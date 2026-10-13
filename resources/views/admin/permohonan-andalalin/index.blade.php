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
                    <div class="card-header">
                        <h4 class="mb-0">Daftar Permohonan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form method="GET">
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search"
                                            value="{{ $search }}" placeholder="Cari perusahaan/jenis kegiatan...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                <select
                                    onchange="location.href='{{ route('admin.permohonan-andalalin.index') }}?status='+this.value"
                                    class="form-control d-inline-block" style="width:auto;">
                                    <option value="">Semua Status</option>
                                    @foreach (['diajukan', 'revisi_berkas', 'dijadwalkan_survey', 'survey_selesai', 'menunggu_dokumen_andalalin', 'revisi_dokumen_andalalin', 'dijadwalkan_paparan', 'menunggu_ttd', 'revisi_ttd', 'selesai'] as $s)
                                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', ucfirst($s)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-primary text-center">
                                    <tr>
                                        <th class="text-white">No</th>
                                        <th class="text-white">Perusahaan</th>
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
                                            <td>{{ $p->company->nama_perusahaan }}</td>
                                            <td>{{ $p->jenis_kegiatan_snapshot }}</td>
                                            <td class="text-center">
                                                @php $kBadge = ['rendah' => 'success', 'sedang' => 'warning', 'tinggi' => 'danger'][$p->kategori_bangkitan]; @endphp
                                                <span
                                                    class="badge badge-{{ $kBadge }}">{{ ucfirst($p->kategori_bangkitan) }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-info">{{ str_replace('_', ' ', ucfirst($p->status)) }}</span>
                                            </td>
                                            <td class="text-center">{{ $p->created_at->format('d M Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.permohonan-andalalin.show', $p->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada permohonan.
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
