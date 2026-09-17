@extends('layouts.auth')
@section('title', 'Login')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <style>
        .card-primary {
            border-top: 4px solid #6777ef;
        }

        .alert-validation {
            font-size: .85rem;
        }
    </style>
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Masuk ke Sistem</h4>
        </div>
        <div class="card-body">

            <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="needs-validation" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" tabindex="1" placeholder="contoh@email.com" required
                        autofocus>
                    <div class="invalid-feedback">
                        Masukkan alamat email yang valid.
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" tabindex="2" placeholder="••••••••" required>
                    <div class="invalid-feedback">
                        Masukkan password Anda.
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </button>
                </div>

            </form>

            <div class="text-center mt-3" style="font-size:.85rem;">
                Belum punya akun pemohon? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
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

            var form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: @json($errors->first())
                });
            @endif

            @if (session('status'))
                Toast.fire({
                    icon: 'success',
                    title: @json(session('status'))
                });
            @endif

            @if (session('toast_success'))
                Toast.fire({
                    icon: 'success',
                    title: @json(session('toast_success'))
                });
            @endif
        })();
    </script>
@endpush
