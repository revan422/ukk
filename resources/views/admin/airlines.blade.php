@extends('layouts.admin')

@section('title', 'Kelola Maskapai')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link active"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-building me-2"></i>Kelola Maskapai</h2>
        <p>Manajemen data maskapai penerbangan partner</p>
    </div>
    <button class="btn btn-gold-custom" data-bs-toggle="modal" data-bs-target="#modalForm">
        <i class="fas fa-plus me-1"></i>Tambah Maskapai
    </button>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr><th>No</th><th>Nama</th><th>Kode</th><th>Deskripsi</th><th>Pesawat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($airlines as $index => $airline)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $airline->name }}</strong></td>
                        <td><span class="badge bg-dark">{{ $airline->code }}</span></td>
                        <td>{{ Str::limit($airline->description ?? '-', 50) }}</td>
                        <td><span class="badge bg-info">{{ $airline->airplanes_count }} Pesawat</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalForm{{ $airline->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.airlines.delete', $airline->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data maskapai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade modal-custom" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.airlines.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Maskapai</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Maskapai</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="code" class="form-control" maxlength="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit foreach -->
@foreach($airlines as $airline)
<div class="modal fade modal-custom" id="modalForm{{ $airline->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.airlines.update', $airline->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Maskapai</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Maskapai</label>
                        <input type="text" name="name" class="form-control" value="{{ $airline->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="code" class="form-control" value="{{ $airline->code }}" maxlength="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $airline->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($airline->logo)<small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo</small>@endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold-custom">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
