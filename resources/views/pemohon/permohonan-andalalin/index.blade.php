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
                                                <a href="{{ route('pemohon.permohonan-andalalin.show', $p->id) }}"
                                                    class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
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
@endpush

@push('scripts')
    <script>
        const BASE_URL = "{{ route('pemohon.permohonan-andalalin.index') }}";
    </script>
@endpush
