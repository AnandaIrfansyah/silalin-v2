@extends('layouts.app')
@section('title', 'Verifikasi Perusahaan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Verifikasi Perusahaan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Verifikasi Perusahaan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Daftar Pengajuan Perusahaan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.verifikasi-perusahaan.index') }}">
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            placeholder="Cari nama perusahaan/NPWP/NIB..." name="search"
                                            value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                @foreach (['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', '' => 'Semua'] as $s => $l)
                                    <a href="{{ route('admin.verifikasi-perusahaan.index', ['status' => $s, 'search' => $search]) }}"
                                        class="btn btn-sm mr-1 {{ $status === $s ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        {{ $l }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Perusahaan</th>
                                        <th class="text-white">Pemohon</th>
                                        <th class="text-white">NPWP</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($companies as $i => $c)
                                        <tr>
                                            <td class="text-center">{{ $companies->firstItem() + $i }}</td>
                                            <td>{{ $c->nama_perusahaan }}</td>
                                            <td>{{ $c->user->name }}</td>
                                            <td>{{ $c->npwp_perusahaan }}</td>
                                            <td class="text-center">
                                                @php
                                                    $badge = [
                                                        'draft' => 'secondary',
                                                        'pending' => 'warning',
                                                        'verified' => 'success',
                                                        'rejected' => 'danger',
                                                    ][$c->status];
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $badge }}">{{ ucfirst($c->status) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info"
                                                    onclick="detailModal({{ $c->id }})" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-building fa-2x mb-2 d-block"></i>
                                                Tidak ada data.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $companies->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Perusahaan</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke" id="detailFooter"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="rejectForm">
                    <input type="hidden" id="reject_company_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan Penolakan <span class="text-danger">*</span></label>
                            <textarea id="catatan_verifikasi" class="form-control" rows="3"
                                placeholder="Jelaskan alasan penolakan, misal: dokumen tidak jelas / data tidak sesuai" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-1"></i> Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        const BASE_URL = "{{ route('admin.verifikasi-perusahaan.index') }}";
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

        function fileRow(label, url) {
            if (!url) {
                return `<div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2">
                            <span>${label}</span>
                            <span class="text-muted font-italic small">Belum diunggah</span>
                        </div>`;
            }
            return `<div class="d-flex justify-content-between align-items-center border rounded px-3 py-2 mb-2">
                        <span>${label}</span>
                        <a href="${url}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye mr-1"></i> Lihat File
                        </a>
                    </div>`;
        }

        function detailModal(id) {
            $('#detailModal').modal('show');
            $('#detailBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
            $('#detailFooter').html('');

            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;

                $('#detailBody').html(`
                    <h6 class="text-primary">Data Perusahaan</h6>
                    <table class="table table-sm table-borderless mb-3">
                        <tr><th width="180">Nama Perusahaan</th><td>: ${d.nama_perusahaan}</td></tr>
                        <tr><th>Bentuk Badan Usaha</th><td>: ${d.bentuk_badan_usaha}</td></tr>
                        <tr><th>NPWP</th><td>: ${d.npwp_perusahaan}</td></tr>
                        <tr><th>NIB</th><td>: ${d.nib}</td></tr>
                        <tr><th>Alamat</th><td>: ${d.alamat_perusahaan}</td></tr>
                        <tr><th>Telepon</th><td>: ${d.telepon_perusahaan}</td></tr>
                        <tr><th>Email</th><td>: ${d.email_perusahaan ?? '-'}</td></tr>
                    </table>
                    ${fileRow('Akta Pendirian', d.akta_pendirian_url)}

                    <h6 class="text-primary mt-3">Penanggung Jawab</h6>
                    <table class="table table-sm table-borderless mb-3">
                        <tr><th width="180">Nama</th><td>: ${d.nama_penanggung_jawab}</td></tr>
                        <tr><th>Jabatan</th><td>: ${d.jabatan_penanggung_jawab}</td></tr>
                        <tr><th>Telepon</th><td>: ${d.telepon_penanggung_jawab}</td></tr>
                    </table>
                    ${fileRow('KTP Penanggung Jawab', d.ktp_penanggung_jawab_url)}

                    ${d.nama_kuasa ? `
                            <h6 class="text-primary mt-3">Surat Kuasa</h6>
                            <table class="table table-sm table-borderless mb-3">
                                <tr><th width="180">Nama Kuasa</th><td>: ${d.nama_kuasa}</td></tr>
                                <tr><th>Jabatan Kuasa</th><td>: ${d.jabatan_kuasa ?? '-'}</td></tr>
                            </table>
                            ${fileRow('Surat Kuasa', d.surat_kuasa_url)}
                        ` : ''}

                    <h6 class="text-primary mt-3">Pengaju</h6>
                    <p class="mb-0">${d.pemohon_name} — ${d.pemohon_email}</p>
                `);

                if (d.status === 'pending') {
                    $('#detailFooter').html(`
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-danger" onclick="openRejectModal(${d.id})">
                            <i class="fas fa-times mr-1"></i> Tolak
                        </button>
                        <button type="button" class="btn btn-success" onclick="approveCompany(${d.id})">
                            <i class="fas fa-check mr-1"></i> Setujui
                        </button>
                    `);
                } else {
                    $('#detailFooter').html(
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>');
                }
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat detail.'
                });
            });
        }

        function approveCompany(id) {
            Swal.fire({
                title: 'Setujui Perusahaan Ini?',
                text: 'Data akan berstatus Terverifikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1abc9c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/${id}/approve`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            $('#detailModal').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'Gagal menyetujui.'
                            });
                        }
                    });
                }
            });
        }

        function openRejectModal(id) {
            $('#detailModal').modal('hide');
            $('#reject_company_id').val(id);
            $('#catatan_verifikasi').val('');
            $('#rejectModal').modal('show');
        }

        $('#rejectForm').submit(function(e) {
            e.preventDefault();
            const id = $('#reject_company_id').val();

            $.ajax({
                url: `${BASE_URL}/${id}/reject`,
                method: 'POST',
                data: {
                    catatan_verifikasi: $('#catatan_verifikasi').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    $('#rejectModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        const msg = errors ? Object.values(errors).flat().join('<br>') : xhr
                            .responseJSON.message;
                        Toast.fire({
                            icon: 'error',
                            title: msg
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Gagal menolak.'
                        });
                    }
                }
            });
        });
    </script>
@endpush
