@extends('layouts.app')

@section('title', $judul)

@section('content')
<div class="container py-5" style="max-width: 640px;">
    <div class="panel panel-pad text-center py-5">
        <i class="bi bi-tools fs-1" style="color: var(--teal)" aria-hidden="true"></i>
        <h1 class="h3 mt-3">{{ $judul }}</h1>
        <p class="text-muted mb-4">Halaman ini sedang dikerjakan dan akan tersedia pada tahap berikutnya.</p>
        <a href="{{ route('home') }}" class="btn btn-teal">Kembali ke beranda</a>
    </div>
</div>
@endsection
