@extends('layouts.dashboard')

@section('title', 'Kelola Kategori - Admin')
@section('heading', 'Kelola Kategori Campaign')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold text-main mb-1">Daftar Kategori</h1>
        <p class="text-muted small mb-0">Kelola kategori penggalangan dana agar campaign dapat dikelompokkan dengan rapi.</p>
    </div>
    <button type="button" class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
    </button>
</div>

<div class="panel">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Campaign</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $i => $cat)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-bold text-main">{{ $cat->name }}</div>
                        </td>
                        <td class="small text-muted">{{ $cat->description ?? '-' }}</td>
                        <td>
                            <span class="badge bg-soft-sage text-primary-green fw-bold">
                                {{ $cat->campaigns_count }} campaign
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cat->id_category }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($cat->campaigns_count === 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm disabled" title="Tidak dapat dihapus karena masih digunakan campaign">
                                        <i class="bi bi-lock-fill"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Edit Kategori --}}
                    <div class="modal fade" id="editCategoryModal{{ $cat->id_category }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.categories.update', $cat) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Deskripsi Kategori</label>
                                            <textarea name="description" rows="3" class="form-control">{{ $cat->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-teal btn-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Create Kategori --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Lingkungan & Satwa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Kategori</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Penjelasan singkat mengenai kategori ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal btn-sm"><i class="bi bi-plus-lg me-1"></i> Tambah Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
