@extends('layouts.auth')
@section('title', 'Daftar Akun Pemohon')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <style>
        .card-primary {
            border-top: 4px solid #6777ef;
        }

        .password-toggle {
            cursor: pointer;
        }

        .register-card {
            max-width: 760px;
        }
    </style>
@endpush

@section('main')
    <div class="card card-primary register-card mx-auto">
        <div class="card-header">
            <h4>Daftar Akun Pemohon</h4>
        </div>
        <div class="card-body">

            <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="needs-validation" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" tabindex="1"
                                placeholder="Nama sesuai identitas" required autofocus>
                            <div class="invalid-feedback">
                                Nama lengkap wajib diisi.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" tabindex="2" placeholder="contoh@email.com"
                                required>
                            <div class="invalid-feedback">
                                Masukkan alamat email yang valid.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    tabindex="3" placeholder="Minimal 8 karakter" required>
                                <div class="input-group-append">
                                    <span class="input-group-text password-toggle" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">
                                    Password minimal 8 karakter.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-group">
                                <input id="password_confirmation" type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" tabindex="4" placeholder="Ulangi password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text password-toggle" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">
                                    Konfirmasi password tidak cocok.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="5">
                        <i class="fas fa-user-plus mr-1"></i> Daftar
                    </button>
                </div>

                <div class="text-center mt-3" style="font-size:.85rem;">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            'use strict';

            // Toggle show/hide password — didaftarkan PALING AWAL,
            // supaya tetap jalan meski ada error di kode lain di bawahnya
            document.querySelectorAll('.password-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    var targetId = this.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    var icon = this.querySelector('i');

                    if (input.getAttribute('type') === 'password') {
                        input.setAttribute('type', 'text');
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.setAttribute('type', 'password');
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Bootstrap client-side validation
            var form = document.getElementById('registerForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            // Toast — dibungkus guard, kalau Swal belum ke-load gak akan
            // menghentikan script lain
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                @if ($errors->any())
                    Toast.fire({
                        icon: 'error',
                        title: @json($errors->first())
                    });
                @endif

                @if (session('toast_success'))
                    Toast.fire({
                        icon: 'success',
                        title: @json(session('toast_success'))
                    });
                @endif
            } else {
                console.warn('SweetAlert2 (Swal) belum ter-load di halaman ini.');
            }
        });
    </script>
@endpush
