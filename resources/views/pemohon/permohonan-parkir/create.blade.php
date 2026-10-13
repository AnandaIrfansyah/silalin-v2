@extends('layouts.app')
@section('title', 'Ajukan Permohonan Rekomendasi Parkir')

@push('style')
    <style>
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: .85rem;
            border: 2px solid #dee2e6;
            background: #fff;
            color: #6c757d;
            transition: all .3s;
        }

        .step-circle.active {
            border-color: #6777ef;
            background: #6777ef;
            color: #fff;
        }

        .step-circle.done {
            border-color: #28a745;
            background: #28a745;
            color: #fff;
        }

        .step-label {
            font-size: .72rem;
            margin-top: 4px;
            color: #6c757d;
            white-space: nowrap;
        }

        .step-label.active {
            color: #6777ef;
            font-weight: 600;
        }

        .step-label.done {
            color: #28a745;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #dee2e6;
            margin: 0 8px 20px;
            transition: all .3s;
        }

        .step-line.done {
            background: #28a745;
        }

        .section-title {
            color: #6777ef;
            font-weight: 700;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #eef0fc;
        }

        .doc-upload-item {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
        }

        .doc-upload-item label {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .fasilitas-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px dashed #e3e8f1;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 160px;
            color: #6c757d;
            flex-shrink: 0;
        }

        .info-value {
            font-weight: 500;
        }

        .review-fasilitas {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fc;
            border: 1px solid #e3e8f1;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 12px;
        }

        .review-doc-card {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid #e3e8f1;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 12px;
        }

        .review-doc-card .doc-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .review-doc-card.uploaded .doc-icon {
            background: #e5f9ee;
            color: #28a745;
        }

        .review-doc-card.missing .doc-icon {
            background: #fdeeee;
            color: #dc3545;
        }

        .review-doc-card .doc-info {
            min-width: 0;
            flex: 1;
        }

        .review-doc-card .doc-name {
            font-weight: 600;
            font-size: .88rem;
        }

        .review-doc-card .doc-status {
            font-size: .76rem;
            word-break: break-all;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ajukan Permohonan Rekomendasi Parkir</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('pemohon.permohonan-parkir.index') }}">Permohonan Parkir</a>
                    </div>
                    <div class="breadcrumb-item">Buat Baru</div>
                </div>
            </div>

            <div class="section-body">
                <div class="step-indicator">
                    <div class="step-item">
                        <div class="step-circle active" id="circle-1">1</div>
                        <div class="step-label active" id="label-1">Info Lokasi</div>
                    </div>
                    <div class="step-line" id="line-1"></div>
                    <div class="step-item">
                        <div class="step-circle" id="circle-2">2</div>
                        <div class="step-label" id="label-2">Dok. Persyaratan</div>
                    </div>
                    <div class="step-line" id="line-2"></div>
                    <div class="step-item">
                        <div class="step-circle" id="circle-3">3</div>
                        <div class="step-label" id="label-3">Review & Submit</div>
                    </div>
                </div>

                {{-- STEP 1 --}}
                <div id="step-1" class="card">
                    <div class="card-header">
                        <h4>Step 1 — Informasi Lokasi Parkir</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_lokasi" class="form-control"
                                        placeholder="contoh: Area Parkir Mall XYZ">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Parkir <span class="text-danger">*</span></label>
                                    <select id="jenis_parkir" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <option value="umum">Umum</option>
                                        <option value="khusus">Khusus</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Luas Area Parkir (m²) <span class="text-danger">*</span></label>
                            <input type="number" id="luas_area" class="form-control" step="0.01" min="0.01"
                                placeholder="contoh: 1500">
                        </div>

                        <div class="form-group">
                            <label>Alamat Lokasi <span class="text-danger">*</span></label>
                            <textarea id="alamat_lokasi" class="form-control" rows="3"
                                placeholder="Jl. Nama Jalan No. XX, Kelurahan, Kecamatan, Kota"></textarea>
                        </div>

                        <hr>
                        <h6 class="mb-3">Fasilitas yang Tersedia</h6>
                        <div class="row">
                            @forelse ($daftarFasilitas as $f)
                                <div class="col-md-6">
                                    <div class="fasilitas-item">
                                        <span>{{ $f->nama_fasilitas }}</span>
                                        <select name="fasilitas[{{ $f->id }}]" class="form-control fasilitas-select"
                                            style="width: 130px;">
                                            <option value="tidak ada">Tidak Ada</option>
                                            <option value="ada">Ada</option>
                                        </select>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">Belum ada daftar fasilitas yang aktif.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary" onclick="validasiStep1()">
                            Lanjut <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div id="step-2" class="card d-none">
                    <div class="card-header">
                        <h4>Step 2 — Dokumen Persyaratan</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" style="font-size:.85rem;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Format PDF/JPG/PNG, maksimal 5MB per file.
                        </div>
                        <div class="row">
                            @foreach ($dokumenPersyaratan as $dok)
                                <div class="col-md-6">
                                    <div class="doc-upload-item">
                                        <label>
                                            {{ $dok->nama_dokumen }}
                                            @if ($dok->is_wajib)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <input type="file" id="dok_{{ $dok->kode }}" data-kode="{{ $dok->kode }}"
                                            class="form-control doc-file" accept=".pdf,.jpg,.jpeg,.png">
                                        @if ($dok->keterangan)
                                            <small class="text-muted d-block">{{ $dok->keterangan }}</small>
                                        @endif
                                        <div class="file-status-{{ $dok->kode }} mt-1"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="keStep(1)">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-primary" onclick="validasiStep2()">
                            Lanjut <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div id="step-3" class="card d-none">
                    <div class="card-header">
                        <h4>Step 3 — Review & Submit</h4>
                    </div>
                    <div class="card-body">
                        <h6 class="section-title"><i class="fas fa-map-marker-alt mr-1"></i> Informasi Lokasi</h6>
                        <div class="mb-4">
                            <div class="info-row">
                                <div class="info-label">Nama Lokasi</div>
                                <div class="info-value" id="review_nama">-</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Jenis Parkir</div>
                                <div class="info-value" id="review_jenis">-</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Luas Area</div>
                                <div class="info-value" id="review_luas">-</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Alamat</div>
                                <div class="info-value" id="review_alamat">-</div>
                            </div>
                        </div>

                        <h6 class="section-title"><i class="fas fa-toolbox mr-1"></i> Fasilitas</h6>
                        <div class="row mb-3" id="review_fasilitas"></div>

                        <h6 class="section-title"><i class="fas fa-file-alt mr-1"></i> Dokumen Persyaratan</h6>
                        <div class="row" id="review_dokumen"></div>

                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Setelah submit, permohonan akan masuk antrian verifikasi Admin dan tidak bisa diubah lagi
                            kecuali diminta revisi.
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="keStep(2)">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-success" id="btnSubmit" onclick="submitPermohonan()">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Permohonan
                        </button>
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
            timerProgressBar: true,
        });

        function esc(text) {
            return $('<div>').text(text).html();
        }

        function keStep(n) {
            for (let i = 1; i <= 3; i++) {
                $(`#step-${i}`).addClass('d-none');
                const circle = $(`#circle-${i}`);
                const label = $(`#label-${i}`);
                circle.removeClass('active done');
                label.removeClass('active done');

                if (i < n) {
                    circle.addClass('done').html('<i class="fas fa-check"></i>');
                    label.addClass('done');
                    if (i <= 2) $(`#line-${i}`).addClass('done');
                } else if (i === n) {
                    circle.addClass('active').text(i);
                    label.addClass('active');
                } else {
                    circle.text(i);
                    if (i <= 2) $(`#line-${i}`).removeClass('done');
                }
            }

            $(`#step-${n}`).removeClass('d-none');
            if (n === 3) buildReview();
            window.scrollTo(0, 0);
        }

        function validasiStep1() {
            if (!$('#nama_lokasi').val().trim()) {
                return Toast.fire({
                    icon: 'warning',
                    title: 'Nama Lokasi wajib diisi.'
                });
            }
            if (!$('#jenis_parkir').val()) {
                return Toast.fire({
                    icon: 'warning',
                    title: 'Pilih Jenis Parkir.'
                });
            }
            if (!(parseFloat($('#luas_area').val()) > 0)) {
                return Toast.fire({
                    icon: 'warning',
                    title: 'Luas Area harus lebih dari 0.'
                });
            }
            if (!$('#alamat_lokasi').val().trim()) {
                return Toast.fire({
                    icon: 'warning',
                    title: 'Alamat Lokasi wajib diisi.'
                });
            }
            keStep(2);
        }

        function validasiStep2() {
            const belumLengkap = [];

            $('.doc-file').each(function() {
                const item = $(this).closest('.doc-upload-item');
                const isWajib = item.find('label .text-danger').length > 0;
                if (isWajib && !this.files.length) {
                    belumLengkap.push(item.find('label').text().replace('*', '').trim());
                }
            });

            if (belumLengkap.length) {
                return Toast.fire({
                    icon: 'warning',
                    title: 'Dokumen belum lengkap: ' + belumLengkap.join(', ')
                });
            }
            keStep(3);
        }

        $('.doc-file').change(function() {
            const kode = $(this).data('kode');
            const el = $(`.file-status-${kode}`);

            if (!this.files[0]) {
                return el.html('');
            }

            const file = this.files[0];
            const size = (file.size / 1024 / 1024).toFixed(2);

            if (size > 5) {
                el.html(
                    '<small class="text-danger"><i class="fas fa-times-circle"></i> File terlalu besar (maks 5MB)</small>');
            } else {
                el.html(
                    `<small class="text-success"><i class="fas fa-check-circle"></i> ${esc(file.name)} (${size}MB)</small>`);
            }
        });

        function buildReview() {
            $('#review_nama').text($('#nama_lokasi').val());
            $('#review_jenis').text($('#jenis_parkir option:selected').text());
            $('#review_luas').text($('#luas_area').val() + ' m²');
            $('#review_alamat').text($('#alamat_lokasi').val());

            let fasilitasHtml = '';
            $('.fasilitas-select').each(function() {
                const nama = $(this).closest('.fasilitas-item').find('span').first().text().trim();
                const ada = $(this).val() === 'ada';
                fasilitasHtml += `
                    <div class="col-md-6">
                        <div class="review-fasilitas">
                            <span>${esc(nama)}</span>
                            <span class="badge badge-${ada ? 'success' : 'secondary'}">${ada ? 'Ada' : 'Tidak Ada'}</span>
                        </div>
                    </div>`;
            });
            $('#review_fasilitas').html(fasilitasHtml || '<div class="col-12 text-muted">Tidak ada fasilitas.</div>');

            let dokumenHtml = '';
            $('.doc-file').each(function() {
                const label = $(this).closest('.doc-upload-item').find('label').text().replace('*', '').trim();
                const hasFile = this.files.length > 0;

                dokumenHtml += `
                    <div class="col-md-6">
                        <div class="review-doc-card ${hasFile ? 'uploaded' : 'missing'}">
                            <div class="doc-icon"><i class="fas ${hasFile ? 'fa-check' : 'fa-times'}"></i></div>
                            <div class="doc-info">
                                <div class="doc-name">${esc(label)}</div>
                                <div class="doc-status ${hasFile ? 'text-success' : 'text-danger'}">
                                    ${hasFile ? esc(this.files[0].name) : 'Belum diupload'}
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            $('#review_dokumen').html(dokumenHtml);
        }

        function submitPermohonan() {
            const formData = new FormData();
            formData.append('nama_lokasi', $('#nama_lokasi').val());
            formData.append('jenis_parkir', $('#jenis_parkir').val());
            formData.append('luas_area', $('#luas_area').val());
            formData.append('alamat_lokasi', $('#alamat_lokasi').val());
            formData.append('_token', '{{ csrf_token() }}');

            $('.fasilitas-select').each(function() {
                formData.append($(this).attr('name'), $(this).val());
            });

            $('.doc-file').each(function() {
                if (this.files[0]) {
                    formData.append(`dokumen[${$(this).data('kode')}]`, this.files[0]);
                }
            });

            $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengajukan...');

            $.ajax({
                url: "{{ route('pemohon.permohonan-parkir.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    setTimeout(() => window.location.href = res.redirect, 1500);
                },
                error: function(xhr) {
                    $('#btnSubmit').prop('disabled', false).html(
                        '<i class="fas fa-paper-plane mr-1"></i> Submit Permohonan');

                    const errors = xhr.responseJSON?.errors;
                    const msg = errors ? Object.values(errors).flat().join('<br>') : xhr.responseJSON?.message;
                    Toast.fire({
                        icon: 'error',
                        title: msg || 'Terjadi kesalahan.'
                    });
                }
            });
        }
    </script>
@endpush
