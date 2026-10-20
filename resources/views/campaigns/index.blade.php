@extends('layouts.app')

@section('title', 'Campaign')

@section('content')
<div class="container py-5">
    <h1 class="h2 mb-1">Campaign donasi</h1>
    <p class="text-muted mb-4">Cari dan pilih campaign yang ingin Anda dukung.</p>

    <form method="GET" action="{{ route('campaigns.index') }}" class="panel panel-pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label for="q" class="form-label">Cari campaign</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Contoh: renovasi, beasiswa, banjir">
            </div>
            <div class="col-md-6 col-lg-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select id="kategori" name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_category }}" @selected((string) $filters['kategori'] === (string) $cat->id_category)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <label for="urut" class="form-label">Urutkan</label>
                <select id="urut" name="urut" class="form-select">
                    <option value="terbaru" @selected($filters['urut'] === 'terbaru')>Terbaru</option>
                    <option value="berakhir" @selected($filters['urut'] === 'berakhir')>Hampir berakhir</option>
                    <option value="terbanyak" @selected($filters['urut'] === 'terbanyak')>Dana terbanyak</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-teal flex-grow-1">Cari</button>
                <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary" title="Atur ulang" aria-label="Atur ulang filter"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i></a>
            </div>
        </div>
    </form>

    @if($campaigns->isEmpty())
        <div class="panel panel-pad text-center py-5">
            <i class="bi bi-search fs-1 text-muted" aria-hidden="true"></i>
            <h2 class="h5 mt-3">Campaign tidak ditemukan</h2>
            <p class="text-muted mb-3">Coba kata kunci lain atau hapus filter kategori.</p>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-teal">Tampilkan semua campaign</a>
        </div>
    @else
        <div class="text-muted small mb-3">Menampilkan {{ $campaigns->firstItem() }}-{{ $campaigns->lastItem() }} dari {{ $campaigns->total() }} campaign</div>
        <div class="row g-4">
            @foreach($campaigns as $campaign)
                <div class="col-md-6 col-lg-4">
                    @include('partials.campaign-card', ['campaign' => $campaign])
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            {{ $campaigns->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
