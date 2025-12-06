@extends('layouts.app')

@section('content')
    <div class="mt-6 space-y-4">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Management Dataset</h1>
        </div>

        {{-- Alert status --}}
        @if (session('status'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-xl shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
            @if ($documents->count() === 0)
                <div class="p-6 text-center text-gray-500 text-sm">
                    Belum ada dokumen. Silakan upload data terlebih dahulu.
                </div>
            @else
                <div class="overflow-x-auto p-4">
                    <table id="documents-table" class="min-w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-wide text-gray-700 uppercase">
                                    No
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-wide text-gray-700 uppercase">
                                    Judul
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-wide text-gray-700 uppercase">
                                    Preview Konten
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold tracking-wide text-gray-700 uppercase">
                                    Diupload
                                </th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold tracking-wide text-gray-700 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white/50 backdrop-blur divide-y divide-gray-200">
                            @foreach ($documents as $doc)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- NOMOR URUT (akan diisi oleh DataTables) --}}
                                    <td class="px-5 py-4 text-gray-700 font-medium"></td>

                                    {{-- JUDUL --}}
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $doc->title ?: '(Tanpa Judul)' }}
                                        </div>
                                    </td>

                                    {{-- PREVIEW KONTEN --}}
                                    <td class="px-5 py-4 max-w-lg text-gray-600 leading-relaxed">
                                        <div class="line-clamp-3">
                                            {{ \Illuminate\Support\Str::limit($doc->content, 180) }}
                                        </div>
                                    </td>

                                    {{-- TANGGAL --}}
                                    <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                        {{ $doc->created_at?->format('d M Y') }}<br>
                                        <span class="text-xs text-gray-400">
                                            {{ $doc->created_at?->format('H:i') }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-5 py-4 text-right flex justify-end gap-2">
                                        {{-- TOMBOL EDIT --}}
                                        <a href="{{ route('documents.edit', $doc) }}"
                                            class="px-4 py-2 text-xs font-semibold bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 shadow-md">
                                            Edit
                                        </a>
                                        <form action="{{ route('documents.destroy', $doc) }}" method="POST"
                                            onsubmit="return confirm('Hapus dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 text-xs font-semibold bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-md">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- jQuery + DataTables CDN --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#documents-table').DataTable({
                pageLength: 10,
                order: [
                    [3, 'desc']
                ], // sort default berdasarkan tanggal
                columnDefs: [{
                        orderable: false,
                        targets: [2, 4]
                    } // preview & aksi tidak bisa sort
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "›",
                        previous: "‹"
                    },
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                }
            });

            // Nomor urut dinamis sesuai sort & search
            table.on('order.dt search.dt', function() {
                let i = 1;
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell) {
                    cell.innerHTML = i++;
                });
            }).draw();
        });
    </script>
@endsection
