@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- LEFT SIDE — FORM INPUT (Light Glassmorphism) --}}
        <div class="shadow-xl rounded-2xl p-6 border border-white/50 h-fit backdrop-blur-lg bg-white/70">

            <h3 class="text-xl font-semibold mb-3 flex items-center gap-2 text-gray-800">
                ✏️ Input Pertanyaan
            </h3>

            <form id="chatForm" class="space-y-4">
                @csrf

                <textarea name="question" id="question" rows="6"
                    class="w-full p-4 border rounded-xl shadow-inner bg-gray-50/70 text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    placeholder="Contoh: Saya pusing dan mual, obat apa yang cocok?"></textarea>

                <button type="submit" id="sendBtn"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg transition disabled:opacity-50">
                    Kirim Pertanyaan
                </button>
            </form>

            {{-- LOADING --}}
            <div id="loading" class="mt-6 text-center hidden">
                <div class="mx-auto h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                </div>
                <p class="mt-3 text-gray-600 text-sm">⏳ Memproses pertanyaan...</p>
            </div>
        </div>



        {{-- RIGHT SIDE — OUTPUT (Light Glassmorphism) --}}
        <div id="result"
            class="hidden shadow-xl rounded-2xl p-6 border border-white/50 h-fit backdrop-blur-lg bg-white/70">

            {{-- Jawaban --}}
            <div>
                <h5 class="text-xl font-semibold mb-3 flex items-center gap-2 text-gray-800">
                    🤖 Jawaban AI
                </h5>

                <div id="answerBox"
                    class="prose bg-blue-50/70 border border-blue-200/50 p-4 rounded-xl shadow-sm text-gray-800">
                </div>
            </div>

            <hr class="my-5 border-gray-200">

            {{-- CONFIDENCE --}}
            <div class="flex flex-col items-center py-4">

                <div class="relative mb-2">
                    <svg id="confidenceCircle" width="100" height="100" class="transition-all">
                        <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-opacity="1" stroke-width="9"
                            fill="none" />
                        <circle id="circleProgress" cx="50" cy="50" r="42" stroke="#3b82f6" stroke-width="9"
                            fill="none" stroke-linecap="round" stroke-dasharray="264" stroke-dashoffset="264"
                            transform="rotate(-90 50 50)" class="transition-all duration-700">
                        </circle>
                    </svg>

                    {{-- Glow --}}
                    <div id="circleGlow" class="absolute inset-0 rounded-full blur-xl opacity-30 transition-all"
                        style="background: #3b82f6;"></div>
                </div>

                <p class="text-lg font-semibold text-gray-800">
                    Confidence: <span id="confidenceCircleText">0%</span>
                </p>

                <p class="text-gray-500 text-sm text-center max-w-xs">
                    Confidence menunjukkan tingkat keyakinan sistem terhadap jawaban berdasarkan kecocokan dokumen.
                </p>
            </div>

            <hr class="my-5 border-gray-200">

            {{-- Dokumen Relevan --}}
            <div>
                <h6 class="font-semibold mb-3 flex items-center gap-1 text-gray-800">📄 Dokumen relevan</h6>
                <div id="matchList" class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-white/50 border border-gray-200 rounded-full text-sm text-gray-800 shadow-sm">
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- AJAX SCRIPT (Memperbarui kelas di JavaScript) --}}
    <script>
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const question = document.getElementById('question').value;
            if (!question.trim()) return;

            const sendBtn = document.getElementById('sendBtn');
            const loading = document.getElementById('loading');
            const result = document.getElementById('result');

            sendBtn.disabled = true;
            loading.classList.remove("hidden");
            result.classList.add("hidden");

            fetch("{{ route('rag.chat') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        question
                    })
                })
                .then(res => res.json())
                .then(data => {
                    loading.classList.add("hidden");
                    sendBtn.disabled = false;

                    if (data.error) {
                        document.getElementById('answerBox').innerHTML = "<b>Error:</b> " + data.error;
                        result.style.display = "block";
                        return;
                    }

                    // Output jawaban
                    document.getElementById('answerBox').innerHTML = data.answer.replace(/\n/g, "<br>");

                    // Confidence (Logic warna tetap sama)
                    const circle = document.getElementById("circleProgress");
                    const textConf = document.getElementById("confidenceCircleText");
                    const glow = document.getElementById("circleGlow");

                    const val = data.confidence;
                    const max = 264;
                    const offset = max - (max * val / 100);

                    circle.style.strokeDashoffset = offset;
                    textConf.innerText = val + "%";

                    // Catatan: Warna teks Conf dan Glow akan diubah melalui JavaScript
                    if (val < 40) {
                        circle.style.stroke = "#ef4444"; // Red
                        textConf.style.color = "#ef4444";
                        glow.style.background = "#ef4444";
                    } else if (val < 75) {
                        circle.style.stroke = "#f59e0b"; // Amber/Yellow
                        textConf.style.color = "#f59e0b";
                        glow.style.background = "#f59e0b";
                    } else {
                        circle.style.stroke = "#22c55e"; // Green
                        textConf.style.color = "#22c55e";
                        glow.style.background = "#22c55e";
                    }

                    // Dokumen relevan (Perubahan kelas di sini)
                    let list = "";
                    data.matches.forEach(m => {
                        // Mengubah badge menjadi Light Glassy
                        list += `<span class="px-3 py-1 bg-white/50 border border-gray-200 rounded-full text-sm text-gray-800 shadow-sm">
                                <strong>${m.title}</strong>
                                <span class="text-gray-500">(${m.score})</span>
                              </span>`;
                    });
                    document.getElementById('matchList').innerHTML = list;

                    result.classList.remove("hidden");
                })
                .catch(err => {
                    loading.classList.add("hidden");
                    sendBtn.disabled = false;
                    alert("Terjadi error AJAX: " + err);
                });
        });
    </script>
@endsection
