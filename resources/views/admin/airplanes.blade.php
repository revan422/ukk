@extends('layouts.admin')

@section('title', 'Kelola Pesawat')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link active"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-subway me-2"></i>Kelola Pesawat</h2>
        <p>Manajemen data pesawat dan kursi</p>
    </div>
    <button class="btn btn-gold-custom" data-bs-toggle="modal" data-bs-target="#modalForm">
        <i class="fas fa-plus me-1"></i>Tambah Pesawat
    </button>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr><th>No</th><th>Model</th><th>Maskapai</th><th>Kapasitas</th><th>Registrasi</th><th>Kursi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($airplanes as $index => $airplane)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $airplane->model }}</strong></td>
                        <td>{{ $airplane->airline->name ?? '-' }}</td>
                        <td>{{ $airplane->capacity }} Kursi</td>
                        <td>{{ $airplane->registration_number ?? '-' }}</td>
                        <td><a href="#" class="btn btn-sm btn-info">Lihat Kursi</a></td>
                        <td>
                            <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalForm{{ $airplane->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.airplanes.delete', $airplane->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin? Semua kursi akan terhapus!')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pesawat.</td></tr>
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
            <form action="{{ route('admin.airplanes.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Pesawat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Maskapai</label>
                        <select name="airline_id" class="form-select" required>
                            <option value="">Pilih Maskapai</option>
                            @foreach(\App\Models\Airline::all() as $al)
                                <option value="{{ $al->id }}">{{ $al->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Model Pesawat</label><input type="text" name="model" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kapasitas</label><input type="number" name="capacity" class="form-control" min="1" required></div>
                    <div class="mb-3"><label class="form-label">No. Registrasi</label><input type="text" name="registration_number" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($airplanes as $airplane)
<div class="modal fade modal-custom" id="modalForm{{ $airplane->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.airplanes.update', $airplane->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Pesawat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Maskapai</label>
                        <select name="airline_id" class="form-select" required>
                            @foreach(\App\Models\Airline::all() as $al)
                                <option value="{{ $al->id }}" {{ $airplane->airline_id == $al->id ? 'selected' : '' }}>{{ $al->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Model Pesawat</label><input type="text" name="model" class="form-control" value="{{ $airplane->model }}" required></div>
                    <div class="mb-3"><label class="form-label">Kapasitas</label><input type="number" name="capacity" class="form-control" value="{{ $airplane->capacity }}" min="1" required></div>
                    <div class="mb-3"><label class="form-label">No. Registrasi</label><input type="text" name="registration_number" class="form-control" value="{{ $airplane->registration_number }}"></div>
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
