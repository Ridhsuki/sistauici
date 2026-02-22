@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Bimbingan</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar riwayat sesi bimbingan yang telah selesai atau ditolak.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto">
            <a href="{{ route('dosen.bimbingan.history.pdf') }}" target="_blank"
                class="w-full sm:w-auto flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Export PDF
            </a>
            <nav
                class="w-full sm:w-auto text-sm font-medium text-gray-500 bg-gray-100 px-4 py-2 rounded-lg flex overflow-x-auto">
                <ol class="list-reset flex items-center gap-2 whitespace-nowrap">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors">Home</a></li>
                    <li><span class="mx-2 text-gray-300">/</span></li>
                    <li><a href="{{ route('dosen.bimbingan.index') }}"
                            class="hover:text-green-600 transition-colors">Bimbingan</a></li>
                    <li><span class="mx-2 text-gray-300">/</span></li>
                    <li class="text-green-600 font-semibold">Riwayat</li>
                </ol>
            </nav>
        </div>
    </div>

    @php
        // Helper status agar DRY (Don't Repeat Yourself)
$getStatusConfig = function ($status) {
    return match ($status) {
        'approved' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-700',
            'border' => 'border-green-200',
            'icon' => 'M5 13l4 4L19 7',
            'accent' => 'bg-green-500',
            'label' => 'Selesai / Disetujui',
        ],
        'rejected' => [
            'bg' => 'bg-red-100',
            'text' => 'text-red-700',
            'border' => 'border-red-200',
            'icon' => 'M6 18L18 6M6 6l12 12',
            'accent' => 'bg-red-500',
            'label' => 'Ditolak',
        ],
        default => [
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-700',
            'border' => 'border-gray-200',
            'icon' => 'M20 12H4',
            'accent' => 'bg-gray-500',
            'label' => 'Tidak Diketahui',
                ],
            };
        };
    @endphp

    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-4">Mahasiswa</th>
                        <th scope="col" class="px-6 py-4">Jadwal Sesi</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Catatan Singkat</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($bimbingans as $bimbingan)
                        @php $config = $getStatusConfig($bimbingan->status); @endphp
                        <tr class="hover:bg-green-50/50 transition-colors duration-200 group" x-data="{ showDetail: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-green-100 to-green-200 text-green-700 flex items-center justify-center font-bold text-sm shadow-inner">
                                        {{ strtoupper(substr($bimbingan->mahasiswa->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $bimbingan->mahasiswa->name }}</p>
                                        <p class="text-xs text-gray-500">Mahasiswa Bimbingan</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $config['icon'] }}"></path>
                                    </svg>
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($bimbingan->catatan_dosen)
                                    <p class="text-sm text-gray-600 italic line-clamp-2 max-w-xs">
                                        "{{ $bimbingan->catatan_dosen }}"</p>
                                @else
                                    <span class="text-sm text-gray-400 italic">Tidak ada catatan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button @click="showDetail = true"
                                    class="inline-flex items-center text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Detail
                                </button>

                                <div x-show="showDetail" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div
                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showDetail" x-transition.opacity
                                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                            @click="showDetail = false" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                            aria-hidden="true">&#8203;</span>
                                        <div x-show="showDetail" x-transition.scale
                                            class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div
                                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                        <svg class="h-6 w-6 text-green-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-bold text-gray-900"
                                                            id="modal-title">
                                                            Detail Riwayat Bimbingan
                                                        </h3>
                                                        <div class="mt-4 space-y-4">
                                                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                                <p
                                                                    class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                                    Mahasiswa</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $bimbingan->mahasiswa->name }}</p>
                                                            </div>
                                                            <div class="grid grid-cols-2 gap-4">
                                                                <div
                                                                    class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                                    <p
                                                                        class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                                        Tanggal</p>
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                                    <p
                                                                        class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                                        Waktu</p>
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                                                        WIB</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                                                                <p
                                                                    class="text-xs text-blue-800 font-bold uppercase tracking-wider mb-2">
                                                                    Catatan Evaluasi Pembimbing</p>
                                                                <p
                                                                    class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                                                    {{ $bimbingan->catatan_dosen ?: 'Tidak ada catatan evaluasi untuk sesi ini.' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                <button type="button" @click="showDetail = false"
                                                    class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 p-4 rounded-full mb-4">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada riwayat bimbingan</h3>
                                    <p class="text-sm text-gray-500">Sesi bimbingan yang telah selesai atau ditolak akan
                                        muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="block md:hidden space-y-4">
        @forelse ($bimbingans as $bimbingan)
            @php $config = $getStatusConfig($bimbingan->status); @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group"
                x-data="{ showDetail: false }">

                <div class="h-1.5 w-full {{ $config['accent'] }}"></div>

                <div class="p-5 flex-1 flex flex-col relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="h-10 w-10 rounded-full bg-gradient-to-br from-green-100 to-green-200 text-green-700 flex items-center justify-center font-bold shadow-inner">
                            {{ strtoupper(substr($bimbingan->mahasiswa->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 leading-tight">{{ $bimbingan->mahasiswa->name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Mahasiswa Bimbingan</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $config['icon'] }}"></path>
                            </svg>
                            {{ $config['label'] }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3.5 mb-4 border border-gray-100">
                        <div class="flex items-center text-sm text-gray-700 mb-2.5">
                            <svg class="w-4 h-4 mr-2.5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="font-medium">{{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 mr-2.5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                - {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <span class="font-bold block text-[10px] uppercase tracking-wider text-gray-400 mb-1">Catatan
                            Evaluasi:</span>
                        @if ($bimbingan->catatan_dosen)
                            <p class="text-sm text-gray-700 line-clamp-2 italic">"{{ $bimbingan->catatan_dosen }}"</p>
                        @else
                            <span class="text-sm text-gray-400 italic">Tidak ada catatan</span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 mt-auto">
                    <button @click="showDetail = true"
                        class="w-full flex items-center justify-center px-4 py-2 border border-green-200 shadow-sm text-sm font-semibold rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Detail Riwayat
                    </button>

                    <div x-show="showDetail" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showDetail" x-transition.opacity
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                @click="showDetail = false" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div x-show="showDetail" x-transition.scale
                                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                                Detail Riwayat Bimbingan
                                            </h3>
                                            <div class="mt-4 space-y-4">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                    <p
                                                        class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                        Mahasiswa</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $bimbingan->mahasiswa->name }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                        <p
                                                            class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                            Tanggal</p>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                        <p
                                                            class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                                                            Waktu</p>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                                            WIB</p>
                                                    </div>
                                                </div>
                                                <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                                                    <p
                                                        class="text-xs text-blue-800 font-bold uppercase tracking-wider mb-2">
                                                        Catatan Evaluasi Pembimbing</p>
                                                    <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                                        {{ $bimbingan->catatan_dosen ?: 'Tidak ada catatan evaluasi untuk sesi ini.' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                    <button type="button" @click="showDetail = false"
                                        class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <div class="mx-auto h-12 w-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Belum ada riwayat bimbingan</h3>
                <p class="mt-1 text-xs text-gray-500 max-w-xs mx-auto">Sesi bimbingan yang selesai/ditolak akan muncul di
                    sini.</p>
            </div>
        @endforelse
    </div>

    @if ($bimbingans->hasPages())
        <div class="mt-6 px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100 hidden md:block">
            {{ $bimbingans->links() }}
        </div>
        <div class="mt-6 block md:hidden">
            {{ $bimbingans->links() }}
        </div>
    @endif
@endsection
