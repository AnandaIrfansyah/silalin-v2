<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand text-center py-3">
            <img src="{{ asset('img/logo/dishubpng.png') }}" alt="Logo" class="img-fluid" style="max-width: 70px;">
            <div style="margin-top: 0px; font-weight: bold; font-size: 16px;">Sistem Informasi Lalu Lintas
            </div>
        </div>
        <div class="sidebar-brand sidebar-brand-sm text-center">
            <img src="{{ asset(path: 'img/logo/dishubpng.png') }}" alt="" style="max-width: 50px;">
        </div>
        <hr>

        <ul class="sidebar-menu">

            {{-- ════════════════════════════════════════════
                 PEMOHON
            ════════════════════════════════════════════ --}}
            @role('pemohon')
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
                <li class="nav-item {{ request()->routeIs('pemohon.permohonan.*') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.permohonan.index') }}" class="nav-link">
                        <i class="fas fa-file-circle-plus"></i>
                        <span>Ajukan Permohonan</span>
                    </a>
                </li>
                {{-- <li class="nav-item {{ request()->routeIs('pemohon.permohonan.index') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.permohonan.index') }}" class="nav-link">
                        <i class="fas fa-folder-open"></i>
                        <span>Permohonan Saya</span>
                    </a>
                </li>

                <li class="menu-header">Hasil</li>
                <li class="nav-item {{ request()->routeIs('pemohon.hasil.*') ? 'active' : '' }}">
                    <a href="{{ route('pemohon.hasil.index') }}" class="nav-link">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Hasil Evaluasi</span>
                    </a>
                </li> --}}
            @endrole

            {{-- ════════════════════════════════════════════
                 EVALUATOR
            ════════════════════════════════════════════ --}}
            @role('evaluator')
                <li class="menu-header">Dashboard</li>
                <li class="nav-item {{ request()->routeIs('evaluator.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.dashboard.index') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Master Data</li>
                <li
                    class="nav-item dropdown {{ request()->routeIs(['evaluator.ruas-jalan.*', 'evaluator.trip-rate.*', 'evaluator.standar-srp.*']) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-database"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('evaluator.ruas-jalan.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('evaluator.ruas-jalan.index') }}">
                                <span>Ruas Jalan</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('evaluator.trip-rate.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('evaluator.trip-rate.index') }}">
                                <span>Trip Rate</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('evaluator.standar-srp.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('evaluator.standar-srp.index') }}">
                                <span>Standar SRP</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('evaluator.master-evaluasi.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('evaluator.master-evaluasi.index') }}">
                                <span>LOS & Parkir</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-header">Fuzzy-AHP</li>
                <li class="nav-item {{ request()->routeIs('evaluator.kuesioner.*') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.kuesioner.index') }}" class="nav-link">
                        <i class="fas fa-file-signature"></i>
                        <span>Kuesioner Pakar</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('evaluator.bobot-kriteria.*') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.bobot-kriteria.index') }}" class="nav-link">
                        <i class="fas fa-scale-balanced"></i>
                        <span>Bobot Kriteria</span>
                    </a>
                </li>

                <li class="menu-header">Verifikasi</li>
                <li class="nav-item {{ request()->routeIs('evaluator.verifikasi-perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.verifikasi-perusahaan.index') }}" class="nav-link">
                        <i class="fas fa-building"></i>
                        <span>Perusahaan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('evaluator.permohonan.*') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.permohonan.index') }}" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Dokumen</span>
                    </a>
                </li>

                <li class="menu-header">Evaluasi Kelayakan</li>
                <li class="nav-item {{ request()->routeIs('evaluator.perangkingan.*') ? 'active' : '' }}">
                    <a href="{{ route('evaluator.perangkingan.index') }}" class="nav-link">
                        <i class="fas fa-ranking-star"></i>
                        <span>Perangkingan</span>
                    </a>
                </li>
            @endrole

            {{-- ════════════════════════════════════════════
                 ADMIN (KEPALA BIDANG)
            ════════════════════════════════════════════ --}}
            @role('kepala_bidang')
                <li class="menu-header">Dashboard</li>
                <li class="nav-item {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Evaluasi Kelayakan</li>
                <li class="nav-item {{ request()->routeIs('admin.bobot-kriteria.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bobot-kriteria.index') }}" class="nav-link">
                        <i class="fas fa-scale-balanced"></i>
                        <span>Review Bobot Kriteria</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.perangkingan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.perangkingan.index') }}" class="nav-link">
                        <i class="fas fa-ranking-star"></i>
                        <span>Hasil Perangkingan</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.keputusan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.keputusan.index') }}" class="nav-link">
                        <i class="fas fa-stamp"></i>
                        <span>Keputusan Akhir</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.surat-pernyataan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.surat-pernyataan.index') }}" class="nav-link">
                        <i class="fas fa-file-signature"></i>
                        <span>Surat Pernyataan</span>
                    </a>
                </li>

                <li class="menu-header">Arsip</li>
                <li class="nav-item {{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.arsip.index') }}" class="nav-link">
                        <i class="fas fa-archive"></i>
                        <span>Arsip Permohonan</span>
                    </a>
                </li>

                <li class="menu-header">Laporan</li>
                <li class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.laporan.index') }}" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan</span>
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
