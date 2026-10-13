@extends('layouts.app')
@section('title', 'Permohonan Parkir')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permohonan Parkir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Permohonan Parkir</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Daftar Permohonan</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.permohonan-parkir.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="search" value="{{ $search }}"
                                        placeholder="Cari nama lokasi atau perusahaan...">
                                </div>
                                <div class="col-md-4">
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        @foreach (['diajukan', 'revisi_berkas', 'dijadwalkan_survey', 'dijadwalkan_paparan', 'menunggu_ttd', 'revisi_ttd', 'selesai'] as $s)
                                            <option value="{{ $s }}" @selected($status === $s)>
                                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search mr-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Perusahaan</th>
                                        <th class="text-white">Nama Lokasi</th>
                                        <th class="text-white">Jenis</th>
                                        <th class="text-white">Status</th>
                                        <th class="text-white">Tanggal</th>
                                        <th class="text-white">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($permohonan as $i => $p)
                                        <tr>
                                            <td class="text-center">{{ $permohonan->firstItem() + $i }}</td>
                                            <td>{{ $p->company->nama_perusahaan }}</td>
                                            <td>{{ $p->nama_lokasi }}</td>
                                            <td class="text-center">{{ ucfirst($p->jenis_parkir) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ $p->status_badge }}">{{ $p->status_label }}</span>
                                            </td>
                                            <td class="text-center">{{ $p->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.permohonan-parkir.show', $p->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
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
                            {{ $permohonan->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
