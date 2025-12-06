<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // List semua dokumen
    public function index()
    {
        $documents = Document::latest()->get(); // <– BUKAN paginate()

        return view('documents.index', compact('documents'));
    }

    // Hapus dokumen
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('status', 'Dokumen berhasil dihapus.');
    }
}
