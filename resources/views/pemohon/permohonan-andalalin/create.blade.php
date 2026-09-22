@extends('layouts.app')
@section('title', 'Ajukan Permohonan Andalalin')

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
            margin: 0 8px;
            margin-bottom: 20px;
            transition: all .3s;
        }

        .step-line.done {
            background: #28a745;
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

        .section-title {
            color: #6777ef;
            font-weight: 700;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #eef0fc;
        }

        .review-summary-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, #6777ef 0%, #8590f2 100%);
            color: #fff;
            border-radius: 12px;
            padding: 20px 24px;
        }

        .review-summary-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .review-summary-text {
            flex: 1;
            min-width: 0;
        }

        .review-summary-text h5 {
            color: #fff;
        }

        .review-summary-text p {
            color: rgba(255, 255, 255, .8) !important;
        }

        .review-summary-badge .badge {
            font-size: 13px;
            padding: 8px 14px;
            border-radius: 20px;
        }

        .review-card {
            background: #f8f9fc;
            border: 1px solid #e3e8f1;
            border-radius: 10px;
            padding: 14px 16px;
            height: 100%;
        }

        .review-card-label {
            font-size: .78rem;
            color: #98a6ad;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 6px;
        }

        .review-card-value {
            font-weight: 600;
            color: #363636;
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
            color: #363636;
        }

        .review-doc-card .doc-status {
            font-size: .76rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ajukan Permohonan Andalalin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('pemohon.permohonan-andalalin.index') }}">Permohonan Andalalin</a>
                    </div>
                    <div class="breadcrumb-item">Buat Baru</div>
                </div>
            </div>

            <div class="section-body">
                <div class="step-indicator">
                    <div class="step-item">
                        <div class="step-circle active" id="circle-1">1</div>
                        <div class="step-label active" id="label-1">Data Kegiatan</div>
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

                {{-- STEP 1: Data Kegiatan --}}
                <div id="step-1" class="card">
                    <div class="card-header">
                        <h4>Step 1 — Data Kegiatan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select id="jenis_kegiatan" class="form-control">
                                        <option value="">-- Pilih Jenis Kegiatan --</option>
                                        @foreach ($jenisKegiatanList as $jk)
                                            <option value="{{ $jk }}">{{ $jk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parameter <span class="text-danger">*</span></label>
                                    <select id="parameter" class="form-control" disabled>
                                        <option value="">-- Pilih Parameter --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nilai Parameter (Sesuai Kondisi Proyek Anda) <span
                                            class="text-danger">*</span></label>
                                    <select id="nilai_parameter" class="form-control" disabled>
                                        <option value="">-- Pilih Rentang Sesuai Kondisi --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori Bangkitan</label>
                                    <div>
                                        <span id="kategori_badge" class="badge badge-secondary"
                                            style="font-size: 14px; padding: 8px 12px;">
                                            Belum Dihitung
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Proyek <span class="text-danger">*</span></label>
                            <textarea id="alamat_proyek" class="form-control" rows="3"
                                placeholder="Jl. Nama Jalan No. XX, Kelurahan, Kecamatan, Kota"></textarea>
                        </div>

                        <div class="alert alert-info" style="font-size:.85rem;">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Kategori bangkitan akan otomatis muncul setelah Anda memilih rentang nilai parameter yang
                            sesuai.
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary" onclick="keStep(2)">
                            Lanjut <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Dokumen Persyaratan --}}
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
                        <button type="button" class="btn btn-primary" onclick="keStep(3)">
                            Lanjut <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: Review & Submit --}}
                <div id="step-3" class="card d-none">
                    <div class="card-header">
                        <h4>Step 3 — Review & Submit</h4>
                    </div>
                    <div class="card-body">

                        {{-- Summary Header --}}
                        <div class="review-summary-card mb-4">
                            <div class="review-summary-icon">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <div class="review-summary-text">
                                <h5 class="mb-1" id="review_jenis">-</h5>
                                <p class="mb-0 text-muted" id="review_parameter">-</p>
                            </div>
                            <div class="review-summary-badge" id="review_kategori"></div>
                        </div>

                        {{-- Data Kegiatan --}}
                        <h6 class="section-title"><i class="fas fa-clipboard-list mr-1"></i> Detail Kegiatan</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="review-card">
                                    <div class="review-card-label"><i class="fas fa-ruler-combined mr-1"></i> Nilai
                                        Parameter</div>
                                    <div class="review-card-value" id="review_nilai">-</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="review-card">
                                    <div class="review-card-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat
                                        Proyek</div>
                                    <div class="review-card-value" id="review_alamat">-</div>
                                </div>
                            </div>
                        </div>

                        {{-- Dokumen --}}
                        <h6 class="section-title"><i class="fas fa-file-alt mr-1"></i> Dokumen Persyaratan</h6>
                        <div class="row" id="review_dokumen"></div>

                        <div class="alert alert-warning mt-4 mb-0">
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

        // Cascading: Jenis Kegiatan -> Parameter
        $('#jenis_kegiatan').change(function() {
            const jenis = $(this).val();
            $('#parameter').html('<option value="">-- Pilih Parameter --</option>').prop('disabled', true);
            $('#nilai_parameter').html('<option value="">-- Pilih Rentang Sesuai Kondisi --</option>').prop(
                'disabled', true);
            resetKategori();
            if (!jenis) return;

            $.get("{{ route('pemohon.permohonan-andalalin.get-parameters') }}", {
                jenis_kegiatan: jenis
            }, function(res) {
                if (!res.status) return;
                let html = '<option value="">-- Pilih Parameter --</option>';
                res.data.forEach(p => html += `<option value="${p}">${p}</option>`);
                $('#parameter').html(html).prop('disabled', false);
            });
        });

        // Cascading: Parameter -> Nilai Parameter (rentang + kategori)
        $('#parameter').change(function() {
            const jenis = $('#jenis_kegiatan').val();
            const parameter = $(this).val();
            $('#nilai_parameter').html('<option value="">-- Pilih Rentang Sesuai Kondisi --</option>').prop(
                'disabled', true);
            resetKategori();
            if (!parameter) return;

            $.get("{{ route('pemohon.permohonan-andalalin.get-rentang-nilai') }}", {
                jenis_kegiatan: jenis,
                parameter
            }, function(res) {
                if (!res.status) return;
                let html = '<option value="">-- Pilih Rentang Sesuai Kondisi --</option>';
                res.data.forEach(r => {
                    html +=
                        `<option value="${r.id}" data-kategori="${r.kategori}" data-label="${r.label}">${r.label}</option>`;
                });
                $('#nilai_parameter').html(html).prop('disabled', false);
            });
        });

        // Pilih rentang -> tampilkan kategori
        $('#nilai_parameter').change(function() {
            const opt = $(this).find('option:selected');
            const kategori = opt.data('kategori');
            if (!kategori) return resetKategori();

            const badgeClass = {
                rendah: 'success',
                sedang: 'warning',
                tinggi: 'danger'
            } [kategori];
            $('#kategori_badge').removeClass('badge-secondary badge-success badge-warning badge-danger')
                .addClass(`badge-${badgeClass}`)
                .text(kategori.charAt(0).toUpperCase() + kategori.slice(1));
        });

        function resetKategori() {
            $('#kategori_badge').removeClass('badge-success badge-warning badge-danger')
                .addClass('badge-secondary').text('Belum Dihitung');
        }

        $('.doc-file').change(function() {
            const kode = $(this).data('kode');
            const el = $(`.file-status-${kode}`);
            if (this.files[0]) {
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);
                el.html(
                    `<small class="text-success"><i class="fas fa-check-circle"></i> ${this.files[0].name} (${size}MB)</small>`
                );
            } else {
                el.html('');
            }
        });

        function buildReview() {
            $('#review_jenis').text($('#jenis_kegiatan').val());
            $('#review_parameter').text($('#parameter').val());
            $('#review_nilai').text($('#nilai_parameter option:selected').data('label') || '-');
            $('#review_alamat').text($('#alamat_proyek').val());

            const kategori = $('#kategori_badge').text().trim();
            const kategoriClass = $('#kategori_badge').attr('class').match(/badge-\S+/)?.[0] || 'badge-secondary';
            $('#review_kategori').html(`<span class="badge ${kategoriClass}">${kategori}</span>`);

            let dokHtml = '';
            $('.doc-file').each(function() {
                const label = $(this).closest('.doc-upload-item').find('label').text().replace('*', '').trim();
                const hasFile = this.files.length > 0;

                dokHtml += `
            <div class="col-md-6">
                <div class="review-doc-card ${hasFile ? 'uploaded' : 'missing'}">
                    <div class="doc-icon">
                        <i class="fas ${hasFile ? 'fa-check' : 'fa-times'}"></i>
                    </div>
                    <div class="doc-info">
                        <div class="doc-name">${label}</div>
                        <div class="doc-status ${hasFile ? 'text-success' : 'text-danger'}">
                            ${hasFile ? this.files[0].name : 'Belum diupload'}
                        </div>
                    </div>
                </div>
            </div>`;
            });
            $('#review_dokumen').html(dokHtml);
        }

        function validasiStep1() {
            const jenisKegiatan = $('#jenis_kegiatan').val();
            const parameter = $('#parameter').val();
            const nilaiParameter = $('#nilai_parameter').val();
            const alamatProyek = $('#alamat_proyek').val().trim();

            if (!jenisKegiatan) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih Jenis Kegiatan terlebih dahulu.'
                });
                return;
            }
            if (!parameter) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih Parameter terlebih dahulu.'
                });
                return;
            }
            if (!nilaiParameter) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih Nilai Parameter sesuai kondisi proyek Anda.'
                });
                return;
            }
            if (!alamatProyek) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Alamat Proyek wajib diisi.'
                });
                return;
            }

            keStep(2);
        }

        function validasiStep2() {
            let dokumenBelumLengkap = [];

            $('.doc-file').each(function() {
                const isWajib = $(this).closest('.doc-upload-item').find('label .text-danger').length > 0;
                if (isWajib && !this.files.length) {
                    const label = $(this).closest('.doc-upload-item').find('label').text().replace('*', '').trim();
                    dokumenBelumLengkap.push(label);
                }
            });

            if (dokumenBelumLengkap.length > 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Dokumen belum lengkap: ' + dokumenBelumLengkap.join(', ')
                });
                return;
            }

            keStep(3);
        }

        function submitPermohonan() {
            if (!$('#nilai_parameter').val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Lengkapi data kegiatan terlebih dahulu.'
                });
                return keStep(1);
            }

            const formData = new FormData();
            formData.append('kriteria_andalalin_id', $('#nilai_parameter').val());
            formData.append('alamat_proyek', $('#alamat_proyek').val());
            formData.append('_token', '{{ csrf_token() }}');

            $('.doc-file').each(function() {
                const kode = $(this).data('kode');
                if (this.files[0]) formData.append(`dokumen[${kode}]`, this.files[0]);
            });

            $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengajukan...');

            $.ajax({
                url: "{{ route('pemohon.permohonan-andalalin.store') }}",
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
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        const msg = errors ? Object.values(errors).flat().join('<br>') : xhr.responseJSON
                            .message;
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
        }
    </script>
@endpush
