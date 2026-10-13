@extends('layouts.app')
@section('title', 'Detail Permohonan Parkir')

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
                <h1>Detail Permohonan Parkir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('pemohon.permohonan-parkir.index') }}">Permohonan
                            Parkir</a></div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $permohonan->nama_lokasi }}</h4>
                                <span
                                    class="badge badge-{{ $permohonan->status_badge }} p-2">{{ $permohonan->status_label }}</span>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">Jenis Parkir</div>
                                    <div class="info-value">{{ ucfirst($permohonan->jenis_parkir) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Luas Area</div>
                                    <div class="info-value">{{ number_format($permohonan->luas_area, 2, ',', '.') }} m²
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Alamat Lokasi</div>
                                    <div class="info-value">{{ $permohonan->alamat_lokasi }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Fasilitas Parkir</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Fasilitas</th>
                                                <th class="text-center">Input Anda</th>
                                                <th class="text-center">Hasil Survey</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permohonan->fasilitas as $f)
                                                <tr>
                                                    <td>{{ $f->nama_fasilitas_snapshot }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ $f->status === 'ada' ? 'success' : 'secondary' }}">{{ ucfirst($f->status) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($f->status_hasil_survey)
                                                            <span
                                                                class="badge badge-{{ $f->status_hasil_survey === 'ada' ? 'success' : 'secondary' }}">{{ ucfirst($f->status_hasil_survey) }}</span>
                                                            @if ($f->status_hasil_survey !== $f->status)
                                                                <small class="text-warning d-block">Dikoreksi</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

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
                                                        class="fas fa-file-pdf text-danger mr-2"></i>{{ $d->nama_dokumen_snapshot }}</span>
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

                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Aksi</h4>
                            </div>
                            <div class="card-body">

                                @if ($permohonan->status === 'revisi_berkas')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <strong>Berkas Anda perlu direvisi.</strong><br>
                                        Catatan Admin: {{ $permohonan->catatan_verifikasi_berkas }}
                                    </div>
                                    <form action="{{ route('pemohon.permohonan-parkir.revisi-berkas', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <p class="text-muted small">Upload ulang dokumen yang perlu direvisi, kosongkan
                                            dokumen yang sudah benar.</p>
                                        <div class="row">
                                            @foreach ($dokumenAktif as $dok)
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
                                @elseif (in_array($permohonan->status, ['diajukan', 'dijadwalkan_survey', 'survey_selesai']))
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Permohonan sedang diproses oleh Admin. Silakan cek riwayat di samping untuk
                                        perkembangan terbaru.
                                        @if ($permohonan->status === 'dijadwalkan_survey' && $permohonan->tanggal_survey)
                                            <hr>
                                            <i class="fas fa-calendar mr-1"></i>
                                            Survey lapangan: <strong>{{ $permohonan->tanggal_survey->format('d M Y') }}
                                                pukul {{ substr($permohonan->jam_survey, 0, 5) }}</strong>
                                        @endif
                                    </div>
                                @elseif (in_array($permohonan->status, ['menunggu_ttd', 'revisi_ttd']))
                                    @if ($permohonan->status === 'menunggu_ttd' && $permohonan->file_surat_pernyataan_ttd)
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Surat sudah diupload dan menunggu verifikasi Admin. Anda masih bisa mengupload
                                            ulang jika ada kesalahan.
                                        </div>
                                    @endif
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

                                    <a href="{{ route('pemohon.permohonan-parkir.download-surat-pernyataan', $permohonan->id) }}"
                                        class="btn btn-outline-primary mb-3">
                                        <i class="fas fa-download mr-1"></i> Unduh Surat Pernyataan
                                    </a>

                                    <form action="{{ route('pemohon.permohonan-parkir.upload-ttd', $permohonan->id) }}"
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
                                @elseif ($permohonan->status === 'selesai')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Rekomendasi Parkir Anda sudah terbit pada
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
