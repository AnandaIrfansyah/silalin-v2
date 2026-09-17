@extends('layouts.auth')
@section('title', 'Daftar Akun Pemohon')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <style>
        .auth-brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .auth-brand img {
            height: 52px;
            margin-bottom: .75rem;
        }

        .auth-brand h4 {
            font-weight: 700;
            color: #6777ef;
            font-size: 1.1rem;
            margin-bottom: .25rem;
        }

        .auth-brand p {
            font-size: .8rem;
            color: #98a6ad;
            margin: 0;
        }

        .card-primary {
            border-top: 4px solid #6777ef;
        }

        .password-toggle {
            cursor: pointer;
        }
    </style>
@endpush

@section('main')
    <div class="auth-brand">
        <img src="{{ asset('img/logo.png') }}" alt="SILALIN" onerror="this.style.display='none'">
        <h4>SILALIN</h4>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4>Daftar Akun Pemohon</h4>
        </div>
        <div class="card-body">

            <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="needs-validation"
                novalidate>
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" tabindex="1" placeholder="Nama sesuai identitas"
                        required autofocus>
                    <div class="invalid-feedback">
                        Nama lengkap wajib diisi.
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" tabindex="2" placeholder="contoh@email.com" required>
                    <div class="invalid-feedback">
                        Masukkan alamat email yang valid.
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" tabindex="3" placeholder="Minimal 8 karakter" required>
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

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-group">
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                            tabindex="4" placeholder="Ulangi password" required>
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
        (function() {
            'use strict';

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
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

            // Toggle show/hide password
            document.querySelectorAll('.password-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    var targetId = this.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    var icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });

            // Toast untuk error validasi dari server
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: @json($errors->first())
                });
            @endif

            // Toast sukses (jika ada, misal redirect dari proses lain)
            @if (session('toast_success'))
                Toast.fire({
                    icon: 'success',
                    title: @json(session('toast_success'))
                });
            @endif
        })();
    </script>
@endpush
