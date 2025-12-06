@extends('layouts.app')
@section('content')
    <div class="max-w-xl mx-auto mt-8">

        <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
            📄 Upload Dokumen (Obat / SOP)
        </h3>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        {{-- DOWNLOAD TEMPLATE --}}
        <div class="mb-4">
            <a href="{{ asset('template_documents.csv') }}" download
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow transition">
                ⬇️ Download Template CSV
            </a>
        </div>

        <form method="POST" action="{{ route('rag.upload') }}" enctype="multipart/form-data"
            class="space-y-4 bg-white p-6 rounded-xl shadow-md border">
            @csrf

            <input type="text" name="title" placeholder="Judul (opsional)"
                class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <input type="file" name="file" accept=".txt,.csv"
                class="w-full p-3 border rounded-lg shadow-sm bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <button
                class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                Simpan & Index
            </button>
        </form>

    </div>
@endsection
