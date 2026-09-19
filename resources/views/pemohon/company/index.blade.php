@extends('layouts.app')
@section('title', 'Profil Perusahaan')

@push('style')
    <style>
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-section h5 {
            color: #6777ef;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6777ef;
        }

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

        .file-item .file-name {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-missing {
            color: #98a6ad;
            font-style: italic;
            font-size: .85rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil Perusahaan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Profil Perusahaan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $company->nama_perusahaan }}</h4>
                        @php
                            $badge = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'verified' => 'success',
                                'rejected' => 'danger',
                            ][$company->status];
                            $label = [
                                'draft' => 'Draft',
                                'pending' => 'Menunggu Verifikasi',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ][$company->status];
                        @endphp
                        <span class="badge badge-{{ $badge }} p-2">{{ $label }}</span>
                    </div>

                    <div class="card-body">
                        @if ($company->status === 'rejected' && $company->catatan_verifikasi)
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <strong>Catatan Penolakan:</strong> {{ $company->catatan_verifikasi }}
                            </div>
                        @endif

                        {{-- Data Perusahaan --}}
                        <div class="info-section">
                            <h5><i class="fas fa-building mr-1"></i> Data Perusahaan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Bentuk Badan Usaha</div>
                                        <div class="info-value">{{ $company->bentuk_badan_usaha }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">NPWP</div>
                                        <div class="info-value">{{ $company->npwp_perusahaan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">NIB</div>
                                        <div class="info-value">{{ $company->nib }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Telepon</div>
                                        <div class="info-value">{{ $company->telepon_perusahaan }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email</div>
                                        <div class="info-value">{{ $company->email_perusahaan ?? '-' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Alamat</div>
                                        <div class="info-value">{{ $company->alamat_perusahaan }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                @if ($company->akta_pendirian)
                                    <div class="file-item">
                                        <div class="file-name">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <span>Akta Pendirian Perusahaan</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $company->akta_pendirian) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                @else
                                    <p class="file-missing mb-0">Akta Pendirian belum diunggah.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Data Penanggung Jawab --}}
                        <div class="info-section">
                            <h5><i class="fas fa-user-tie mr-1"></i> Data Penanggung Jawab</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Nama</div>
                                        <div class="info-value">{{ $company->nama_penanggung_jawab }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Jabatan</div>
                                        <div class="info-value">{{ $company->jabatan_penanggung_jawab }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <div class="info-label">Telepon</div>
                                        <div class="info-value">{{ $company->telepon_penanggung_jawab }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                @if ($company->ktp_penanggung_jawab)
                                    <div class="file-item">
                                        <div class="file-name">
                                            <i class="fas fa-id-card text-info"></i>
                                            <span>KTP Penanggung Jawab</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $company->ktp_penanggung_jawab) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </a>
                                    </div>
                                @else
                                    <p class="file-missing mb-0">KTP Penanggung Jawab belum diunggah.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Surat Kuasa --}}
                        @if ($company->surat_kuasa || $company->nama_kuasa)
                            <div class="info-section">
                                <h5><i class="fas fa-file-signature mr-1"></i> Surat Kuasa</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">Nama Kuasa</div>
                                            <div class="info-value">{{ $company->nama_kuasa ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <div class="info-label">Jabatan Kuasa</div>
                                            <div class="info-value">{{ $company->jabatan_kuasa ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if ($company->surat_kuasa)
                                        <div class="file-item">
                                            <div class="file-name">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                                <span>Surat Kuasa</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $company->surat_kuasa) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye mr-1"></i> Lihat File
                                            </a>
                                        </div>
                                    @else
                                        <p class="file-missing mb-0">File surat kuasa belum diunggah.</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        @if (in_array($company->status, ['draft', 'rejected']))
                            <a href="{{ route('pemohon.company.edit') }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Edit Data
                            </a>
                            <form action="{{ route('pemohon.company.submit-verifikasi') }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Verifikasi
                                </button>
                            </form>
                        @elseif ($company->status === 'pending')
                            <p class="text-muted mb-0"><i class="fas fa-clock mr-1"></i> Data sedang menunggu verifikasi
                                admin.</p>
                        @endif
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
            timerProgressBar: true,
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
