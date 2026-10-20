@extends('layouts.app')

@section('title', 'Masuk - Impactly')

@section('content')
<div class="auth-wrap">
    <div class="container">
        <div class="auth-card">
            <h1 class="h3 fw-bold text-main mb-1">Masuk ke Impactly</h1>
            <p class="text-muted small mb-4">Masuk untuk berdonasi atau mengelola campaign Anda.</p>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" autofocus required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label text-muted small" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-teal w-100 py-2 fs-6">Masuk</button>
            </form>

            <p class="text-center text-muted small mt-4 mb-0">
                Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-primary-green">Daftar Akun Baru</a>
            </p>
        </div>
    </div>
</div>
@endsection
