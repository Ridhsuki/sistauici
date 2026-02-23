@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div
        class="bg-gradient-to-r from-green-600 to-teal-500 rounded-2xl p-6 mb-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>

        <div class="relative">
            <div class="text-left">
                <h1 class="text-2xl font-bold mb-2 flex items-center">
                    Selamat Datang, {{ Auth::user()->name }}!
                    <img src="https://raw.githubusercontent.com/Ridhsuki/Ridhsuki/refs/heads/main/img/Hi.gif" alt="hi"
                        class="ml-2 w-8 h-8" loading="lazy">
                </h1>
                <p class="text-green-50 text-sm opacity-90 max-w-2xl leading-relaxed">
                    Pantau terus progres tugas akhirmu. Jangan lupa cek jadwal bimbingan dan pengumuman terbaru dari program
                    studi.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('mahasiswa.proposals.index') }}"
                    class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md hover:border-green-300 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="p-3 rounded-xl transition-colors duration-300 {{ $proposalStatus == 'approved' ? 'bg-green-50 text-green-600 group-hover:bg-green-100' : 'bg-gray-50 text-gray-400 group-hover:bg-green-50 group-hover:text-green-500' }}">
                            <i class="fas fa-file-signature text-xl"></i>
                        </div>
                        <span
                            class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg border {{ $proposalStatus == 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200' }}">
                            {{ str_replace('_', ' ', $proposalStatus) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-green-700 transition-colors">Tahap 1: Proposal
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">Pengajuan judul dan verifikasi dosen pembimbing.</p>
                    </div>
                    <div class="mt-4 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full transition-all duration-1000 ease-out {{ $proposalStatus == 'approved' ? 'bg-green-500 w-full' : 'bg-yellow-400 w-1/2' }}">
                        </div>
                    </div>
                    <div
                        class="absolute bottom-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-x-2 group-hover:translate-x-0">
                        <i class="fas fa-arrow-right text-green-500 text-sm"></i>
                    </div>
                </a>

                <a href="{{ route('mahasiswa.dokumen-akhir.index') }}"
                    class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md hover:border-green-300 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="p-3 rounded-xl transition-colors duration-300 {{ $dokumenStatus == 'approved' ? 'bg-green-50 text-green-600 group-hover:bg-green-100' : 'bg-gray-50 text-gray-400 group-hover:bg-green-50 group-hover:text-green-500' }}">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <span
                            class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg border {{ $dokumenStatus == 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                            {{ str_replace('_', ' ', $dokumenStatus) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-green-700 transition-colors">Tahap 2: Skripsi
                            Akhir</h4>
                        <p class="text-xs text-gray-500 mt-1">Pelaksanaan sidang akhir dan yudisium.</p>
                    </div>
                    <div class="mt-4 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div
                            class="h-full transition-all duration-1000 ease-out {{ $dokumenStatus == 'approved' ? 'bg-green-500 w-full' : 'bg-gray-300 w-0' }}">
                        </div>
                    </div>
                    <div
                        class="absolute bottom-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -translate-x-2 group-hover:translate-x-0">
                        <i class="fas fa-arrow-right text-green-500 text-sm"></i>
                    </div>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-comments-alt mr-3 text-green-600"></i>
                        Interaksi Bimbingan
                    </h3>
                    <a href="{{ route('mahasiswa.proposals.index') }}"
                        class="text-[10px] font-bold text-green-600 uppercase tracking-widest hover:underline">Tambah
                        Log</a>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div
                            class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-green-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-gray-800">{{ $bimbinganDone }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Disetujui</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div
                            class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-yellow-500">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-gray-800">{{ $bimbinganPending }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Menunggu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-green-600 rounded-2xl p-6 text-white flex justify-between items-center shadow-lg shadow-green-100">
                <div>
                    <h4 class="font-bold text-lg">Butuh Template Dokumen?</h4>
                    <p class="text-xs text-green-100 opacity-80 mt-1">Unduh format proposal dan skripsi terbaru di sini.</p>
                </div>
                <a href="{{ route('mahasiswa.template.index') }}"
                    class="px-5 py-2.5 bg-white text-green-600 rounded-xl font-bold text-xs uppercase tracking-tight hover:bg-green-50 transition-colors">
                    Buka Folder
                </a>
            </div>

        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Pengumuman</h3>
                    <a href="{{ route('pengumuman.index') }}"
                        class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Semua</a>
                </div>

                <div class="p-4 flex-1">
                    @forelse($pengumumans as $p)
                        <a href="{{ route('pengumuman.show', $p->id) }}" class="block mb-4 last:mb-0 group">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</p>
                            <h4
                                class="text-sm font-bold text-gray-800 group-hover:text-green-600 transition-colors line-clamp-2 leading-snug">
                                {{ $p->nomor_surat }}
                            </h4>
                        </a>
                    @empty
                        <div class="text-center py-10">
                            <i class="fas fa-bullhorn text-gray-200 text-3xl mb-2"></i>
                            <p class="text-xs text-gray-400">Belum ada info.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
