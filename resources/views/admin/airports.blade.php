@extends('layouts.admin')

@section('title', 'Kelola Bandara')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link active"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-map-marker-alt me-2"></i>Kelola Bandara</h2>
        <p>Manajemen data bandara</p>
    </div>
    <button class="btn btn-gold-custom" data-bs-toggle="modal" data-bs-target="#modalForm">
        <i class="fas fa-plus me-1"></i>Tambah Bandara
    </button>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr><th>No</th><th>Nama</th><th>Kode</th><th>Kota</th><th>Negara</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($airports as $index => $airport)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $airport->name }}</strong></td>
                        <td><span class="badge bg-dark">{{ $airport->code }}</span></td>
                        <td>{{ $airport->city }}</td>
                        <td>{{ $airport->country }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalForm{{ $airport->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.airports.delete', $airport->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data bandara.</td></tr>
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
            <form action="{{ route('admin.airports.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Bandara</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Bandara</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kode IATA</label><input type="text" name="code" class="form-control" maxlength="5" required></div>
                    <div class="mb-3"><label class="form-label">Kota</label><input type="text" name="city" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Negara</label><input type="text" name="country" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($airports as $airport)
<div class="modal fade modal-custom" id="modalForm{{ $airport->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.airports.update', $airport->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Bandara</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Bandara</label><input type="text" name="name" class="form-control" value="{{ $airport->name }}" required></div>
                    <div class="mb-3"><label class="form-label">Kode IATA</label><input type="text" name="code" class="form-control" value="{{ $airport->code }}" maxlength="5" required></div>
                    <div class="mb-3"><label class="form-label">Kota</label><input type="text" name="city" class="form-control" value="{{ $airport->city }}" required></div>
                    <div class="mb-3"><label class="form-label">Negara</label><input type="text" name="country" class="form-control" value="{{ $airport->country }}" required></div>
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
