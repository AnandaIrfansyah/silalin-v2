@extends('layouts.app')
@section('title', 'Manajemen User')
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Manajemen User</div>
                </div>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Kelola User</h4>
                        <button class="btn btn-primary" onclick="openModal()">
                            <i class="fas fa-user-plus"></i> Tambah User
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.users.index') }}">
                                    <input type="hidden" name="role" value="{{ $role }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama/email..."
                                            name="search" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                @foreach (['' => 'Semua', 'Admin' => 'Admin', 'Pemohon' => 'Pemohon'] as $r => $l)
                                    <a href="{{ route('admin.users.index', ['role' => $r, 'search' => $search]) }}"
                                        class="btn btn-sm mr-1 {{ $role === $r ? 'btn-primary' : 'btn-outline-secondary' }}">
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
                                        <th class="text-white">Nama</th>
                                        <th class="text-white">Email</th>
                                        <th class="text-white">Role</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $i => $u)
                                        <tr>
                                            <td class="text-center">{{ $users->firstItem() + $i }}</td>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td class="text-center">
                                                @php $roleName = $u->roles->first()->name ?? null; @endphp
                                                <span
                                                    class="badge badge-{{ $roleName === 'Admin' ? 'primary' : 'secondary' }}">
                                                    {{ $roleName ?? 'Tanpa Role' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $u->is_active ? 'success' : 'secondary' }}">
                                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center" style="white-space:nowrap">
                                                <button class="btn btn-sm btn-info mb-1"
                                                    onclick="editModal({{ $u->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if ($u->id !== auth()->id())
                                                    <button
                                                        class="btn btn-sm btn-{{ $u->is_active ? 'warning' : 'success' }} mb-1"
                                                        onclick="toggleActive({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                        title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i class="fas fa-{{ $u->is_active ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger mb-1"
                                                        onclick="deleteItem({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <span class="badge badge-light">Akun Anda</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                                Tidak ada data user.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title">Tambah User</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="userForm">
                    <input type="hidden" id="user_id">
                    <input type="hidden" id="form_type" value="create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role <span class="text-danger">*</span></label>
                            <select id="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="Admin">Admin</option>
                                <option value="Pemohon">Pemohon</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label id="label_password">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" class="form-control" placeholder="Minimal 8 karakter">
                            <small class="text-muted" id="hint_password" style="display:none;">
                                Kosongkan jika tidak ingin mengubah password.
                            </small>
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
        const BASE_URL = "{{ route('admin.users.index') }}";
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
            $('.modal-title').text('Tambah User');
            $('#userForm')[0].reset();
            $('#form_type').val('create');
            $('#user_id').val('');
            $('#password').prop('required', true);
            $('#label_password').html('Password <span class="text-danger">*</span>');
            $('#hint_password').hide();
        }

        function editModal(id) {
            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;
                $('#formModal').modal('show');
                $('.modal-title').text('Edit User');
                $('#form_type').val('update');
                $('#user_id').val(d.id);
                $('#name').val(d.name);
                $('#email').val(d.email);
                $('#role').val(d.role);
                $('#password').val('').prop('required', false);
                $('#label_password').html('Password <small class="text-muted">(opsional)</small>');
                $('#hint_password').show();
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat data.'
                });
            });
        }

        $('#userForm').submit(function(e) {
            e.preventDefault();
            const type = $('#form_type').val();
            const id = $('#user_id').val();
            const url = type === 'create' ? `${BASE_URL}/store` : `${BASE_URL}/${id}/update`;
            const method = type === 'create' ? 'POST' : 'PUT';
            $.ajax({
                url,
                method,
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    role: $('#role').val(),
                    password: $('#password').val(),
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
                title: 'Ubah Status User?',
                html: `Ubah status aktif untuk <strong>${nama}</strong>?`,
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
                title: 'Hapus User?',
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
