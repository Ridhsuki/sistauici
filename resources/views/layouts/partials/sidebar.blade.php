<aside id="sidebar"
    class="fixed inset-y-0 left-0 transform bg-white border-r border-gray-200 w-64 transition-transform duration-300 z-40 -translate-x-full lg:translate-x-0">
    <div class="flex flex-col h-full">
        <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-200">
            <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="flex items-center gap-2">
                <img src="https://sia.uici.ac.id/images/uici/logo-uici-baru.png" class="w-10 h-10" alt="Logo SIA" />
                <span class="text-lg font-normal text-gray-900">Sistem Informasi Tugas Akhir & Skripsi</span>
            </a>
            <button onclick="toggleSidebar()" id="sidebar-toggle-button"
                class="lg:hidden p-2 rounded-full text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 ml-auto">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>

        <nav class="flex flex-col px-5 py-6 space-y-1 text-sm text-gray-600 overflow-y-auto">
            <span class="uppercase text-xs font-semibold mb-2 text-gray-400">Navigasi</span>

            <a href="{{ route(Auth::user()->role . '.dashboard') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('*.dashboard')
                    ? 'bg-green-600 text-white font-semibold'
                    : 'hover:bg-gray-100 text-gray-600' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>

            {{-- ================= ADMIN ================= --}}
            @if (auth()->user()->role == 'admin')
                <div x-data="{
                    open: localStorage.getItem('sidebar_admin_users') === 'true' ||
                        (localStorage.getItem('sidebar_admin_users') === null && {{ request()->routeIs('admin.management.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_admin_users', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.management.*') ? 'text-white bg-green-600' : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('admin.management.admin.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.management.admin.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin</span>
                        </a>
                        <a href="{{ route('admin.management.mahasiswa.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.management.mahasiswa.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user-graduate"></i>
                            <span>Mahasiswa</span>
                        </a>
                        <a href="{{ route('admin.management.dosen.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.management.dosen.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user-tie"></i>
                            <span>Dosen Pembimbing</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.jadwal.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('admin.jadwal.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadwal Sidang</span>
                </a>
                <div x-data="{
                    open: localStorage.getItem('sidebar_admin_documents') === 'true' ||
                        (localStorage.getItem('sidebar_admin_documents') === null && {{ request()->routeIs('admin.proposal.*') || request()->routeIs('admin.dokumen-akhir.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_admin_documents', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300
{{ request()->routeIs('admin.proposal.*') || request()->routeIs('admin.dokumen-akhir.*')
    ? 'text-white bg-green-600'
    : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Dokumen</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('admin.proposal.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.proposal.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-file-signature"></i>
                            <span>Proposal</span>
                        </a>
                        <a href="{{ route('admin.dokumen-akhir.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('admin.dokumen-akhir.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-file-contract"></i>
                            <span>Dokumen akhir</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.template.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('admin.template.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-file-word"></i>
                    <span>Template Skripsi</span>
                </a>
                <a href="{{ route('admin.pengumuman.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('admin.pengumuman.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Pengumuman</span>
                </a>

                {{-- ================= DOSEN ================= --}}
            @elseif (auth()->user()->role == 'dosen')
                <div x-data="{
                    open: localStorage.getItem('sidebar_dosen_bimbingan') === 'true' ||
                        (localStorage.getItem('sidebar_dosen_bimbingan') === null && {{ request()->routeIs('dosen.bimbingan.*') || request()->routeIs('dosen.jadwalbimbingan.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_dosen_bimbingan', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.bimbingan.*') || request()->routeIs('dosen.jadwalbimbingan.*') ? 'text-white bg-green-600' : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Bimbingan</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('dosen.jadwalbimbingan.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.jadwalbimbingan.index') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Jadwal Bimbingan</span>
                        </a>
                        <a href="{{ route('dosen.bimbingan.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.bimbingan.index') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user-graduate"></i>
                            <span>Mahasiswa Bimbingan</span>
                        </a>
                        <a href="{{ route('dosen.bimbingan.history') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.bimbingan.history') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Bimbingan</span>
                        </a>
                    </div>
                </div>

                <div x-data="{
                    open: localStorage.getItem('sidebar_dosen_review') === 'true' ||
                        (localStorage.getItem('sidebar_dosen_review') === null && {{ request()->routeIs('dosen.proposals.*') || request()->routeIs('dosen.dokumen-akhir.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_dosen_review', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.proposals.*') || request()->routeIs('dosen.dokumen-akhir.*') ? 'text-white bg-green-600' : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Review</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('dosen.proposals.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.proposals.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-tasks"></i>
                            <span>Proposal/Skripsi</span>
                        </a>
                        <a href="{{ route('dosen.dokumen-akhir.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.dokumen-akhir.*') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Dokumen Akhir</span>
                        </a>
                    </div>
                </div>

                <div x-data="{
                    open: localStorage.getItem('sidebar_dosen_nilai') === 'true' ||
                        (localStorage.getItem('sidebar_dosen_nilai') === null && {{ request()->routeIs('dosen.nilai-proposal.*') || request()->routeIs('dosen.nilai-dokumen-akhir.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_dosen_nilai', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.nilai-proposal.*') || request()->routeIs('dosen.nilai-dokumen-akhir.*') ? 'text-white bg-green-600' : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-book"></i>
                        <span>Nilai Mahasiswa</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('dosen.nilai-proposal.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.nilai-proposal.index') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-clipboard"></i>
                            <span>Proposal</span>
                        </a>
                        <a href="{{ route('dosen.nilai-dokumen-akhir.index') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.nilai-dokumen-akhir.index') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Dokumen Akhir</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('dosen.laporan-progress.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('dosen.laporan-progress.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan Progres</span>
                </a>

                {{-- ================= MAHASISWA ================= --}}
            @elseif (auth()->user()->role == 'mahasiswa')
                <div x-data="{
                    open: localStorage.getItem('sidebar_dosen_bimbingan') === 'true' ||
                        (localStorage.getItem('sidebar_dosen_bimbingan') === null && {{ request()->routeIs('dosen.bimbingan.*') || request()->routeIs('dosen.jadwalbimbingan.*') ? 'true' : 'false' }})
                }" x-init="$watch('open', val => localStorage.setItem('sidebar_dosen_bimbingan', val))" class="space-y-1">

                    <button type="button" @click="open = !open"
                        class="flex items-center w-full gap-3 px-4 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('dosen.bimbingan.*') || request()->routeIs('dosen.jadwalbimbingan.*') ? 'text-white bg-green-600' : 'hover:bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Bimbingan</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-collapse.duration.300ms class="space-y-1">
                        <a href="{{ route('mahasiswa.jadwal-seminar') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('mahasiswa.jadwal-seminar') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Jadwal Seminar & Sidang</span>
                        </a>
                        <a href="{{ route('mahasiswa.bimbingan.history') }}"
                            class="flex items-center gap-3 px-8 py-2 rounded-full transition-all duration-300 {{ request()->routeIs('mahasiswa.bimbingan.history') ? 'bg-green-500 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Bimbingan</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('mahasiswa.proposals.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('mahasiswa.proposals.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Proposal & Skripsi</span>
                </a>
                <a href="{{ route('mahasiswa.dokumen-akhir.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('mahasiswa.dokumen-akhir.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-file-upload"></i>
                    <span>Upload Dokumen Akhir</span>
                </a>
                <a href="{{ route('mahasiswa.nilai.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('mahasiswa.nilai.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-book"></i>
                    <span>Arsip & Nilai</span>
                </a>
                <a href="{{ route('mahasiswa.template.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('mahasiswa.template.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-file-word"></i>
                    <span>Download Template</span>
                </a>
                <a href="{{ route('pengumuman.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('pengumuman.*') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Pengumuman</span>
                </a>
            @endif

            <div class="border-t border-gray-200 my-2"></div>
            <a href="{{ route('bantuan') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('bantuan') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                <i class="fas fa-question-circle"></i>
                <span>Bantuan</span>
            </a>
            <a href="{{ route('tentang') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-full {{ request()->routeIs('tentang') ? 'bg-green-600 text-white font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
                <i class="fas fa-info-circle"></i>
                <span>Tentang</span>
            </a>
            <div class="border-t border-gray-200 my-2"></div>
            <a href="#" onclick="event.preventDefault(); openLogoutModal()"
                class="flex items-center gap-3 px-4 py-2 rounded-full hover:bg-red-100 text-red-600">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>
</aside>

<aside id="notification-sidebar"
    class="fixed inset-y-0 right-0 transform translate-x-full bg-white border-l border-gray-200 w-80 transition-transform duration-300 z-40">
    <div class="flex flex-col h-full">
        <div class="px-6 py-4 border-b border-gray-100 bg-white flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">Notifikasi</h2>
            </div>

            <div class="flex items-center gap-3">
                @if (auth()->user()->notifications->count() > 0)
                    <form action="{{ route('notifications.clearAll') }}" method="POST" class="delete-form"
                        data-message="Semua riwayat notifikasi akan dihapus permanen.">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="group flex items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all duration-200"
                            title="Hapus Semua Notifikasi">
                            <i class="fas fa-trash-alt text-[10px] group-hover:scale-110 transition-transform"></i>
                            <span>Bersihkan</span>
                        </button>
                    </form>

                    <div class="h-4 w-px bg-gray-200"></div>
                @endif

                <button onclick="toggleNotificationSidebar()"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50"
                    title="Tutup Panel">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="p-4 overflow-y-auto">
            <div class="space-y-4 text-sm text-gray-600">
                @php
                    $notifications = getCachedNotifications();
                @endphp

                @if (count($notifications) > 0)
                    @foreach ($notifications as $notif)
                        @php
                            $colorClasses = match ($notif['type']) {
                                'warning' => 'bg-yellow-50 text-yellow-800',
                                'info' => 'bg-blue-50 text-blue-800',
                                'success' => 'bg-green-50 text-green-800',
                                default => 'bg-gray-50 text-gray-800',
                            };

                            $readClass = $notif['is_read'] ? '' : 'border-l-4 border-blue-500';
                        @endphp

                        <a href="{{ is_numeric($notif['id']) ? route('notifications.markAsRead', $notif['id']) : $notif['link'] }}"
                            class="block p-3 rounded-lg shadow-sm {{ $colorClasses }} {{ $readClass }}">
                            <p class="font-bold">{{ $notif['title'] }}</p>
                            <p class="font-semibold text-xs">{{ $notif['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Klik untuk detail</p>
                        </a>
                    @endforeach
                @else
                    <p class="text-center text-gray-500">Tidak ada notifikasi</p>
                @endif
            </div>
        </div>
    </div>
</aside>
