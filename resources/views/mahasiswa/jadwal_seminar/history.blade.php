@extends('layouts.app')

@section('title', 'Riwayat Bimbingan Saya')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Bimbingan</h1>
            <p class="text-gray-500 text-sm mt-1">Lacak progres dan evaluasi dari dosen pembimbing Anda sebelumnya.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto">
            <a href="{{ route('mahasiswa.bimbingan.history.pdf') }}" target="_blank"
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
                    <li><a href="{{ route('mahasiswa.jadwal-seminar') }}"
                            class="hover:text-green-600 transition-colors">Jadwal</a></li>
                    <li><span class="mx-2 text-gray-300">/</span></li>
                    <li class="text-green-600 font-semibold">Riwayat</li>
                </ol>
            </nav>
        </div>
    </div>

    @php
        $getStatusConfig = function ($status) {
            return match ($status) {
                'approved' => [
                    'bg' => 'bg-green-100',
                    'text' => 'text-green-700',
                    'border' => 'border-green-200',
                    'icon' => 'M5 13l4 4L19 7',
                    'accent' => 'bg-green-500',
                    'label' => 'Selesai',
                ],
                'rejected' => [
                    'bg' => 'bg-red-100',
                    'text' => 'text-red-700',
                    'border' => 'border-red-200',
                    'icon' => 'M6 18L18 6M6 6l12 12',
                    'accent' => 'bg-red-500',
                    'label' => 'Ditolak/Batal',
                ],
                default => [
                    'bg' => 'bg-gray-100',
                    'text' => 'text-gray-700',
                    'border' => 'border-gray-200',
                    'icon' => 'M20 12H4',
                    'accent' => 'bg-gray-500',
                    'label' => 'Status Unknown',
                ],
            };
        };
    @endphp

    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-4">Dosen Pembimbing</th>
                        <th scope="col" class="px-6 py-4">Pelaksanaan</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Evaluasi Dosen</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($bimbingans as $bimbingan)
                        @php $config = $getStatusConfig($bimbingan->status); @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors duration-200 group" x-data="{ showDetail: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-bold text-sm shadow-inner">
                                        {{ strtoupper(substr($bimbingan->dosen->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $bimbingan->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">Dosen Pembimbing</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
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
                                    <p class="text-sm text-gray-700 line-clamp-2 max-w-xs font-medium">
                                        "{{ $bimbingan->catatan_dosen }}"</p>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum ada evaluasi dicatat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button @click="showDetail = true"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Evaluasi
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
                                            <div class="bg-green-600 px-4 py-4 sm:px-6 flex justify-between items-center">
                                                <h3 class="text-lg leading-6 font-bold text-white flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Hasil Evaluasi Bimbingan
                                                </h3>
                                                <button @click="showDetail = false"
                                                    class="text-green-100 hover:text-white">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="space-y-5">
                                                    <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
                                                        <div
                                                            class="h-12 w-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-lg">
                                                            {{ strtoupper(substr($bimbingan->dosen->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p
                                                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">
                                                                Dosen Pembimbing</p>
                                                            <p class="text-base font-bold text-gray-900">
                                                                {{ $bimbingan->dosen->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div
                                                            class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                                                            <div class="p-2 bg-white rounded shadow-sm text-gray-500">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 font-semibold uppercase">
                                                                    Tanggal</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d/m/Y') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                                                            <div class="p-2 bg-white rounded shadow-sm text-gray-500">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-xs text-gray-500 font-semibold uppercase">
                                                                    Waktu</p>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Catatan
                                                            & Revisi (Jika Ada)</label>
                                                        <div
                                                            class="bg-yellow-50/50 p-4 rounded-lg border border-yellow-200">
                                                            @if ($bimbingan->catatan_dosen)
                                                                <p
                                                                    class="text-sm text-gray-800 whitespace-pre-line leading-relaxed font-medium">
                                                                    {{ $bimbingan->catatan_dosen }}</p>
                                                            @else
                                                                <p class="text-sm text-gray-500 italic text-center py-2">
                                                                    Tidak ada catatan yang dilampirkan oleh dosen pada sesi
                                                                    ini.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end border-t border-gray-100">
                                                <button type="button" @click="showDetail = false"
                                                    class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors">Tutup</button>
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
                                    <div class="bg-blue-50 p-4 rounded-full mb-4">
                                        <svg class="h-10 w-10 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Riwayat Kosong</h3>
                                    <p class="text-sm text-gray-500">Anda belum memiliki riwayat bimbingan yang telah
                                        selesai.</p>
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
                            class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-bold shadow-inner">
                            {{ strtoupper(substr($bimbingan->dosen->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 leading-tight">{{ $bimbingan->dosen->name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Dosen Pembimbing</p>
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

                    <div class="bg-blue-50/30 rounded-lg p-3.5 mb-4 border border-gray-100">
                        <div class="flex items-center text-sm text-gray-700 mb-2.5">
                            <svg class="w-4 h-4 mr-2.5 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="font-medium">{{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-700">
                            <svg class="w-4 h-4 mr-2.5 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                - {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        @if ($bimbingan->catatan_dosen)
                            <p class="text-sm text-gray-700 line-clamp-2 italic">"{{ $bimbingan->catatan_dosen }}"</p>
                        @else
                            <span class="text-sm text-gray-400 italic">Belum ada evaluasi dicatat</span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 mt-auto">
                    <button @click="showDetail = true"
                        class="w-full flex items-center justify-center px-4 py-2 border border-blue-200 shadow-sm text-sm font-semibold rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lihat Detail Evaluasi
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
                                <div class="bg-green-600 px-4 py-4 sm:px-6 flex justify-between items-center">
                                    <h3 class="text-lg leading-6 font-bold text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Hasil Evaluasi Bimbingan
                                    </h3>
                                    <button @click="showDetail = false" class="text-green-100 hover:text-white">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="space-y-5">
                                        <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
                                            <div
                                                class="h-12 w-12 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(substr($bimbingan->dosen->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p
                                                    class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">
                                                    Dosen Pembimbing</p>
                                                <p class="text-base font-bold text-gray-900">{{ $bimbingan->dosen->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div
                                                class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                                                <div class="p-2 bg-white rounded shadow-sm text-gray-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 font-semibold uppercase">Tanggal</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 p-3 rounded-lg border border-gray-100 flex items-center gap-3">
                                                <div class="p-2 bg-white rounded shadow-sm text-gray-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 font-semibold uppercase">Waktu</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Catatan
                                                & Revisi (Jika Ada)</label>
                                            <div class="bg-yellow-50/50 p-4 rounded-lg border border-yellow-200">
                                                @if ($bimbingan->catatan_dosen)
                                                    <p
                                                        class="text-sm text-gray-800 whitespace-pre-line leading-relaxed font-medium">
                                                        {{ $bimbingan->catatan_dosen }}</p>
                                                @else
                                                    <p class="text-sm text-gray-500 italic text-center py-2">Tidak ada
                                                        catatan yang dilampirkan oleh dosen pada sesi ini.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end border-t border-gray-100">
                                    <button type="button" @click="showDetail = false"
                                        class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm transition-colors">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <div class="mx-auto h-12 w-12 bg-blue-50 rounded-full flex items-center justify-center mb-3 text-blue-500">
                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Riwayat Kosong</h3>
                <p class="mt-1 text-xs text-gray-500 max-w-xs mx-auto">Anda belum memiliki riwayat bimbingan.</p>
            </div>
        @endforelse
    </div>

    @if ($bimbingans->hasPages())
        <div class="mt-6 px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100">
            {{ $bimbingans->links() }}
        </div>
    @endif
@endsection
