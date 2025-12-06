@extends('layouts.app')

@section('content')
    <div class="mt-6 max-w-3xl mx-auto space-y-4">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Edit Dokumen
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-xl shadow-lg rounded-2xl border border-gray-200 p-6">
            <form action="{{ route('documents.update', $document) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1">Judul (opsional)</label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Konten</label>
                    <textarea name="content" rows="8"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('content', $document->content) }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:underline">
                        ← Kembali ke daftar
                    </a>

                    <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
