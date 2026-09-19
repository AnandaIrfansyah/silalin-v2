@extends('layouts.app')
@section('title', 'Edit Data Perusahaan')

@push('style')
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section h5 {
            color: #6777ef;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6777ef;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Data Perusahaan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Profil Perusahaan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Data Perusahaan</h4>
                    </div>
                    <form id="companyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <div class="form-section">
                                <h5><i class="fas fa-building mr-1"></i> Data Perusahaan</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Perusahaan <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_perusahaan" class="form-control"
                                                value="{{ $company->nama_perusahaan }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bentuk Badan Usaha <span class="text-danger">*</span></label>
                                            <select name="bentuk_badan_usaha" class="form-control" required>
                                                @foreach (['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Perorangan', 'Lainnya'] as $b)
                                                    <option value="{{ $b }}"
                                                        {{ $company->bentuk_badan_usaha === $b ? 'selected' : '' }}>
                                                        {{ $b }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NPWP Perusahaan <span class="text-danger">*</span></label>
                                            <input type="text" name="npwp_perusahaan" id="npwp" class="form-control"
                                                value="{{ $company->npwp_perusahaan }}" maxlength="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NIB (Nomor Induk Berusaha) <span class="text-danger">*</span></label>
                                            <input type="text" name="nib" id="nib" class="form-control"
                                                value="{{ $company->nib }}" maxlength="15" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telepon Perusahaan <span class="text-danger">*</span></label>
                                            <input type="text" name="telepon_perusahaan" class="form-control"
                                                value="{{ $company->telepon_perusahaan }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email Perusahaan</label>
                                            <input type="email" name="email_perusahaan" class="form-control"
                                                value="{{ $company->email_perusahaan }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Perusahaan <span class="text-danger">*</span></label>
                                    <textarea name="alamat_perusahaan" class="form-control" rows="3" required>{{ $company->alamat_perusahaan }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Akta Pendirian Perusahaan</label>
                                    <input type="file" name="akta_pendirian" class="form-control" accept=".pdf">
                                    <small class="text-muted">Format: PDF, Maksimal 2MB</small>
                                    @if ($company->akta_pendirian)
                                        <small class="text-success d-block mt-1"><i class="fas fa-check"></i> File sudah
                                            diunggah — kosongkan jika tidak ingin mengganti.</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-section">
                                <h5><i class="fas fa-user-tie mr-1"></i> Data Penanggung Jawab</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Penanggung Jawab <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_penanggung_jawab" class="form-control"
                                                value="{{ $company->nama_penanggung_jawab }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan <span class="text-danger">*</span></label>
                                            <input type="text" name="jabatan_penanggung_jawab" class="form-control"
                                                value="{{ $company->jabatan_penanggung_jawab }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telepon <span class="text-danger">*</span></label>
                                            <input type="text" name="telepon_penanggung_jawab" class="form-control"
                                                value="{{ $company->telepon_penanggung_jawab }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>KTP Penanggung Jawab</label>
                                            <input type="file" name="ktp_penanggung_jawab" class="form-control"
                                                accept=".pdf,.jpg,.jpeg,.png">
                                            <small class="text-muted">Format: PDF/JPG/PNG, Maksimal 2MB</small>
                                            @if ($company->ktp_penanggung_jawab)
                                                <small class="text-success d-block mt-1"><i class="fas fa-check"></i> File
                                                    sudah diunggah — kosongkan jika tidak ingin mengganti.</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5><i class="fas fa-file-signature mr-1"></i> Surat Kuasa <small
                                        class="text-muted">(Opsional)</small></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kuasa</label>
                                            <input type="text" name="nama_kuasa" class="form-control"
                                                value="{{ $company->nama_kuasa }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan Kuasa</label>
                                            <input type="text" name="jabatan_kuasa" class="form-control"
                                                value="{{ $company->jabatan_kuasa }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>File Surat Kuasa</label>
                                    <input type="file" name="surat_kuasa" class="form-control" accept=".pdf">
                                    <small class="text-muted">Format: PDF, Maksimal 2MB</small>
                                    @if ($company->surat_kuasa)
                                        <small class="text-success d-block mt-1"><i class="fas fa-check"></i> File sudah
                                            diunggah — kosongkan jika tidak ingin mengganti.</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('pemohon.company.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSimpan">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
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

        $('#npwp').on('input', function() {
            let val = $(this).val().replace(/\D/g, '').substring(0, 15);
            if (val.length >= 2) val = val.substring(0, 2) + '.' + val.substring(2);
            if (val.length >= 6) val = val.substring(0, 6) + '.' + val.substring(6);
            if (val.length >= 10) val = val.substring(0, 10) + '.' + val.substring(10);
            if (val.length >= 12) val = val.substring(0, 12) + '-' + val.substring(12);
            if (val.length >= 16) val = val.substring(0, 16) + '.' + val.substring(16);
            $(this).val(val);
        });

        $('#nib').on('input', function() {
            let val = $(this).val().replace(/\D/g, '').substring(0, 13);
            if (val.length >= 4) val = val.substring(0, 4) + '-' + val.substring(4);
            if (val.length >= 9) val = val.substring(0, 9) + '-' + val.substring(9);
            $(this).val(val);
        });

        $('#companyForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'POST');

            $('#btnSimpan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: "{{ route('pemohon.company.update') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    setTimeout(() => {
                        window.location.href = "{{ route('pemohon.company.index') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    $('#btnSimpan').prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i> Simpan Perubahan');
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
    </script>
@endpush
