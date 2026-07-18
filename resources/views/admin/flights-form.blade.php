@extends('layouts.admin')

@section('title', isset($flight) ? 'Edit Penerbangan' : 'Tambah Penerbangan')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link active"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-{{ isset($flight) ? 'edit' : 'plus' }} me-2"></i>{{ isset($flight) ? 'Edit Penerbangan' : 'Tambah Penerbangan' }}</h2>
    <p>{{ isset($flight) ? 'Edit data penerbangan' : 'Buat jadwal penerbangan baru' }}</p>
</div>

<div class="card content-card">
    <div class="card-body p-4">
        <form action="{{ isset($flight) ? route('admin.flights.update', $flight->id) : route('admin.flights.store') }}" method="POST">
            @csrf
            @if(isset($flight)) @method('PUT') @endif

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Maskapai <span class="text-danger">*</span></label>
                    <select name="airline_id" class="form-select @error('airline_id') is-invalid @enderror" required>
                        <option value="">Pilih Maskapai</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}" {{ old('airline_id', $flight->airline_id ?? '') == $airline->id ? 'selected' : '' }}>{{ $airline->name }} ({{ $airline->code }})</option>
                        @endforeach
                    </select>
                    @error('airline_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pesawat <span class="text-danger">*</span></label>
                    <select name="airplane_id" class="form-select @error('airplane_id') is-invalid @enderror" required>
                        <option value="">Pilih Pesawat</option>
                        @foreach($airplanes as $airplane)
                            <option value="{{ $airplane->id }}" {{ old('airplane_id', $flight->airplane_id ?? '') == $airplane->id ? 'selected' : '' }}>{{ $airplane->model }} ({{ $airplane->airline->name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('airplane_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Bandara Asal <span class="text-danger">*</span></label>
                    <select name="departure_airport_id" class="form-select @error('departure_airport_id') is-invalid @enderror" required>
                        <option value="">Pilih Bandara Asal</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" {{ old('departure_airport_id', $flight->departure_airport_id ?? '') == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->code }})</option>
                        @endforeach
                    </select>
                    @error('departure_airport_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Bandara Tujuan <span class="text-danger">*</span></label>
                    <select name="arrival_airport_id" class="form-select @error('arrival_airport_id') is-invalid @enderror" required>
                        <option value="">Pilih Bandara Tujuan</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" {{ old('arrival_airport_id', $flight->arrival_airport_id ?? '') == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->code }})</option>
                        @endforeach
                    </select>
                    @error('arrival_airport_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Waktu Keberangkatan <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="departure_time" class="form-control @error('departure_time') is-invalid @enderror"
                           value="{{ old('departure_time', isset($flight) ? $flight->departure_time->format('Y-m-d\TH:i') : '') }}" required>
                    @error('departure_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Waktu Kedatangan <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="arrival_time" class="form-control @error('arrival_time') is-invalid @enderror"
                           value="{{ old('arrival_time', isset($flight) ? $flight->arrival_time->format('Y-m-d\TH:i') : '') }}" required>
                    @error('arrival_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kelas Penerbangan <span class="text-danger">*</span></label>
                    <select name="flight_class" class="form-select @error('flight_class') is-invalid @enderror" required>
                        <option value="">Pilih Kelas</option>
                        <option value="economy" {{ old('flight_class', $flight->flight_class ?? '') == 'economy' ? 'selected' : '' }}>Economy Class</option>
                        <option value="business" {{ old('flight_class', $flight->flight_class ?? '') == 'business' ? 'selected' : '' }}>Business Class</option>
                        <option value="first" {{ old('flight_class', $flight->flight_class ?? '') == 'first' ? 'selected' : '' }}>First Class</option>
                    </select>
                    @error('flight_class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Tiket <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $flight->price ?? '') }}" required min="0">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Kursi <span class="text-danger">*</span></label>
                    <input type="number" name="total_seats" class="form-control @error('total_seats') is-invalid @enderror"
                           value="{{ old('total_seats', $flight->total_seats ?? '') }}" required min="1">
                    @error('total_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kursi Tersedia <span class="text-danger">*</span></label>
                    <input type="number" name="available_seats" class="form-control @error('available_seats') is-invalid @enderror"
                           value="{{ old('available_seats', $flight->available_seats ?? '') }}" required min="1">
                    @error('available_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="scheduled" {{ old('status', $flight->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="on_time" {{ old('status', $flight->status ?? '') == 'on_time' ? 'selected' : '' }}>On Time</option>
                        <option value="delayed" {{ old('status', $flight->status ?? '') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="cancelled" {{ old('status', $flight->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-gold-custom">
                    <i class="fas fa-save me-1"></i>{{ isset($flight) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.flights') }}" class="btn btn-outline-custom">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
