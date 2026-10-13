@extends('layouts.app')
@section('title', 'Detail Permohonan Andalalin')

@push('style')
    <style>
        .info-row {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px dashed #e3e8f1;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 200px;
            color: #6c757d;
            flex-shrink: 0;
        }

        .info-value {
            font-weight: 500;
        }

        .timeline-item {
            border-left: 2px solid #e3e8f1;
            padding: 0 0 16px 16px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #6777ef;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border: 1px solid #e3e8f1;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 8px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Permohonan Andalalin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.permohonan-andalalin.index') }}">Permohonan
                            Andalalin</a></div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        {{-- Data Permohonan --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    {{ $permohonan->company->bentuk_badan_usaha }}.
                                    &nbsp;{{ $permohonan->company->nama_perusahaan }}
                                </h4>
                                <span
                                    class="badge badge-info p-2">{{ str_replace('_', ' ', ucfirst($permohonan->status)) }}</span>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">Jenis Kegiatan</div>
                                    <div class="info-value">{{ $permohonan->jenis_kegiatan_snapshot }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Parameter</div>
                                    <div class="info-value">{{ $permohonan->parameter_snapshot }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Nilai Parameter</div>
                                    <div class="info-value">{{ $permohonan->nilai_parameter_snapshot }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Kategori Bangkitan</div>
                                    <div class="info-value">
                                        @php $kBadge = ['rendah' => 'success', 'sedang' => 'warning', 'tinggi' => 'danger'][$permohonan->kategori_bangkitan]; @endphp
                                        <span
                                            class="badge badge-{{ $kBadge }}">{{ ucfirst($permohonan->kategori_bangkitan) }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Alamat Proyek</div>
                                    <div class="info-value">{{ $permohonan->alamat_proyek }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Pemohon</div>
                                    <div class="info-value">{{ $permohonan->user->name }} ({{ $permohonan->user->email }})
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dokumen Persyaratan --}}
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Dokumen Persyaratan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @forelse ($permohonan->dokumen as $d)
                                        <div class="col-md-6">
                                            <div class="file-item">
                                                <span><i
                                                        class="fas fa-file-pdf text-danger mr-2"></i>{{ $d->masterDokumen->nama_dokumen }}</span>
                                                <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye mr-1"></i> Lihat File
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted mb-0">Belum ada dokumen.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- PANEL AKSI KONDISIONAL --}}
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Aksi</h4>
                            </div>
                            <div class="card-body">

                                {{-- Tahap: Verifikasi Berkas --}}
                                @if ($permohonan->status === 'diajukan')
                                    <form
                                        action="{{ route('admin.permohonan-andalalin.verifikasi-berkas', $permohonan->id) }}"
                                        method="POST">
                                        @csrf
                                        <p>Periksa dokumen di atas, lalu tentukan apakah berkas sudah sesuai.</p>
                                        <div class="form-group">
                                            <label>Catatan (wajib jika revisi)</label>
                                            <textarea name="catatan" class="form-control" rows="2" placeholder="Alasan revisi, jika ada"></textarea>
                                        </div>
                                        <button type="submit" name="aksi" value="setuju" class="btn btn-success">
                                            <i class="fas fa-check mr-1"></i> Setujui Berkas
                                        </button>
                                        <button type="submit" name="aksi" value="revisi" class="btn btn-danger">
                                            <i class="fas fa-times mr-1"></i> Minta Revisi
                                        </button>
                                    </form>

                                    {{-- Tahap: Revisi Berkas (menunggu pemohon) --}}
                                @elseif ($permohonan->status === 'revisi_berkas')
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Menunggu pemohon mengupload ulang dokumen yang direvisi.
                                        <br><strong>Catatan:</strong> {{ $permohonan->catatan_verifikasi_berkas }}
                                    </div>

                                    {{-- Tahap: Jadwal Survey --}}
                                @elseif ($permohonan->status === 'dijadwalkan_survey')
                                    @if (!$permohonan->tanggal_survey)
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.jadwal-survey', $permohonan->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tanggal Survey</label>
                                                        <input type="date" name="tanggal_survey" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Jam Survey</label>
                                                        <input type="time" name="jam_survey" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-calendar-check mr-1"></i> Simpan Jadwal
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Survey dijadwalkan:
                                            <strong>{{ \Carbon\Carbon::parse($permohonan->tanggal_survey)->format('d M Y') }}
                                                pukul {{ $permohonan->jam_survey }}</strong>
                                        </div>
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.upload-berita-acara', $permohonan->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label>Upload Berita Acara Survey</label>
                                                <input type="file" name="file_berita_acara_survey" class="form-control"
                                                    accept=".pdf" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload mr-1"></i> Upload Berita Acara
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tahap: Menunggu / Revisi Dokumen Andalalin --}}
                                @elseif (in_array($permohonan->status, ['menunggu_dokumen_andalalin', 'revisi_dokumen_andalalin']))
                                    @if ($permohonan->file_dokumen_andalalin)
                                        <div class="file-item">
                                            <span><i class="fas fa-file-pdf text-danger mr-2"></i>Dokumen Andalalin</span>
                                            <a href="{{ asset('storage/' . $permohonan->file_dokumen_andalalin) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye mr-1"></i> Lihat File
                                            </a>
                                        </div>
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.verifikasi-dokumen-andalalin', $permohonan->id) }}"
                                            method="POST" class="mt-3">
                                            @csrf
                                            <div class="form-group">
                                                <label>Catatan (wajib jika revisi)</label>
                                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                                            </div>
                                            <button type="submit" name="aksi" value="setuju"
                                                class="btn btn-success">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                            <button type="submit" name="aksi" value="revisi" class="btn btn-danger">
                                                <i class="fas fa-times mr-1"></i> Minta Revisi
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu pemohon mengupload dokumen
                                            andalalin.
                                        </div>
                                    @endif

                                    {{-- Tahap: Jadwal Paparan & Laporan Analisis --}}
                                @elseif ($permohonan->status === 'dijadwalkan_paparan')
                                    @if (!$permohonan->tanggal_paparan)
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.jadwal-paparan', $permohonan->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tanggal Paparan</label>
                                                        <input type="date" name="tanggal_paparan" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Jam Paparan</label>
                                                        <input type="time" name="jam_paparan" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-calendar-check mr-1"></i> Simpan Jadwal
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Paparan dijadwalkan:
                                            <strong>{{ \Carbon\Carbon::parse($permohonan->tanggal_paparan)->format('d M Y') }}
                                                pukul {{ $permohonan->jam_paparan }}</strong>
                                        </div>
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.upload-laporan', $permohonan->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label>Upload Laporan Analisis</label>
                                                <input type="file" name="file_laporan_analisis" class="form-control"
                                                    accept=".pdf" required>
                                            </div>
                                            <hr>
                                            <p class="text-muted small">Data naskah untuk generate Surat Pernyataan
                                                Kesanggupan:</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Jenis/Sifat Naskah</label>
                                                        <input type="text" name="jenis_sifat_naskah"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nomor Klasifikasi</label>
                                                        <input type="text" name="nomor_klasifikasi"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Kode Bidang</label>
                                                        <input type="text" name="kode_bidang" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nomor Urut Dokumen</label>
                                                        <input type="text" name="nomor_urut_dokumen"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tanggal Dokumen</label>
                                                        <input type="date" name="tanggal_dokumen" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload mr-1"></i> Upload & Generate Surat Pernyataan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tahap: Menunggu / Revisi TTD --}}
                                @elseif (in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd']))
                                    @if ($permohonan->file_surat_pernyataan_ttd)
                                        <div class="file-item">
                                            <span><i class="fas fa-file-signature text-primary mr-2"></i>Surat Pernyataan
                                                (Sudah TTD)</span>
                                            <a href="{{ asset('storage/' . $permohonan->file_surat_pernyataan_ttd) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye mr-1"></i> Lihat File
                                            </a>
                                        </div>
                                        <form
                                            action="{{ route('admin.permohonan-andalalin.verifikasi-ttd', $permohonan->id) }}"
                                            method="POST" enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <div class="form-group">
                                                <label>Upload Surat Rekomendasi Final (jika disetujui)</label>
                                                <input type="file" name="file_surat_rekomendasi" class="form-control"
                                                    accept=".pdf">
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan (wajib jika revisi)</label>
                                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                                            </div>
                                            <button type="submit" name="aksi" value="setuju"
                                                class="btn btn-success">
                                                <i class="fas fa-check mr-1"></i> Setujui & Terbitkan Rekomendasi
                                            </button>
                                            <button type="submit" name="aksi" value="revisi" class="btn btn-danger">
                                                <i class="fas fa-times mr-1"></i> Minta Revisi
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu pemohon mengunduh,
                                            menandatangani, dan mengupload surat pernyataan.
                                        </div>
                                    @endif

                                    {{-- Tahap: Selesai --}}
                                @elseif ($permohonan->status === 'selesai')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Rekomendasi Andalalin sudah terbit pada
                                        {{ $permohonan->diterbitkan_at->format('d M Y H:i') }}.
                                        <a href="{{ asset('storage/' . $permohonan->file_surat_rekomendasi) }}"
                                            target="_blank" class="ml-2">Lihat Surat</a>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Tidak ada aksi untuk status ini.</p>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- Timeline Riwayat --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Riwayat Proses</h4>
                            </div>
                            <div class="card-body">
                                @forelse ($permohonan->logs as $log)
                                    <div class="timeline-item">
                                        <small class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</small><br>
                                        <strong>{{ $log->user->name }}</strong>: {{ str_replace('_', ' ', $log->aksi) }}
                                        @if ($log->keterangan)
                                            <br><em class="small">{{ $log->keterangan }}</em>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Belum ada riwayat.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        @if (session('toast_success'))
            Toast.fire({
                icon: 'success',
                title: @json(session('toast_success'))
            });
        @endif
        @if (session('toast_error'))
            Toast.fire({
                icon: 'error',
                title: @json(session('toast_error'))
            });
        @endif
    </script>
@endpush
