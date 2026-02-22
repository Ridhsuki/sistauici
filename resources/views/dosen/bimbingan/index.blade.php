@extends('layouts.app')

@section('title', 'Manajemen Bimbingan')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Bimbingan</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola jadwal, status persetujuan, dan prosedur bimbingan mahasiswa.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dosen.bimbingan.history') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Lihat Riwayat
            </a>
            <nav class="text-sm font-medium text-gray-500 bg-gray-100 px-4 py-2 rounded-lg">
                <ol class="list-reset flex items-center gap-2">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-green-600">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-green-600">Bimbingan</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center justify-between bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6">
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($bimbingans as $bimbingan)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 overflow-hidden flex flex-col h-full"
                x-data="{ showStatusModal: false, showDetailModal: false }">

                @php
                    $statusColor = match ($bimbingan->status) {
                        'pending' => 'bg-yellow-500',
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        default => 'bg-gray-500',
                    };
                    $statusLabel = match ($bimbingan->status) {
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Unknown',
                    };
                @endphp
                <div class="h-1.5 w-full {{ $statusColor }}"></div>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-lg">
                                {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $bimbingan->mahasiswa->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">Mahasiswa</p>
                            </div>
                        </div>
                        <span
                            class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $bimbingan->status == 'approved' ? 'bg-green-100 text-green-700' : ($bimbingan->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($bimbingan->status) }}
                        </span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB
                        </div>

                        <div class="flex gap-2 mt-2">
                            @if ($bimbingan->link_meet)
                                <a href="{{ $bimbingan->link_meet }}" target="_blank"
                                    class="text-xs flex items-center text-blue-600 bg-blue-50 px-2 py-1 rounded hover:bg-blue-100"
                                    title="Link Zoom tersedia">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Zoom/Meet
                                </a>
                            @endif
                            @if ($bimbingan->file_prosedur)
                                <a href="{{ asset('storage/' . $bimbingan->file_prosedur) }}" target="_blank"
                                    class="text-xs flex items-center text-purple-600 bg-purple-50 px-2 py-1 rounded hover:bg-purple-100"
                                    title="Prosedur tersedia">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Prosedur
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($bimbingan->catatan_dosen)
                        <div class="mt-auto bg-gray-50 p-3 rounded-lg text-sm italic text-gray-600 border border-gray-100">
                            "{{ Str::limit($bimbingan->catatan_dosen, 80) }}"
                        </div>
                    @else
                        <div class="mt-auto"></div>
                    @endif
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 grid grid-cols-2 gap-3">
                    <button @click="showStatusModal = true"
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        Status
                    </button>

                    @if ($bimbingan->status == 'approved')
                        <button @click="showDetailModal = true"
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Link & Info
                        </button>
                    @else
                        <button disabled
                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">
                            Link & Info
                        </button>
                    @endif
                </div>

                <div x-show="showStatusModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showStatusModal" x-transition.opacity
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showStatusModal" @click.away="showStatusModal = false" x-transition.scale
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                            <form action="{{ route('dosen.bimbingan.updateStatus', $bimbingan->id) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Update
                                        Status Bimbingan</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <select name="status"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                                <option value="pending" @selected($bimbingan->status == 'pending')>Pending</option>
                                                <option value="approved" @selected($bimbingan->status == 'approved')>Disetujui</option>
                                                <option value="rejected" @selected($bimbingan->status == 'rejected')>Ditolak</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                                Dosen (setelah bimbingan)</label>
                                            <textarea name="catatan_dosen" rows="3"
                                                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="Berikan catatan untuk mahasiswa...">{{ $bimbingan->catatan_dosen }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                    <button type="button" @click="showStatusModal = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showDetailModal" x-transition.opacity
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="showDetailModal" @click.away="showDetailModal = false" x-transition.scale
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                            <form action="{{ route('dosen.bimbingan.updateDetails', $bimbingan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="flex items-center gap-3 mb-4 border-b pb-2">
                                        <div class="p-2 bg-green-100 rounded-full text-green-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Kelengkapan Bimbingan</h3>
                                            <p class="text-xs text-gray-500">Tambahkan link zoom dan prosedur untuk
                                                mahasiswa.</p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Link Zoom / Google
                                                Meet</label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <span
                                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                                    https://
                                                </span>
                                                <input type="text" name="link_meet"
                                                    value="{{ $bimbingan->link_meet }}"
                                                    class="focus:ring-green-500 focus:border-green-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                    placeholder="zoom.us/j/...">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Prosedur
                                                (Gambar)
                                            </label>
                                            <div
                                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                                                <div class="space-y-1 text-center">
                                                    @if ($bimbingan->file_prosedur)
                                                        <div class="mb-2">
                                                            <p class="text-xs text-green-600 font-semibold">File saat ini
                                                                tersimpan:</p>
                                                            <img src="{{ asset('storage/' . $bimbingan->file_prosedur) }}"
                                                                class="h-20 mx-auto object-cover rounded shadow-sm">
                                                        </div>
                                                    @else
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path
                                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    @endif
                                                    <div class="text-sm text-gray-600">
                                                        <label for="file-upload-{{ $bimbingan->id }}"
                                                            class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                            <span>Upload file baru</span>
                                                            <input id="file-upload-{{ $bimbingan->id }}"
                                                                name="file_prosedur" type="file" class="sr-only"
                                                                accept="image/*">
                                                        </label>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan
                                        Detail</button>
                                    <button type="button" @click="showDetailModal = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div
                class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Jadwal</h3>
                <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Saat ini belum ada mahasiswa yang mengajukan jadwal
                    bimbingan aktif. Cek halaman riwayat untuk melihat sesi yang sudah berlalu.</p>
                <a href="{{ route('dosen.bimbingan.history') }}"
                    class="mt-4 inline-flex items-center text-green-600 hover:text-green-800 font-medium text-sm">
                    Lihat Riwayat Bimbingan <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        @endforelse
    </div>
@endsection
