@extends('layouts.app')
@section('title', 'Master Dokumen Persyaratan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Master Dokumen Persyaratan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Master Data</div>
                    <div class="breadcrumb-item">Dokumen Persyaratan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Dokumen Persyaratan</h4>
                        <button class="btn btn-primary" onclick="openModal()">
                            <i class="fas fa-plus"></i> Tambah Dokumen
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.master-dokumen.index') }}">
                                    <input type="hidden" name="jenis_permohonan" value="{{ $jenis }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama dokumen..."
                                            name="search" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                @foreach (['' => 'Semua', 'andalalin' => 'Andalalin', 'parkir' => 'Parkir', 'semua' => 'Overlap (Keduanya)'] as $j => $l)
                                    <a href="{{ route('admin.master-dokumen.index', ['jenis_permohonan' => $j, 'search' => $search]) }}"
                                        class="btn btn-sm mr-1 {{ $jenis === $j ? 'btn-primary' : 'btn-outline-secondary' }}">
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
                                        <th class="text-white">Urutan</th>
                                        <th class="text-white">Nama Dokumen</th>
                                        <th class="text-white">Berlaku Untuk</th>
                                        <th class="text-white">Wajib?</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dokumen as $i => $d)
                                        <tr>
                                            <td class="text-center">{{ $dokumen->firstItem() + $i }}</td>
                                            <td class="text-center">{{ $d->urutan }}</td>
                                            <td>
                                                {{ $d->nama_dokumen }}
                                                @if ($d->keterangan)
                                                    <br><small class="text-muted">{{ $d->keterangan }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $jenisBadge = [
                                                        'andalalin' => 'info',
                                                        'parkir' => 'primary',
                                                        'semua' => 'dark',
                                                    ][$d->jenis_permohonan];
                                                    $jenisLabel = [
                                                        'andalalin' => 'Andalalin',
                                                        'parkir' => 'Parkir',
                                                        'semua' => 'Keduanya',
                                                    ][$d->jenis_permohonan];
                                                @endphp
                                                <span class="badge badge-{{ $jenisBadge }}">{{ $jenisLabel }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $d->is_wajib ? 'danger' : 'secondary' }}">
                                                    {{ $d->is_wajib ? 'Wajib' : 'Opsional' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $d->is_active ? 'success' : 'secondary' }}">
                                                    {{ $d->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center" style="white-space:nowrap">
                                                <button class="btn btn-sm btn-{{ $d->is_active ? 'warning' : 'success' }}"
                                                    onclick="toggleActive({{ $d->id }}, '{{ addslashes($d->nama_dokumen) }}')"
                                                    title="{{ $d->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $d->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info"
                                                    onclick="editModal({{ $d->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem({{ $d->id }}, '{{ addslashes($d->nama_dokumen) }}')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                                Belum ada data dokumen persyaratan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $dokumen->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokumen</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="dokumenForm">
                    <input type="hidden" id="dokumen_id">
                    <input type="hidden" id="form_type" value="create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Dokumen <span class="text-danger">*</span></label>
                            <input type="text" id="nama_dokumen" class="form-control"
                                placeholder="Misal: Surat Permohonan" required>
                        </div>
                        <div class="form-group">
                            <label>Kode <span class="text-danger">*</span></label>
                            <input type="text" id="kode" class="form-control"
                                placeholder="Misal: surat_permohonan" required>
                            <small class="text-muted">Huruf kecil, angka, tanda hubung/underscore. Identifier unik.</small>
                        </div>
                        <div class="form-group">
                            <label>Berlaku Untuk <span class="text-danger">*</span></label>
                            <select id="jenis_permohonan" class="form-control" required>
                                <option value="semua">Keduanya (Andalalin & Parkir)</option>
                                <option value="andalalin">Khusus Andalalin</option>
                                <option value="parkir">Khusus Parkir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Wajib Diupload? <span class="text-danger">*</span></label>
                            <select id="is_wajib" class="form-control" required>
                                <option value="1">Wajib</option>
                                <option value="0">Opsional</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea id="keterangan" class="form-control" rows="2"
                                placeholder="Opsional, misal: format PDF maksimal 5MB"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Urutan Tampil</label>
                            <input type="number" id="urutan" class="form-control" placeholder="0" min="0">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        const BASE_URL = "{{ route('admin.master-dokumen.index') }}";
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

        function openModal() {
            $('#formModal').modal('show');
            $('.modal-title').text('Tambah Dokumen');
            $('#dokumenForm')[0].reset();
            $('#form_type').val('create');
            $('#dokumen_id').val('');
        }

        function editModal(id) {
            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;
                $('#formModal').modal('show');
                $('.modal-title').text('Edit Dokumen');
                $('#form_type').val('update');
                $('#dokumen_id').val(d.id);
                $('#nama_dokumen').val(d.nama_dokumen);
                $('#kode').val(d.kode);
                $('#jenis_permohonan').val(d.jenis_permohonan);
                $('#is_wajib').val(d.is_wajib ? '1' : '0');
                $('#keterangan').val(d.keterangan);
                $('#urutan').val(d.urutan);
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat data.'
                });
            });
        }

        $('#dokumenForm').submit(function(e) {
            e.preventDefault();
            const type = $('#form_type').val();
            const id = $('#dokumen_id').val();
            const url = type === 'create' ? `${BASE_URL}/store` : `${BASE_URL}/${id}/update`;
            const method = type === 'create' ? 'POST' : 'PUT';

            $.ajax({
                url,
                method,
                data: {
                    nama_dokumen: $('#nama_dokumen').val(),
                    kode: $('#kode').val(),
                    jenis_permohonan: $('#jenis_permohonan').val(),
                    is_wajib: $('#is_wajib').val(),
                    keterangan: $('#keterangan').val(),
                    urutan: $('#urutan').val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    $('#formModal').modal('hide');
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
                            title: xhr.responseJSON?.message || 'Terjadi kesalahan.'
                        });
                    }
                }
            });
        });

        function toggleActive(id, nama) {
            Swal.fire({
                title: 'Ubah Status Dokumen?',
                html: `Ubah status aktif untuk <strong>${nama}</strong>? Dokumen nonaktif tidak akan diminta di form permohonan baru.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f6c23e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/${id}/toggle-active`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'Terjadi kesalahan.'
                            });
                        }
                    });
                }
            });
        }

        function deleteItem(id, nama) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                html: `Yakin hapus <strong>${nama}</strong>? Tindakan ini tidak bisa dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/${id}/destroy`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON?.message || 'Gagal menghapus.'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
