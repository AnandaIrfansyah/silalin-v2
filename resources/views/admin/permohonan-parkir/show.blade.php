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

        .fasilitas-survey {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('main')
    @php
        $fieldNaskah = [
            ['jenis_sifat_naskah', 'Jenis/Sifat Naskah', 'text', 6],
            ['nomor_klasifikasi', 'Nomor Klasifikasi', 'text', 6],
            ['kode_bidang', 'Kode Bidang', 'text', 4],
            ['nomor_urut_dokumen', 'Nomor Urut Dokumen', 'text', 4],
            ['tanggal_dokumen', 'Tanggal Dokumen', 'date', 4],
        ];
    @endphp

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Permohonan Parkir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('admin.permohonan-parkir.index') }}">Permohonan Parkir</a>
                    </div>
                    <div class="breadcrumb-item">Detail</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">

                        {{-- Data Permohonan --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $permohonan->nama_lokasi }}</h4>
                                <span
                                    class="badge badge-{{ $permohonan->status_badge }} p-2">{{ $permohonan->status_label }}</span>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="info-label">Perusahaan</div>
                                    <div class="info-value">{{ $permohonan->company->nama_perusahaan }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Pemohon</div>
                                    <div class="info-value">{{ $permohonan->user->name }} ({{ $permohonan->user->email }})
                                    </div>
                                </div>
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

                        {{-- Fasilitas --}}
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
                                                <th class="text-center">Input Pemohon</th>
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

                        {{-- Dokumen --}}
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

                        {{-- Panel Aksi --}}
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Aksi</h4>
                            </div>
                            <div class="card-body">

                                {{-- Verifikasi Berkas --}}
                                @if ($permohonan->status === 'diajukan')
                                    <form
                                        action="{{ route('admin.permohonan-parkir.verifikasi-berkas', $permohonan->id) }}"
                                        method="POST">
                                        @csrf
                                        <p>Periksa dokumen di atas, lalu tentukan apakah berkas sudah sesuai.</p>
                                        <div class="form-group">
                                            <label>Catatan <small class="text-muted">(wajib jika revisi)</small></label>
                                            <textarea name="catatan" class="form-control" rows="2" placeholder="Alasan revisi, jika ada">{{ old('catatan') }}</textarea>
                                        </div>
                                        <button type="submit" name="aksi" value="setuju" class="btn btn-success">
                                            <i class="fas fa-check mr-1"></i> Setujui Berkas
                                        </button>
                                        <button type="submit" name="aksi" value="revisi" class="btn btn-danger">
                                            <i class="fas fa-times mr-1"></i> Minta Revisi
                                        </button>
                                    </form>

                                    {{-- Menunggu pemohon merevisi berkas --}}
                                @elseif ($permohonan->status === 'revisi_berkas')
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Menunggu pemohon mengupload ulang dokumen yang direvisi.
                                        <br><strong>Catatan:</strong> {{ $permohonan->catatan_verifikasi_berkas }}
                                    </div>

                                    {{-- Survey: jadwal, berita acara, hasil survey fasilitas --}}
                                @elseif ($permohonan->status === 'dijadwalkan_survey')
                                    <h6>Jadwal Survey</h6>
                                    <form action="{{ route('admin.permohonan-parkir.jadwal-survey', $permohonan->id) }}"
                                        method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="date" name="tanggal_survey" class="form-control"
                                                        value="{{ old('tanggal_survey', $permohonan->tanggal_survey?->format('Y-m-d')) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Jam</label>
                                                    <input type="time" name="jam_survey" class="form-control"
                                                        value="{{ old('jam_survey', $permohonan->jam_survey ? substr($permohonan->jam_survey, 0, 5) : '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-group w-100">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <i class="fas fa-calendar-check mr-1"></i>
                                                        {{ $permohonan->tanggal_survey ? 'Ubah' : 'Simpan' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    @if ($permohonan->tanggal_survey)
                                        <hr>
                                        <h6>Berita Acara & Hasil Survey Fasilitas</h6>
                                        <form
                                            action="{{ route('admin.permohonan-parkir.upload-berita-acara', $permohonan->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label>File Berita Acara Survey <span class="text-danger">*</span></label>
                                                <input type="file" name="file_berita_acara_survey"
                                                    class="form-control" accept=".pdf" required>
                                            </div>

                                            @if ($permohonan->fasilitas->isNotEmpty())
                                                <p class="text-muted small">
                                                    Sesuaikan status tiap fasilitas dengan kondisi di lapangan. Input asli
                                                    pemohon tetap tersimpan.
                                                </p>
                                                <div class="row">
                                                    @foreach ($permohonan->fasilitas as $item)
                                                        @php $nilai = $item->status_hasil_survey ?? $item->status; @endphp
                                                        <div class="col-md-6">
                                                            <div class="fasilitas-survey">
                                                                <span>
                                                                    {{ $item->nama_fasilitas_snapshot }}
                                                                    <small class="text-muted d-block">Input pemohon:
                                                                        {{ ucfirst($item->status) }}</small>
                                                                </span>
                                                                <select name="fasilitas[{{ $item->id }}]"
                                                                    class="form-control" style="width: 130px;">
                                                                    <option value="ada" @selected($nilai === 'ada')>Ada
                                                                    </option>
                                                                    <option value="tidak ada" @selected($nilai === 'tidak ada')>
                                                                        Tidak Ada</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <button type="submit" class="btn btn-primary mt-2">
                                                <i class="fas fa-upload mr-1"></i> Upload Berita Acara & Simpan Hasil
                                                Survey
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Paparan: jadwal, laporan analisis, data naskah --}}
                                    {{-- Laporan analisis & data naskah --}}
                                @elseif ($permohonan->status === 'survey_selesai')
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Survey selesai. Upload laporan analisis dan lengkapi data naskah untuk menyiapkan
                                        Surat Pernyataan Kesanggupan.
                                    </div>
                                    <form action="{{ route('admin.permohonan-parkir.upload-laporan', $permohonan->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>File Laporan Analisis <span class="text-danger">*</span></label>
                                            <input type="file" name="file_laporan_analisis" class="form-control"
                                                accept=".pdf" required>
                                        </div>
                                        <div class="row">
                                            @foreach ($fieldNaskah as [$name, $label, $type, $col])
                                                <div class="col-md-{{ $col }}">
                                                    <div class="form-group">
                                                        <label>{{ $label }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="{{ $type }}" name="{{ $name }}"
                                                            class="form-control"
                                                            value="{{ old($name, $type === 'date' ? $permohonan->tanggal_dokumen?->format('Y-m-d') : $permohonan->$name) }}"
                                                            required>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Upload & Siapkan Surat Pernyataan
                                        </button>
                                    </form>

                                    {{-- Verifikasi TTD --}}
                                @elseif ($permohonan->status === 'menunggu_ttd')
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
                                            action="{{ route('admin.permohonan-parkir.verifikasi-ttd', $permohonan->id) }}"
                                            method="POST" enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <div class="form-group">
                                                <label>Surat Rekomendasi Final <small class="text-muted">(wajib jika
                                                        disetujui)</small></label>
                                                <input type="file" name="file_surat_rekomendasi" class="form-control"
                                                    accept=".pdf">
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan <small class="text-muted">(wajib jika
                                                        revisi)</small></label>
                                                <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
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
                                            <i class="fas fa-hourglass-half mr-1"></i>
                                            Menunggu pemohon mengunduh, menandatangani, dan mengupload surat pernyataan.
                                        </div>
                                    @endif

                                    {{-- Menunggu pemohon mengupload ulang TTD --}}
                                @elseif ($permohonan->status === 'revisi_ttd')
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Menunggu pemohon mengupload ulang surat pernyataan.
                                        <br><strong>Catatan:</strong> {{ $permohonan->catatan_verifikasi_ttd }}
                                    </div>

                                    {{-- Selesai --}}
                                @elseif ($permohonan->status === 'selesai')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Rekomendasi Parkir sudah terbit pada
                                        {{ $permohonan->diterbitkan_at->format('d M Y H:i') }}.
                                        <a href="{{ asset('storage/' . $permohonan->file_surat_rekomendasi) }}"
                                            target="_blank" class="ml-2">Lihat Surat</a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- Riwayat --}}
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
        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: @json($errors->first())
            });
        @endif
    </script>
@endpush
