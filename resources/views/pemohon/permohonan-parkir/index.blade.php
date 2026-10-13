@extends('layouts.app')
@section('title', 'Permohonan Parkir')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permohonan Rekomendasi Parkir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Permohonan Parkir</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Riwayat Permohonan</h4>
                        <a href="{{ route('pemohon.permohonan-parkir.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajukan Baru
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Nama Lokasi</th>
                                        <th class="text-white">Jenis</th>
                                        <th class="text-white">Luas Area</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Tanggal</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($permohonan as $i => $p)
                                        <tr>
                                            <td class="text-center">{{ $permohonan->firstItem() + $i }}</td>
                                            <td>{{ $p->nama_lokasi }}</td>
                                            <td class="text-center">{{ ucfirst($p->jenis_parkir) }}</td>
                                            <td class="text-right">{{ number_format($p->luas_area, 2, ',', '.') }} m²</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ $p->status_badge }}">{{ $p->status_label }}</span>
                                            </td>
                                            <td class="text-center">{{ $p->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('pemohon.permohonan-parkir.show', $p->id) }}"
                                                    class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-square-parking fa-2x mb-2 d-block"></i>
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
