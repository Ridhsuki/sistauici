@extends('layouts.app')

@section('title', 'Jadwal Bimbingan')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
            <p class="text-gray-500 text-sm mt-1">Halaman untuk melihat daftar bimbingan mahasiswa yang terdaftar pada Anda.
            </p>
        </div>
        <nav class="text-sm font-medium text-gray-500 bg-gray-100 px-4 py-2 rounded-lg">
            <ol class="list-reset flex items-center gap-2">
                <li><a href="{{ route('dosen.dashboard') }}" class="hover:text-green-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-green-600">@yield('title')</li>
            </ol>
        </nav>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-blue-800">Informasi</h3>
                <div class="mt-2 text-sm leading-5 text-blue-700 hidden md:block">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Hadir tepat waktu. Klik tombol <strong>Join Meet</strong> jika bimbingan Online.</li>
                        <li>Jadwal di bawah adalah bimbingan yang telah <strong>Approved</strong>.</li>
                    </ul>
                </div>
                <p class="mt-1 text-sm text-blue-600 md:hidden">
                    Jadwal di bawah adalah bimbingan yang telah <strong>Approved</strong>.
                </p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
                Jadwal Approved
            </h2>
            <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-green-100 text-green-800">
                Total: {{ $bimbingans->count() }} Sesi
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-green-50/50 border-b border-green-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-green-800">Waktu Pelaksanaan</th>
                        <th class="px-6 py-4 font-bold text-green-800">Mahasiswa</th>
                        <th class="px-6 py-4 font-bold text-green-800">Topik</th>
                        <th class="px-6 py-4 font-bold text-center text-green-800">Lokasi/Link</th>
                        <th class="px-6 py-4 font-bold text-center text-green-800">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($bimbingans as $bimbingan)
                        <tr class="bg-white hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('l, d F Y') }}</span>
                                    <span
                                        class="text-green-600 text-xs font-medium">{{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }}
                                        - {{ \Carbon\Carbon::parse($bimbingan->waktu_selesai)->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900">{{ $bimbingan->mahasiswa->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $bimbingan->mahasiswa->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-900 truncate max-w-[200px]">
                                    {{ $bimbingan->keterangan ?? 'Bimbingan Rutin' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if (!empty($bimbingan->link_meet))
                                    <a href="{{ $bimbingan->link_meet }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-full hover:bg-blue-700 transition">
                                        Join Meet
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500 italic">Offline</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 rounded-full border border-green-200 uppercase">Approved</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                                    </svg>

                                    <h3 class="text-lg font-semibold text-gray-700 mb-1">
                                        Belum Ada Jadwal Bimbingan
                                    </h3>

                                    <p class="text-sm text-gray-500 mb-4 max-w-md">
                                        Saat ini belum terdapat jadwal bimbingan yang telah disetujui.
                                        Silakan cek halaman permintaan untuk melihat pengajuan terbaru.
                                    </p>

                                    <a href="{{ route('dosen.bimbingan.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm">
                                        Cek Permintaan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse ($bimbingans as $bimbingan)
            @php
                $tanggal = \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan);
                $isToday = $tanggal->isToday();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 w-full {{ $isToday ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                <div class="p-4 flex gap-4">
                    <div
                        class="flex flex-col items-center justify-center bg-gray-50 border border-gray-200 rounded-lg w-14 h-14 min-w-[3.5rem]">
                        <span
                            class="text-[10px] font-bold text-red-500 uppercase">{{ $tanggal->translatedFormat('M') }}</span>
                        <span class="text-xl font-bold text-gray-800">{{ $tanggal->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-600 italic">
                                {{ \Carbon\Carbon::parse($bimbingan->waktu_mulai)->format('H:i') }} WIB
                            </span>
                            @if ($isToday)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">HARI
                                    INI</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 truncate">{{ $bimbingan->mahasiswa->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $bimbingan->keterangan ?? 'Bimbingan Rutin' }}</p>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    @if (!empty($bimbingan->link_meet))
                        <a href="{{ $bimbingan->link_meet }}"
                            class="text-xs font-bold text-blue-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                            Link Meet
                        </a>
                    @else
                        <span class="text-xs text-gray-400 italic">Offline / Kampus</span>
                    @endif
                    <span class="text-[10px] font-bold text-green-600 uppercase">Approved</span>
                </div>
            </div>
        @empty
            <div class="bg-white p-10 rounded-xl border-2 border-dashed text-center">
                <p class="text-gray-500">Tidak ada jadwal bimbingan.</p>
                <a href="{{ route('dosen.bimbingan.index') }}"
                    class="text-green-600 text-sm font-bold mt-2 inline-block">Cek Permintaan</a>
            </div>
        @endforelse
    </div>
@endsection
