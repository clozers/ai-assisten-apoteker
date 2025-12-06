<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Document;
use Illuminate\Support\Facades\Log;

class RAGController extends Controller
{
    public function uploadPage()
    {
        return view('rag.upload');
    }

    public function upload(Request $request)
    {
        // =================================================================
        // SOLUSI: Menaikkan batas waktu eksekusi PHP untuk menangani file besar
        // Mengatur batas waktu menjadi 300 detik (5 menit)
        set_time_limit(300);
        // =================================================================

        $validator = \Validator::make($request->all(), [
            'file' => 'required|file|mimes:txt,csv',
            'title' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validasi gagal, periksa kembali input Anda.');
        }

        try {
            $uploadedFile = $request->file('file');
            $ext = $uploadedFile->getClientOriginalExtension();
            $path = $uploadedFile->getRealPath();
            $baseTitle = $request->title ?? $uploadedFile->getClientOriginalName();

            $chunksToEmbed = [];

            // ==========================
            // HANDLE TXT
            // ==========================
            if ($ext === 'txt') {
                $content = file_get_contents($path);

                $chunksToEmbed[] = [
                    'title' => $baseTitle,
                    'content' => $content
                ];
            }

            // ==========================
            // HANDLE CSV
            // ==========================
            elseif ($ext === 'csv') {
                $rows = array_map('str_getcsv', file($path));
                if (count($rows) <= 1) {
                    return redirect()->back()->with('error', 'CSV kosong atau hanya header.');
                }

                $header = array_shift($rows);

                foreach ($rows as $index => $row) {
                    // Penanganan jika baris memiliki jumlah kolom yang berbeda
                    if (count($row) !== count($header)) {
                        Log::warning("CSV row $index skipped due to column count mismatch.");
                        continue;
                    }

                    $chunk = "";
                    foreach ($header as $i => $h) {
                        $chunk .= "$h: " . ($row[$i] ?? '') . "; ";
                    }

                    $chunksToEmbed[] = [
                        'title' => ($row[0] ?? "row_$index") . " | " . $baseTitle,
                        'content' => trim($chunk)
                    ];
                }
            } else {
                return redirect()->back()->with('error', 'Format file tidak dikenali.');
            }

            if (empty($chunksToEmbed)) {
                return redirect()->back()->with('error', 'File kosong atau format tidak valid.');
            }

            // ==========================
            // EMBEDDING PROCESS
            // ==========================
            $success = 0;
            $failed = [];

            foreach ($chunksToEmbed as $chunk) {

                // Opsional: Tambahkan timeout Guzzle untuk mencegah satu request API macet
                $embResp = Http::timeout(60)->post(
                    "https://generativelanguage.googleapis.com/v1/models/text-embedding-004:embedContent?key=" . env('GEMINI_API_KEY'),
                    [
                        "model" => "text-embedding-004",
                        "content" => [
                            "parts" => [
                                ["text" => $chunk['content']]
                            ]
                        ]
                    ]
                );

                if ($embResp->failed()) {
                    Log::error("Embedding failed for chunk: " . $chunk['title'] . " Response: " . $embResp->body());
                    $failed[] = $chunk['title'];
                    continue;
                }

                $embedding = $embResp->json('embedding.values');

                if (!$embedding) {
                    Log::error("Embedding values missing for chunk: " . $chunk['title']);
                    $failed[] = $chunk['title'];
                    continue;
                }

                Document::create([
                    'title' => $chunk['title'],
                    'content' => $chunk['content'],
                    'embedding' => json_encode($embedding),
                ]);

                $success++;
            }

            $message = "$success dokumen berhasil di-upload dan di-index.";
            if (!empty($failed)) {
                $message .= " Namun, " . count($failed) . " dokumen gagal diproses.";
            }

            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("RAG Upload Exception: " . $e->getMessage() . " on line " . $e->getLine());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }


    // ================================
    // CHAT PAGE
    // ================================
    public function chatPage()
    {
        return view('rag.chat');
    }

    // ================================
    // CHAT (AJAX)
    // ================================
    public function chat(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'question' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $question = $request->question;

        try {
            // Embed query
            $embQ = Http::post(
                "https://generativelanguage.googleapis.com/v1/models/text-embedding-004:embedContent?key=" . env('GEMINI_API_KEY'),
                [
                    "model" => "text-embedding-004",
                    "content" => [
                        "parts" => [
                            ["text" => $question]
                        ]
                    ]
                ]
            );

            if ($embQ->failed()) {
                return response()->json([
                    'error' => 'Embedding question failed',
                    'details' => $embQ->body()
                ], 500);
            }

            $queryVec = $embQ->json('embedding.values');

            // Cosine similarity search
            $docs = Document::all();
            $scored = [];

            foreach ($docs as $d) {
                $vec = json_decode($d->embedding, true);
                if (!$vec || count($vec) !== count($queryVec)) continue;

                $sim = $this->cosineSimilarity($queryVec, $vec);

                $scored[] = [
                    'doc' => $d,
                    'score' => $sim
                ];
            }

            usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
            Log::info("Similarity Results:", array_map(function ($s) {
                return [
                    'title' => $s['doc']->title,
                    'score' => $s['score']
                ];
            }, array_slice($scored, 0, 10)));
            $topK = array_slice($scored, 0, 10);


            $confidence = !empty($topK) ? round($topK[0]['score'] * 100) : 0;

            // Build context
            $context = "";
            $matches = [];

            foreach ($topK as $s) {
                $context .= "<DOC_SCORE:" . round($s['score'], 4) . "> " . $s['doc']->content . "\n\n----\n\n";

                $matches[] = [
                    'title' => $s['doc']->title,
                    'score' => round($s['score'], 6),
                    'content' => $s['doc']->content
                ];
            }

            // Build prompt
            $prompt = "
Kamu adalah asisten apotek.
Jawab berdasarkan dokumen berikut:

=== DOKUMEN TERKAIT ===
$context

=== PERTANYAAN USER ===
$question

Jika jawaban tidak ditemukan di dokumen, jawab:
'Maaf, informasi tidak ditemukan dalam dataset untuk mencegah jawaban yang berpotensi halusinasi.'
";

            // Generate answer
            $genResp = Http::post(
                "https://generativelanguage.googleapis.com/v1/models/" . env('GEMINI_MODEL') . ":generateContent?key=" . env('GEMINI_API_KEY'),
                [
                    "contents" => [
                        [
                            "role" => "user",
                            "parts" => [
                                ["text" => $prompt]
                            ]
                        ]
                    ],
                    "generationConfig" => [
                        "temperature" => 0.0
                    ]
                ]
            );

            if ($genResp->failed()) {
                return response()->json([
                    'error' => 'Generation failed',
                    'details' => $genResp->body()
                ], 500);
            }

            $answer = $genResp->json('candidates.0.content.parts.0.text') ?? "(tidak ada jawaban)";

            return response()->json([
                'answer' => $answer,
                'confidence' => $confidence,
                'matches' => $matches
            ]);
        } catch (\Exception $e) {
            Log::error("Chat exception: " . $e->getMessage());
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
