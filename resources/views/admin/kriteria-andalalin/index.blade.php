@extends('layouts.app')
@section('title', 'Kriteria Andalalin')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kriteria Andalalin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Master Data</div>
                    <div class="breadcrumb-item">Kriteria Andalalin</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Kriteria Andalalin</h4>
                        <button class="btn btn-primary" onclick="openModal()">
                            <i class="fas fa-plus"></i> Tambah Kriteria
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.kriteria-andalalin.index') }}">
                                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari jenis kegiatan..."
                                            name="search" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                @foreach (['' => 'Semua', 'rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi'] as $k => $l)
                                    <a href="{{ route('admin.kriteria-andalalin.index', ['kategori' => $k, 'search' => $search]) }}"
                                        class="btn btn-sm mr-1 {{ $kategori === $k ? 'btn-primary' : 'btn-outline-secondary' }}">
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
                                        <th class="text-white">Jenis Kegiatan</th>
                                        <th class="text-white">Parameter</th>
                                        <th class="text-white">Batas Min</th>
                                        <th class="text-white">Batas Max</th>
                                        <th class="text-white">Satuan</th>
                                        <th class="text-white">Kategori</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kriteria as $i => $k)
                                        <tr>
                                            <td class="text-center">{{ $kriteria->firstItem() + $i }}</td>
                                            <td>{{ $k->jenis_kegiatan }}</td>
                                            <td>{{ $k->parameter }}</td>
                                            <td class="text-right">
                                                {{ $k->batas_min !== null ? number_format($k->batas_min, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $k->batas_max !== null ? number_format($k->batas_max, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-center">{{ $k->satuan ?? '-' }}</td>
                                            <td class="text-center">
                                                @php
                                                    $badge = [
                                                        'rendah' => 'success',
                                                        'sedang' => 'warning',
                                                        'tinggi' => 'danger',
                                                    ][$k->kategori];
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $badge }}">{{ ucfirst($k->kategori) }}</span>
                                            </td>
                                            <td class="text-center" style="white-space:nowrap">
                                                <button class="btn btn-sm btn-info"
                                                    onclick="editModal({{ $k->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem({{ $k->id }}, '{{ addslashes($k->jenis_kegiatan) }}')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="fas fa-list fa-2x mb-2 d-block"></i>
                                                Belum ada data kriteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $kriteria->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title">Tambah Kriteria Andalalin</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="kriteriaForm">
                    <input type="hidden" id="kriteria_id">
                    <input type="hidden" id="form_type" value="create">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Jenis Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" id="jenis_kegiatan" class="form-control"
                                placeholder="Misal: Pusat Perbelanjaan" required>
                        </div>
                        <div class="form-group">
                            <label>Parameter <span class="text-danger">*</span></label>
                            <input type="text" id="parameter" class="form-control"
                                placeholder="Misal: Luas Lantai Bangunan" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batas Min</label>
                                    <input type="number" step="0.01" id="batas_min" class="form-control"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batas Max</label>
                                    <input type="number" step="0.01" id="batas_max" class="form-control"
                                        placeholder="Kosongkan jika tidak ada batas atas">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" id="satuan" class="form-control"
                                        placeholder="Misal: m2, unit, SRP">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori <span class="text-danger">*</span></label>
                                    <select id="kategori" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="rendah">Rendah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="tinggi">Tinggi</option>
                                    </select>
                                </div>
                            </div>
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
        const BASE_URL = "{{ route('admin.kriteria-andalalin.index') }}";
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
            $('.modal-title').text('Tambah Kriteria Andalalin');
            $('#kriteriaForm')[0].reset();
            $('#form_type').val('create');
            $('#kriteria_id').val('');
        }

        function editModal(id) {
            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;
                $('#formModal').modal('show');
                $('.modal-title').text('Edit Kriteria Andalalin');
                $('#form_type').val('update');
                $('#kriteria_id').val(d.id);
                $('#jenis_kegiatan').val(d.jenis_kegiatan);
                $('#parameter').val(d.parameter);
                $('#batas_min').val(d.batas_min);
                $('#batas_max').val(d.batas_max);
                $('#satuan').val(d.satuan);
                $('#kategori').val(d.kategori);
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat data.'
                });
            });
        }

        $('#kriteriaForm').submit(function(e) {
            e.preventDefault();
            const type = $('#form_type').val();
            const id = $('#kriteria_id').val();
            const url = type === 'create' ? `${BASE_URL}/store` : `${BASE_URL}/${id}/update`;
            const method = type === 'create' ? 'POST' : 'PUT';

            $.ajax({
                url,
                method,
                data: {
                    jenis_kegiatan: $('#jenis_kegiatan').val(),
                    parameter: $('#parameter').val(),
                    batas_min: $('#batas_min').val(),
                    batas_max: $('#batas_max').val(),
                    satuan: $('#satuan').val(),
                    kategori: $('#kategori').val(),
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

        function deleteItem(id, nama) {
            Swal.fire({
                title: 'Hapus Kriteria?',
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
