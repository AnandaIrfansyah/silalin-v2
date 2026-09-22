@extends('layouts.app')
@section('title', 'Permohonan Andalalin')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permohonan Andalalin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Permohonan Andalalin</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Riwayat Permohonan</h4>
                        <a href="{{ route('pemohon.permohonan-andalalin.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajukan Baru
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Jenis Kegiatan</th>
                                        <th class="text-white">Kategori</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Tanggal</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($permohonan as $i => $p)
                                        <tr>
                                            <td class="text-center">{{ $permohonan->firstItem() + $i }}</td>
                                            <td>{{ $p->jenis_kegiatan_snapshot }}</td>
                                            <td class="text-center">
                                                @php
                                                    $kBadge = [
                                                        'rendah' => 'success',
                                                        'sedang' => 'warning',
                                                        'tinggi' => 'danger',
                                                    ][$p->kategori_bangkitan];
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $kBadge }}">{{ ucfirst($p->kategori_bangkitan) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-info">{{ str_replace('_', ' ', ucfirst($p->status)) }}</span>
                                            </td>
                                            <td class="text-center">{{ $p->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info"
                                                    onclick="detailModal({{ $p->id }})" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                                Belum ada permohonan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right mt-3">
                            {{ $permohonan->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title">Detail Permohonan</h5>
                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        const BASE_URL = "{{ route('pemohon.permohonan-andalalin.index') }}";

        function detailModal(id) {
            $('#detailModal').modal('show');
            $('#detailBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

            $.get(`${BASE_URL}/${id}/show`, function(res) {
                if (!res.status) return;
                const d = res.data;

                let riwayat = d.logs.map(l => `
                    <div class="mb-2 pb-2 border-bottom">
                        <small class="text-muted">${new Date(l.created_at).toLocaleString('id-ID')}</small><br>
                        <strong>${l.user.name}</strong>: ${l.aksi.replace(/_/g, ' ')}
                        ${l.keterangan ? `<br><em>${l.keterangan}</em>` : ''}
                    </div>
                `).join('');

                $('#detailBody').html(`
                    <p><strong>Jenis Kegiatan:</strong> ${d.jenis_kegiatan_snapshot}</p>
                    <p><strong>Alamat Proyek:</strong> ${d.alamat_proyek}</p>
                    <p><strong>Status:</strong> ${d.status.replace(/_/g, ' ')}</p>
                    <hr>
                    <h6>Riwayat Proses</h6>
                    ${riwayat || '<p class="text-muted">Belum ada riwayat.</p>'}
                `);
            });
        }
    </script>
@endpush
