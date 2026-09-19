@extends('layouts.app')
@section('title', 'Master Fasilitas')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Master Fasilitas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Master Data</div>
                    <div class="breadcrumb-item">Master Fasilitas</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Fasilitas</h4>
                        <button class="btn btn-primary" onclick="openModal()">
                            <i class="fas fa-plus"></i> Tambah Fasilitas
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.master-fasilitas.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama fasilitas..."
                                            name="search" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Urutan</th>
                                        <th class="text-white">Nama Fasilitas</th>
                                        <th class="text-white">Kode</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($fasilitas as $i => $f)
                                        <tr>
                                            <td class="text-center">{{ $fasilitas->firstItem() + $i }}</td>
                                            <td class="text-center">{{ $f->urutan }}</td>
                                            <td>
                                                {{ $f->nama_fasilitas }}
                                                @if ($f->deskripsi)
                                                    <br><small class="text-muted">{{ $f->deskripsi }}</small>
                                                @endif
                                            </td>
                                            <td><code>{{ $f->kode }}</code></td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $f->is_active ? 'success' : 'secondary' }}">
                                                    {{ $f->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center" style="white-space:nowrap">
                                                <button class="btn btn-sm btn-{{ $f->is_active ? 'warning' : 'success' }}"
                                                    onclick="toggleActive({{ $f->id }}, '{{ addslashes($f->nama_fasilitas) }}')"
                                                    title="{{ $f->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $f->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info"
                                                    onclick="editModal({{ $f->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem({{ $f->id }}, '{{ addslashes($f->nama_fasilitas) }}')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-list-check fa-2x mb-2 d-block"></i>
                                                Belum ada data fasilitas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $fasilitas->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title">Tambah Fasilitas</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="fasilitasForm">
                    <input type="hidden" id="fasilitas_id">
                    <input type="hidden" id="form_type" value="create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Fasilitas <span class="text-danger">*</span></label>
                            <input type="text" id="nama_fasilitas" class="form-control" placeholder="Misal: Marka Parkir"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Kode <span class="text-danger">*</span></label>
                            <input type="text" id="kode" class="form-control" placeholder="Misal: marka_parkir"
                                required>
                            <small class="text-muted">Huruf kecil, angka, dan tanda hubung/underscore saja. Dipakai sebagai
                                identifier unik.</small>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea id="deskripsi" class="form-control" rows="2" placeholder="Opsional"></textarea>
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
        const BASE_URL = "{{ route('admin.master-fasilitas.index') }}";
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
            $('.modal-title').text('Tambah Fasilitas');
            $('#fasilitasForm')[0].reset();
            $('#form_type').val('create');
            $('#fasilitas_id').val('');
        }

        function editModal(id) {
            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;
                $('#formModal').modal('show');
                $('.modal-title').text('Edit Fasilitas');
                $('#form_type').val('update');
                $('#fasilitas_id').val(d.id);
                $('#nama_fasilitas').val(d.nama_fasilitas);
                $('#kode').val(d.kode);
                $('#deskripsi').val(d.deskripsi);
                $('#urutan').val(d.urutan);
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat data.'
                });
            });
        }

        $('#fasilitasForm').submit(function(e) {
            e.preventDefault();
            const type = $('#form_type').val();
            const id = $('#fasilitas_id').val();
            const url = type === 'create' ? `${BASE_URL}/store` : `${BASE_URL}/${id}/update`;
            const method = type === 'create' ? 'POST' : 'PUT';

            $.ajax({
                url,
                method,
                data: {
                    nama_fasilitas: $('#nama_fasilitas').val(),
                    kode: $('#kode').val(),
                    deskripsi: $('#deskripsi').val(),
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
                title: 'Ubah Status Fasilitas?',
                html: `Ubah status aktif untuk <strong>${nama}</strong>? Fasilitas nonaktif tidak akan muncul di form permohonan baru.`,
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
                title: 'Hapus Fasilitas?',
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
