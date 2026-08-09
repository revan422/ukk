<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kursi - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            color: #333;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
        }
        .main-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            margin: 30px auto;
            max-width: 800px;
        }
        .flight-info {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #11998e;
        }
        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .legend-box {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 2px solid #ddd;
        }
        .legend-box.available {
            background: #fff;
            border-color: #28a745;
        }
        .legend-box.selected {
            background: #007bff;
            border-color: #007bff;
        }
        .legend-box.occupied {
            background: #ff477e;
            border-color: #ff477e;
        }
        .seat-map {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .seat-header {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .seat-column-header {
            width: 50px;
            text-align: center;
            font-weight: 600;
            color: #555;
            margin: 0 5px;
        }
        .seat-row {
            display: flex;
            align-items: center;
            margin: 10px 0;
            justify-content: center;
        }
        .seat-row-number {
            width: 30px;
            font-weight: 600;
            color: #555;
            margin-right: 20px;
        }
        .seat {
            width: 50px;
            height: 45px;
            margin: 0 5px;
            border-radius: 8px;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
            background: #fff;
        }
        .seat:hover:not(.occupied) {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-color: #007bff;
        }
        .seat.available {
            background: #fff;
            border-color: #28a745;
            color: #28a745;
        }
        .seat.available:hover {
            background: #28a745;
            color: #fff;
        }
        .seat.selected {
            background: #007bff !important;
            border-color: #007bff !important;
            color: #fff !important;
        }
        .seat.occupied {
            background: #ff477e;
            border-color: #ff477e;
            color: #fff;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .seat-gap {
            width: 30px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            font-weight: 700;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
        }
        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(244, 180, 0, 0.4);
        }
        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .selected-info {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #f4b400;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .selected-info h5 {
            margin: 0;
            color: #0a192f;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Pilih Kursi</span>
        </div>
    </nav>

    <div class="container">
        <div class="main-card">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-3" style="color: #0a192f;">Pilih Kursi Anda</h3>
                
                <!-- Tampilkan Pesan Error/Gagal jika Ada -->
                @if(session('error'))
                    <div class="alert alert-danger mb-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-muted mb-4">{{ $flight->airline->name ?? 'Maskapai' }} - {{ $flight->flight_number }}</p>

                <!-- Flight Details -->
                <div class="flight-info mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Detail Penerbangan:</h6>
                            <p class="mb-1"><strong>Dari:</strong> {{ $flight->departureAirport->name ?? '-' }} ({{ $flight->departureAirport->code ?? '-' }})</p>
                            <p class="mb-1"><strong>Ke:</strong> {{ $flight->arrivalAirport->name ?? '-' }} ({{ $flight->arrivalAirport->code ?? '-' }})</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y') }}</p>
                            <p class="mb-0"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Keterangan Kursi:</h6>
                            <div class="legend">
                                <div class="legend-item">
                                    <div class="legend-box available"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box selected"></div>
                                    <span>Dipilih</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box occupied"></div>
                                    <span>Terisi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seat Map -->
                <div class="seat-map">
                    <h5 class="text-center mb-4 fw-bold">Denah Kursi</h5>

                    <!-- Column Headers -->
                    <div class="seat-header">
                        <div style="width: 30px;"></div>
                        <div class="seat-column-header">A</div>
                        <div class="seat-column-header">B</div>
                        <div class="seat-column-header">C</div>
                        <div class="seat-gap"></div>
                        <div class="seat-column-header">D</div>
                        <div class="seat-column-header">E</div>
                        <div class="seat-column-header">F</div>
                    </div>

                    <!-- Seats Grid -->
                    @php
                        $rows = range(1, 12);
                        $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
                        $selectedSeat = session('selected_seat');
                    @endphp

                    @foreach($rows as $row)
                        <div class="seat-row">
                            <div class="seat-row-number">{{ $row }}</div>
                            @foreach($columns as $index => $column)
                                @php
                                    $seatNumber = $row . $column;
                                    $seat = isset($seats) ? $seats->firstWhere('seat_number', $seatNumber) : null;
                                    
                                    // Cek ketersediaan
                                    if ($seat) {
                                        $isOccupied = ($seat->status === 'booked' || (isset($seat->is_available) && !$seat->is_available));
                                    } else {
                                        $isOccupied = false; 
                                    }
                                    
                                    $isSelected = ($selectedSeat == $seatNumber);
                                    $seatClass = $isOccupied ? 'occupied' : ($isSelected ? 'selected' : 'available');
                                    $seatPrice = $seat->price ?? $flight->price ?? 0;
                                    $seatId = $seat->id ?? 0;
                                @endphp

                                @if($index == 3)
                                    <div class="seat-gap"></div>
                                @endif

                                <div class="seat {{ $seatClass }}"
                                     data-seat="{{ $seatNumber }}"
                                     data-seat-id="{{ $seatId }}"
                                     data-price="{{ $seatPrice }}"
                                     onclick="selectSeat(this, '{{ $seatNumber }}', {{ $seatId }}, {{ $seatPrice }})"
                                     style="{{ $isOccupied ? 'cursor: not-allowed;' : '' }}">
                                    {{ $seatNumber }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Selected Seat Info -->
                <div id="selectedSeatInfo" class="selected-info" style="display: none;">
                    <h5>Kursi Terpilih: <span id="selectedSeatNumber">-</span></h5>
                    <p class="mb-0">Harga: <strong>Rp <span id="selectedSeatPrice">0</span></strong></p>
                </div>

                <!-- Submit Button -->
                <form action="{{ route('bookings.processSeat') }}" method="POST" id="seatForm">
                    @csrf
                    <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                    <input type="hidden" name="seat_id" id="seatIdInput" value="">
                    <input type="hidden" name="seat_number" id="seatNumberInput" value="">
                    <input type="hidden" name="price" id="priceInput" value="">
                    <button type="submit" class="btn btn-submit" id="submitBtn" disabled>
                        Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectSeat(element, seatNumber, seatId, price) {
            if (element.classList.contains('occupied')) {
                alert('Kursi sudah terisi. Silakan pilih kursi lain.');
                return;
            }

            // Reset semua kursi yang tidak occupied
            document.querySelectorAll('.seat').forEach(seat => {
                seat.classList.remove('selected');
                if (!seat.classList.contains('occupied')) {
                    seat.classList.add('available');
                }
            });

            // Tandai kursi yang dipilih
            element.classList.remove('available');
            element.classList.add('selected');

            // Update informasi teks
            const parsedPrice = price ? Number(price) : 0;
            document.getElementById('selectedSeatNumber').textContent = seatNumber;
            document.getElementById('selectedSeatPrice').textContent = parsedPrice.toLocaleString('id-ID');
            document.getElementById('selectedSeatInfo').style.display = 'block';

            // Set ID, Nomor, dan Price ke form
            document.getElementById('seatIdInput').value = seatId;
            document.getElementById('seatNumberInput').value = seatNumber;
            document.getElementById('priceInput').value = parsedPrice;

            // Aktifkan tombol Lanjutkan
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            submitBtn.removeAttribute('disabled');
        }

        @if($selectedSeat)
            document.addEventListener('DOMContentLoaded', function() {
                const targetSeat = document.querySelector('[data-seat="{{ $selectedSeat }}"]');
                if (targetSeat) {
                    targetSeat.click();
                }
            });
        @endif
    </script>
</body>
</html>
