@extends('layouts.app')

@section('title', 'Manajemen Dosen')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-gray-800 text-xl font-semibold">@yield('title')</h1>
            <p class="text-gray-500 text-sm">Halaman untuk mengelola data dosen, termasuk menambah, mengedit, dan menghapus
                akun dosen.</p>
        </div>
        <nav class="text-sm text-gray-500">
            <ol class="list-reset flex">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-green-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700">Manajemen Dosen</li>
            </ol>
        </nav>
    </div>
    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.management.dosen.index') }}" method="GET" class="w-full md:w-1/2 relative">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau email dosen..."
                    class="block w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors duration-200">

                @if (request('search'))
                    <a href="{{ route('admin.management.dosen.index') }}"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <div class="flex items-center gap-3 w-full md:w-auto">
           <a href="{{ route('admin.management.dosen.create') }}"
            class="px-4 py-2 bg-green-600 text-white rounded-md mb-4 inline-block hover:bg-green-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Dosen
        </a>
        <button x-data @click="$dispatch('open-modal')"
            class="px-4 py-2 bg-blue-600 text-white rounded-md mb-4 inline-block hover:bg-blue-700 transition-colors">
            <i class="fas fa-upload mr-2"></i>Import Data
        </button>
        </div>
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div x-data="{ open: false }" @open-modal.window="open = true" x-cloak x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center p-4 z-50">

            <div @click.away="open = false" x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white p-6 rounded-2xl shadow-2xl max-w-lg w-full transform transition-all">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-2xl font-bold text-gray-800">Import Data Dosen</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.management.dosen.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label for="file" class="block text-gray-700 font-medium mb-2">Pilih File Excel (.xlsx,
                            .xls)</label>
                        <input type="file" name="file" accept=".xlsx,.xls"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors border border-gray-300 rounded-md p-1"
                            required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="open = false"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-md my-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-md my-4">
                {!! session('error') !!}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="table-auto w-full mt-4 border border-gray-200 rounded-lg min-w-[800px]">
                <thead class="bg-green-100 text-gray-700">
                    <tr>
                        <th class="px-2 md:px-4 py-2 border text-left">Foto</th>
                        <th class="px-2 md:px-4 py-2 border text-left">Nama</th>
                        <th class="px-2 md:px-4 py-2 border text-left">Email</th>
                        <th class="px-2 md:px-4 py-2 border text-left">NIDN</th>
                        <th class="px-2 md:px-4 py-2 border text-left">Bidang</th>
                        <th class="px-2 md:px-4 py-2 border text-left">No HP</th>
                        <th class="px-2 md:px-4 py-2 border text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosen as $d)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-2 md:px-4 py-2 border">
                                @if ($d->foto)
                                    <img src="{{ asset('storage/' . $d->foto) }}" alt="Foto {{ $d->name }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('img/defaultpp.jpg') }}" alt="Foto Default" width="50"
                                        class="rounded-full object-cover w-12 h-12">
                                @endif
                            </td>
                            <td class="px-2 md:px-4 py-2 border break-words max-w-[150px]">{{ $d->name }}</td>
                            <td class="px-2 md:px-4 py-2 border break-words max-w-[200px]">{{ $d->email }}</td>
                            <td class="px-2 md:px-4 py-2 border break-words max-w-[150px]">{{ $d->NIDN }}</td>
                            <td class="px-2 md:px-4 py-2 border break-words max-w-[200px]">{{ $d->bidang_keahlian }}</td>
                            <td class="px-2 md:px-4 py-2 border break-words max-w-[150px]">{{ $d->no_hp }}</td>
                            <td class="px-2 md:px-4 py-2 border flex flex-col md:flex-row gap-1 md:gap-2 items-center">
                                <a href="{{ route('admin.management.dosen.edit', $d->id) }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded text-center hover:bg-blue-600 transition-colors">Edit</a>
                                <form action="{{ route('admin.management.dosen.destroy', $d->id) }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-gray-500">Belum ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($dosen->hasPages())
            <div class="mt-4">
                {{ $dosen->links() }}
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
