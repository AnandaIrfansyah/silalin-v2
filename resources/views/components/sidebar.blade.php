<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand text-center py-3">
            <img src="{{ asset('img/logo/dishubpng.png') }}" alt="Logo" class="img-fluid" style="max-width: 70px;">
            <div style="margin-top: 0px; font-weight: bold; font-size: 16px;">Sistem Informasi Lalu Lintas
            </div>
        </div>
        <div class="sidebar-brand sidebar-brand-sm text-center">
            <img src="{{ asset('img/logo/dishubpng.png') }}" alt="" style="max-width: 50px;">
        </div>
        <hr>

        <ul class="sidebar-menu">

            {{-- ════════════════════════════════════════════
                 PEMOHON
            ════════════════════════════════════════════ --}}
            @role('Pemohon')
                <li class="menu-header">Dashboard</li>
                <li class="nav-item {{ request()->routeIs('pemohon.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.dashboard.index') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-header">Perusahaan</li>
                <li class="nav-item {{ request()->routeIs('pemohon.company.*') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.company.index') }}" class="nav-link">
                        <i class="fas fa-building"></i>
                        <span>Profil Perusahaan</span>
                    </a>
                </li>

                <li class="menu-header">Permohonan</li>
                <li class="nav-item {{ request()->routeIs('pemohon.permohonan-andalalin.*') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.permohonan-andalalin.index') }}" class="nav-link">
                        <i class="fas fa-file-circle-plus"></i>
                        <span>Permohonan Andalalin</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('pemohon.permohonan-parkir.*') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.permohonan-parkir.index') }}" class="nav-link">
                        <i class="fas fa-square-parking"></i>
                        <span>Permohonan Parkir</span>
                    </a>
                </li>
            @endrole

            {{-- ════════════════════════════════════════════
                 ADMIN
            ════════════════════════════════════════════ --}}
            @role('Admin')
                <li class="menu-header">Dashboard</li>
                <li class="nav-item {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-header">Verifikasi</li>
                <li class="nav-item {{ request()->routeIs('admin.verifikasi-perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.verifikasi-perusahaan.index') }}" class="nav-link">
                        <i class="fas fa-building"></i>
                        <span>Verifikasi Perusahaan</span>
                    </a>
                </li>
                <li class="menu-header">Perizinan</li>
                <li class="nav-item {{ request()->routeIs('admin.permohonan-andalalin.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permohonan-andalalin.index') }}" class="nav-link">
                        <i class="fas fa-file-signature"></i>
                        <span>Permohonan Andalalin</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.permohonan-parkir.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permohonan-parkir.index') }}" class="nav-link">
                        <i class="fas fa-square-parking"></i>
                        <span>Permohonan Parkir</span>
                    </a>
                </li>
                <li class="menu-header">Master Data</li>
                <li class="nav-item {{ request()->routeIs('admin.kriteria-andalalin.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kriteria-andalalin.index') }}" class="nav-link">
                        <i class="fas fa-list-check"></i>
                        <span>Kriteria Andalalin</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.master-fasilitas.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.master-fasilitas.index') }}" class="nav-link">
                        <i class="fas fa-toolbox"></i>
                        <span>Master Fasilitas</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.master-dokumen.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.master-dokumen.index') }}" class="nav-link">
                        <i class="fas fa-file-circle-check"></i>
                        <span>Dokumen Persyaratan</span>
                    </a>
                </li>
                <li class="menu-header">Manajemen</li>
                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="fas fa-users-gear"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
            @endrole

        </ul>
    </aside>
</div>
