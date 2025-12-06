<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // List semua dokumen
    public function index()
    {
        $documents = Document::latest()->get(); // DataTables yg handle paging

        return view('documents.index', compact('documents'));
    }

    // Tampil form edit
    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    // Proses update dokumen
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title'   => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $document->update($validated);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Dokumen berhasil diupdate.');
    }

    // Hapus dokumen
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('status', 'Dokumen berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('template_documents.csv'); // atau template_documents_clean.csv

        if (! file_exists($filePath)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download($filePath, 'template_documents.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
