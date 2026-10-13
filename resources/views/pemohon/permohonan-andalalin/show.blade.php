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
                    <div class="breadcrumb-item"><a href="{{ route('pemohon.permohonan-andalalin.index') }}">Permohonan
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
                                <h4 class="mb-0">{{ $permohonan->jenis_kegiatan_snapshot }}</h4>
                                <span
                                    class="badge badge-info p-2">{{ str_replace('_', ' ', ucfirst($permohonan->status)) }}</span>
                            </div>
                            <div class="card-body">
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

                                {{-- Revisi Berkas --}}
                                @if ($permohonan->status === 'revisi_berkas')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <strong>Berkas Anda perlu direvisi.</strong><br>
                                        Catatan Admin: {{ $permohonan->catatan_verifikasi_berkas }}
                                    </div>
                                    <form
                                        action="{{ route('pemohon.permohonan-andalalin.revisi-berkas', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <p class="text-muted small">Upload ulang dokumen yang perlu direvisi (biarkan kosong
                                            jika dokumen lain sudah benar).</p>
                                        <div class="row">
                                            @foreach ($dokumenWajib as $dok)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ $dok->nama_dokumen }}</label>
                                                        <input type="file" name="dokumen[{{ $dok->kode }}]"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Upload Ulang & Ajukan Kembali
                                        </button>
                                    </form>

                                    {{-- Menunggu Verifikasi Umum --}}
                                @elseif (in_array($permohonan->status, ['diajukan', 'dijadwalkan_survey', 'survey_selesai', 'dijadwalkan_paparan']))
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-hourglass-half mr-1"></i> Permohonan sedang diproses oleh Admin.
                                        Silakan cek riwayat di samping untuk perkembangan terbaru.
                                    </div>

                                    {{-- Upload Dokumen Andalalin --}}
                                @elseif ($permohonan->status === 'menunggu_dokumen_andalalin')
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Kategori bangkitan proyek Anda ({{ ucfirst($permohonan->kategori_bangkitan) }})
                                        mewajibkan Dokumen Andalalin dari konsultan.
                                    </div>
                                    <form
                                        action="{{ route('pemohon.permohonan-andalalin.upload-dokumen-andalalin', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Upload Dokumen Andalalin (PDF) <span class="text-danger">*</span></label>
                                            <input type="file" name="file_dokumen_andalalin" class="form-control"
                                                accept=".pdf" required>
                                            <small class="text-muted">Maksimal 10MB</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Upload Dokumen
                                        </button>
                                    </form>
                                @elseif ($permohonan->status === 'revisi_dokumen_andalalin')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <strong>Dokumen Andalalin Anda perlu direvisi.</strong><br>
                                        Catatan Admin: {{ $permohonan->catatan_verifikasi_dokumen_andalalin }}
                                    </div>
                                    <form
                                        action="{{ route('pemohon.permohonan-andalalin.upload-dokumen-andalalin', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Upload Ulang Dokumen Andalalin (PDF) <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="file_dokumen_andalalin" class="form-control"
                                                accept=".pdf" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Upload Ulang
                                        </button>
                                    </form>

                                    {{-- Menunggu / Revisi TTD --}}
                                @elseif (in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd']))
                                    @if ($permohonan->status === 'revisi_ttd')
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <strong>Surat Pernyataan Anda perlu diupload ulang.</strong><br>
                                            Catatan Admin: {{ $permohonan->catatan_verifikasi_ttd }}
                                        </div>
                                    @endif

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Silakan unduh Surat Pernyataan Kesanggupan, tanda tangani, lalu upload hasil
                                        scan-nya.
                                    </div>

                                    <a href="{{ route('pemohon.permohonan-andalalin.download-surat-pernyataan', $permohonan->id) }}"
                                        target="_blank" class="btn btn-outline-primary mb-3">
                                        <i class="fas fa-download mr-1"></i> Unduh Surat Pernyataan
                                    </a>

                                    <form action="{{ route('pemohon.permohonan-andalalin.upload-ttd', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Upload Surat yang Sudah Ditandatangani <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="file_surat_pernyataan_ttd" class="form-control"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Upload Surat
                                        </button>
                                    </form>

                                    {{-- Selesai --}}
                                @elseif ($permohonan->status === 'selesai')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Selamat, Rekomendasi Andalalin Anda sudah terbit pada
                                        {{ $permohonan->diterbitkan_at->format('d M Y') }}.
                                        <br>
                                        <a href="{{ asset('storage/' . $permohonan->file_surat_rekomendasi) }}"
                                            target="_blank" class="btn btn-success btn-sm mt-2">
                                            <i class="fas fa-download mr-1"></i> Unduh Surat Rekomendasi
                                        </a>
                                    </div>

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
                                        {{ str_replace('_', ' ', $log->aksi) }}
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
