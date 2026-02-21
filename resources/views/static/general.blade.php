@extends('layouts.app')

@section('title', ucfirst($page))

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $page === 'bantuan' ? 'Pusat Bantuan' : 'Tentang Aplikasi' }}</h1>
        <p class="text-gray-500 text-sm italic">
            {{ $page === 'bantuan' ? 'Panduan singkat penggunaan sistem SISTA.' : 'Informasi mengenai sistem informasi skripsi.' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        @if ($page === 'bantuan')
            <div class="divide-y divide-gray-100" x-data="{ active: null }">

                <div class="group">
                    <button @click="active = (active === 1 ? null : 1)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-700">Bagaimana cara Mahasiswa mengajukan judul?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"
                            :class="active === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-collapse.duration.400ms x-transition.opacity.duration.300ms x-cloak
                        class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                        Mahasiswa dapat menuju menu <span class="font-bold text-green-600">Pengajuan Judul</span>, mengisi
                        form yang tersedia, dan mengunggah berkas proposal dalam format PDF.
                    </div>
                </div>

                <div class="group">
                    <button @click="active = (active === 2 ? null : 2)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-700">Bagaimana Dosen memberikan penilaian?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"
                            :class="active === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-collapse.duration.400ms x-transition.opacity.duration.300ms x-cloak
                        class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                        Dosen pembimbing dapat melihat daftar dokumen masuk di halaman <span
                            class="font-bold text-green-600">Penilaian</span>, lalu menekan tombol "Beri Nilai" untuk
                        mengisi grade dan catatan.
                    </div>
                </div>

                <div class="group">
                    <button @click="active = (active === 3 ? null : 3)"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-700">Kontak Bantuan Teknis</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform"
                            :class="active === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    @php
                        $adminPhone = \App\Models\User::where('role', 'admin')->value('no_hp');

                        if ($adminPhone) {
                            $adminPhone = preg_replace('/[^0-9]/', '', $adminPhone); // hapus spasi & simbol
                            if (str_starts_with($adminPhone, '0')) {
                                $adminPhone = '62' . substr($adminPhone, 1);
                            }
                        }

                        $waMessage =
                            'Halo Admin, saya mengalami kendala teknis pada web ' .
                            config('app.name') .
                            ' (' .
                            url('/') .
                            ').';
                    @endphp

                    <div x-show="active === 3" x-collapse.duration.400ms x-transition.opacity.duration.300ms x-cloak
                        class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                        Jika terjadi kendala teknis (bug/error),
                        silakan hubungi admin melalui WhatsApp

                        @if ($adminPhone)
                            <a href="https://wa.me/{{ $adminPhone }}?text={{ urlencode($waMessage) }}" target="_blank"
                                class="text-green-600 underline">
                                Hubungi Admin
                            </a>
                        @else
                            <span class="text-gray-400 italic">
                                (nomor admin belum tersedia)
                            </span>
                        @endif
                        .
                    </div>

                </div>
            </div>
        @else
            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row gap-10 items-center md:items-start">
                    <div
                        class="w-20 h-20 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-3xl shrink-0">
                        <i class="fas fa-info-circle"></i>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">SISTA (Sistem Informasi Skripsi & Tugas Akhir)</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Aplikasi ini dikembangkan untuk mendigitalkan proses bimbingan dan administrasi tugas akhir
                            mahasiswa Universitas Insan Cita Indonesia (UICI), mencakup pengajuan judul, pemantauan
                            progress, hingga penilaian akhir.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-50 pt-6">
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <i class="fas fa-check text-green-500"></i> Efisiensi Administrasi
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <i class="fas fa-check text-green-500"></i> Transparansi Nilai
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <i class="fas fa-check text-green-500"></i> Monitoring Real-time
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <i class="fas fa-check text-green-500"></i> Arsip Terpusat
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-12 flex justify-between items-center text-[10px] font-bold text-gray-300 uppercase tracking-widest border-t pt-6">
                    <span>Versi 1.0.0</span>
                    <span>&copy; {{ date('Y') }} UICI</span>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
