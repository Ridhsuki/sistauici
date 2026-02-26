@extends('layouts.app')

@section('title', 'Review Dokumen Akhir')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>

            <p class="text-gray-500 text-sm mt-1">Daftar mahasiswa bimbingan yang telah mengunggah dokumen.
            </p>
        </div>

        <nav class="text-sm font-medium text-gray-500 bg-gray-100 px-4 py-2 rounded-lg">
            <ol class="list-reset flex items-center gap-2">
                <li><a href="{{ route('dosen.dashboard') }}" class="hover:text-green-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('dosen.dokumen-akhir.index') }}" class="hover:text-green-600">Dokumen
                        Akhir</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-green-600">Detail</li>
            </ol>
        </nav>
    </div>
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>

                @if (session('show_grade_button'))
                    <a href="{{ route('dosen.nilai-dokumen-akhir.index') }}"
                        class="ml-4 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700 transition shadow-md">
                        Beri Nilai Akhir Sekarang &rarr;
                    </a>
                @endif
            </div>
        </div>
    @endif
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div
                class="h-16 w-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl font-bold border border-green-100">
                {{ substr($mahasiswa->name, 0, 1) }}
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-xl font-bold text-gray-800">{{ $mahasiswa->name }}</h2>
                <div
                    class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-sm text-gray-500 mt-1 font-medium">
                    <span class="flex items-center"><i class="far fa-id-badge mr-2"></i>{{ $mahasiswa->NIM ?? '-' }}</span>
                    <span class="hidden md:inline text-gray-300">|</span>
                    <span class="flex items-center"><i
                            class="fas fa-graduation-cap mr-2"></i>{{ $mahasiswa->prodi ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4" x-data="{
        open: false,
        modalTitle: '',
        status: '',
        catatan: '',
        actionUrl: '',
        openReview(id, title, currentStatus, currentNote) {
            this.modalTitle = title;
            this.status = currentStatus;
            this.catatan = currentNote || '';
            this.actionUrl = `/dosen/dokumen-akhir/${id}/update-status`;
            this.open = true;
        }
    }">
        @foreach ($chapters as $key => $chapterName)
            @php
                $dokumen = $uploads[$key] ?? null;
                $status = $dokumen ? $dokumen->status : 'none';

                $statusConfig = match ($status) {
                    'approved' => [
                        'bg' => 'bg-green-100',
                        'text' => 'text-green-700',
                        'label' => 'DISETUJUI',
                        'border' => 'border-green-500',
                    ],
                    'rejected' => [
                        'bg' => 'bg-red-100',
                        'text' => 'text-red-700',
                        'label' => 'REVISI',
                        'border' => 'border-red-500',
                    ],
                    'pending' => [
                        'bg' => 'bg-amber-100',
                        'text' => 'text-amber-700',
                        'label' => 'PENDING',
                        'border' => 'border-amber-500',
                    ],
                    default => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-500',
                        'label' => 'BELUM UPLOAD',
                        'border' => 'border-gray-200',
                    ],
                };
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border-l-4 {{ $statusConfig['border'] }} overflow-hidden">
                <div class="p-5 md:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-base font-bold text-gray-800">{{ $chapterName }}</h3>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} uppercase border border-current opacity-70">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>

                            @if ($dokumen)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Judul
                                            Dokumen</p>
                                        <p class="text-sm font-bold text-gray-700 truncate" title="{{ $dokumen->judul }}">
                                            {{ $dokumen->judul }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Waktu
                                            Upload</p>
                                        <p class="text-sm font-bold text-gray-700">
                                            {{ $dokumen->created_at->translatedFormat('d M Y, H:i') }}</p>
                                    </div>
                                </div>

                                @if ($dokumen->deskripsi)
                                    <div
                                        class="mb-4 text-sm bg-blue-50/50 p-3 rounded-xl italic text-gray-600 border-l-2 border-blue-200">
                                        <span
                                            class="font-bold not-italic text-[10px] text-blue-400 uppercase block mb-1">Pesan
                                            Mahasiswa:</span>
                                        "{{ $dokumen->deskripsi }}"
                                    </div>
                                @endif

                                @if ($dokumen->catatan_dosen)
                                    <div
                                        class="p-3 rounded-xl border border-dashed border-amber-200 bg-amber-50/30 text-sm">
                                        <span class="font-bold text-[10px] text-amber-600 uppercase block mb-1">Review Anda
                                            Sebelumnya:</span>
                                        <p class="text-gray-700">{{ $dokumen->catatan_dosen }}</p>
                                    </div>
                                @endif

                                @if (isset($uploadsHistory[$key]) && $uploadsHistory[$key]->count() > 1)
                                    <div x-data="{ showHistory: false }" class="mt-4 pt-4 border-t border-gray-100">
                                        <button @click="showHistory = !showHistory" type="button"
                                            class="flex items-center text-xs font-bold text-gray-400 hover:text-green-600 transition-colors uppercase tracking-wider focus:outline-none">
                                            <svg :class="{ 'rotate-180': showHistory }"
                                                class="w-3.5 h-3.5 mr-1.5 transition-transform duration-200" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            <span
                                                x-text="showHistory ? 'Tutup Riwayat Revisi' : 'Lihat File Revisi Sebelumnya ({{ $uploadsHistory[$key]->count() - 1 }})'"></span>
                                        </button>

                                        <div x-show="showHistory" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 -translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;"
                                            class="mt-4 pl-2">
                                            <div class="border-l-2 border-gray-200 ml-3 space-y-4 pb-2">
                                                @foreach ($uploadsHistory[$key]->skip(1) as $history)
                                                    <div class="relative pl-6">
                                                        <span
                                                            class="absolute -left-[9px] top-1.5 h-4 w-4 rounded-full border-2 border-white {{ $history->status == 'rejected' ? 'bg-red-400' : 'bg-gray-400' }}"></span>
                                                        <div class="bg-gray-50/70 border border-gray-100 p-3 rounded-xl">
                                                            <div
                                                                class="flex flex-wrap justify-between items-start mb-2 gap-2">
                                                                <div>
                                                                    <span
                                                                        class="text-[10px] font-bold text-gray-400 uppercase block mb-0.5">Versi
                                                                        {{ $history->created_at->translatedFormat('d M Y, H:i') }}</span>
                                                                    <span
                                                                        class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $history->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-600' }}">
                                                                        {{ $history->status == 'rejected' ? 'Ditolak/Revisi' : ucfirst($history->status) }}
                                                                    </span>
                                                                </div>
                                                                <a href="{{ asset('storage/' . $history->file) }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center px-2 py-1 bg-white border border-gray-200 text-gray-600 rounded shadow-sm hover:bg-gray-100 text-xs font-bold transition">
                                                                    <i class="fas fa-file-download mr-1.5"></i> File Lama
                                                                </a>
                                                            </div>
                                                            @if ($history->catatan_dosen)
                                                                <div
                                                                    class="mt-2 text-xs bg-red-50 text-red-700 p-2 rounded-lg border border-red-100">
                                                                    <span
                                                                        class="font-bold text-[10px] uppercase block mb-0.5">Catatan
                                                                        Penolakan:</span>
                                                                    {{ $history->catatan_dosen }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-400 italic">Menunggu mahasiswa mengunggah dokumen...</p>
                            @endif
                        </div>

                        @if ($dokumen)
                            <div class="flex flex-row lg:flex-col gap-2 mt-2 lg:mt-0">
                                <a href="{{ asset('storage/' . $dokumen->file) }}" target="_blank"
                                    class="flex-1 lg:w-40 flex items-center justify-center px-4 py-2.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl font-bold text-xs uppercase hover:bg-blue-100 transition shadow-sm"
                                    title="Lihat file versi terakhir">
                                    <i class="fas fa-file-alt mr-2"></i> File Terbaru
                                </a>

                                @if ($dokumen->status === 'approved')
                                    <button disabled
                                        class="flex-1 lg:w-40 flex items-center justify-center px-4 py-2.5 bg-gray-50 text-gray-400 border border-gray-200 rounded-xl font-bold text-xs uppercase cursor-not-allowed"
                                        title="Dokumen ini telah disetujui">
                                        <i class="fas fa-check-double mr-2"></i> Disetujui
                                    </button>
                                @elseif ($dokumen->status === 'rejected')
                                    <button
                                        @click="openReview({{ $dokumen->id }}, '{{ $chapterName }}', '{{ $dokumen->status }}', '{{ addslashes($dokumen->catatan_dosen ?? '') }}')"
                                        class="flex-1 lg:w-40 flex items-center justify-center px-4 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl font-bold text-xs uppercase hover:bg-amber-100 transition shadow-sm"
                                        title="Edit catatan revisi">
                                        <i class="fas fa-edit mr-2"></i> Edit Review
                                    </button>
                                @else
                                    <button
                                        @click="openReview({{ $dokumen->id }}, '{{ $chapterName }}', '{{ $dokumen->status }}', '{{ addslashes($dokumen->catatan_dosen ?? '') }}')"
                                        class="flex-1 lg:w-40 flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-xl font-bold text-xs uppercase hover:bg-green-700 shadow-md shadow-green-100 transition"
                                        title="Berikan keputusan review">
                                        <i class="fas fa-clipboard-check mr-2"></i> Review
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[60] flex items-end md:items-center justify-center p-0 md:p-4 bg-gray-900/60 backdrop-blur-sm"
            x-cloak>

            <div @click.away="open = false" x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 md:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                class="bg-white w-full max-w-lg rounded-t-3xl md:rounded-2xl shadow-2xl overflow-hidden">

                <form :action="actionUrl" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800" x-text="'Review ' + modalTitle"></h3>
                        <button type="button" @click="open = false"
                            class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-3">Keputusan
                                Review</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="approved" x-model="status"
                                        class="peer sr-only">
                                    <div
                                        class="p-3 border-2 rounded-xl text-center transition peer-checked:border-green-600 peer-checked:bg-green-50 hover:bg-gray-50">
                                        <i class="fas fa-check-circle text-lg mb-1 block peer-checked:text-green-600"></i>
                                        <span class="text-xs font-bold text-gray-700">Setujui</span>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="rejected" x-model="status"
                                        class="peer sr-only">
                                    <div
                                        class="p-3 border-2 rounded-xl text-center transition peer-checked:border-red-600 peer-checked:bg-red-50 hover:bg-gray-50">
                                        <i
                                            class="fas fa-exclamation-circle text-lg mb-1 block peer-checked:text-red-600"></i>
                                        <span class="text-xs font-bold text-gray-700">Revisi</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Catatan
                                Pembimbing</label>
                            <textarea name="catatan_dosen" x-model="catatan" rows="4"
                                class="w-full rounded-xl border-gray-200 focus:ring-green-500 focus:border-green-500 text-sm font-medium"
                                placeholder="Berikan alasan persetujuan atau detail revisi..."></textarea>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 flex flex-col md:flex-row gap-2">
                        <button type="submit"
                            class="w-full md:order-2 px-6 py-3 bg-green-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-green-100 hover:bg-green-700 transition">
                            Simpan Review
                        </button>
                        <button type="button" @click="open = false"
                            class="w-full md:order-1 px-6 py-3 bg-white text-gray-500 border border-gray-200 rounded-xl font-bold text-sm hover:bg-gray-100 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
