<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penumpang - SkyLine Airlines</title>
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
        .form-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
        }
        .flight-info {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #11998e;
        }
        .btn-gold {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #f4b400;
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .gender-options {
            display: flex;
            gap: 20px;
        }
        .gender-option {
            flex: 1;
        }
        .gender-option input[type="radio"] {
            display: none;
        }
        .gender-option label {
            display: block;
            padding: 12px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        .gender-option input[type="radio"]:checked + label {
            border-color: #f4b400;
            background: rgba(244, 180, 0, 0.1);
            color: #0a192f;
        }
        .gender-option label:hover {
            border-color: #f4b400;
        }
        .shipping-result-item {
            cursor: pointer;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .shipping-result-item:hover {
            background-color: #f8f9fa;
            border-left-color: #f4b400;
        }
        .shipping-result-item.selected {
            background-color: #fff3cd;
            border-left-color: #f4b400;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Data Penumpang</span>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4" style="color: #0a192f;">Isi Data Penumpang</h3>

                        <!-- Info Penerbangan -->
                        <div class="flight-info mb-4">
                            <h6 class="fw-bold mb-2">Detail Penerbangan:</h6>
                            <p class="mb-1"><strong>{{ $flight->airline->name }}</strong> - {{ $flight->flight_number }}</p>
                            <p class="mb-1">{{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}</p>
                            <p class="mb-0">Kursi: <strong>{{ $bookingData['seat_number'] }}</strong> | Harga: <strong>Rp {{ number_format($bookingData['price'], 0, ',', '.') }}</strong></p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('bookings.processPassenger') }}" method="POST">
                            @csrf

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="Sesuai KTP/Paspor" required>
                                <small class="text-muted">Masukkan nama lengkap sesuai identitas</small>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                                <small class="text-muted">Format: YYYY-MM-DD</small>
                            </div>

                            <!-- No NIK / Paspor -->
                            <div class="mb-3">
                                <label class="form-label">No. NIK / Paspor <span class="text-danger">*</span></label>
                                <input type="text" name="id_card_number" class="form-control" value="{{ old('id_card_number') }}" placeholder="Masukkan NIK atau Nomor Paspor" required>
                                <small class="text-muted">Minimal 10 karakter</small>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="mb-4">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="gender-options">
                                    <div class="gender-option">
                                        <input type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                        <label for="male">👨 Laki-laki</label>
                                    </div>
                                    <div class="gender-option">
                                        <input type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                        <label for="female">👩 Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- RAJAONGKIR SHIPPING SECTION - KOMERCE API -->
                            <div class="mb-4 mt-4 border-top pt-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="shipping_required" id="shipping_required" value="1">
                                    <label class="form-check-label fw-bold" for="shipping_required" style="color: #0a192f; cursor: pointer;">
                                        📦 Kirim Tiket Fisik & Dokumen Perjalanan ke Rumah (Layanan Ekspedisi POS/JNE/TIKI)
                                    </label>
                                </div>

                                <div id="shipping_fields_wrapper" style="display: none; background: rgba(0, 0, 0, 0.02); padding: 20px; border-radius: 10px; border: 1px dashed #ccc;">
                                    <h5 class="fw-bold mb-3" style="color: #0a192f; font-size: 1rem;"><i class="fas fa-truck me-2"></i>Detail Pengiriman</h5>

                                    <input type="hidden" name="shipping_province_name" id="shipping_province_name">
                                    <input type="hidden" name="shipping_city_name" id="shipping_city_name">
                                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Provinsi Tujuan</label>
                                            <select name="shipping_province_id" id="shipping_province" class="form-select">
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Destinasi (Kota/Kecamatan)</label>
                                            <div class="position-relative">
                                                <input type="text" name="search_destination" id="search_destination" class="form-control" placeholder="Ketik minimal 3 huruf..." autocomplete="off">
                                                <div id="destination_results" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                                            </div>
                                            <small class="text-muted">Cari kota/kecamatan tujuan</small>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="2" placeholder="Nama Jalan, Blok, No Rumah, RT/RW, Kecamatan, Kode Pos"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kurir Ekspedisi</label>
                                            <select name="shipping_courier" id="shipping_courier" class="form-select">
                                                <option value="pos">POS Indonesia</option>
                                                <option value="jne">JNE</option>
                                                <option value="tiki">TIKI</option>
                                                <option value="sicepat">SiCepat</option>
                                                <option value="jnt">J&T</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="w-100 p-3 bg-white rounded border border-warning" style="min-height: 48px;">
                                                <div id="shipping_cost_loading" style="display: none;" class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                <span id="shipping_cost_label" class="fw-bold text-success">Biaya Kirim: Rp 0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Daftar Layanan -->
                                    <div id="shipping_services" class="mt-3" style="display: none;">
                                        <label class="form-label">Pilih Layanan:</label>
                                        <div id="shipping_services_list"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- END RAJAONGKIR SHIPPING SECTION -->

                            <button type="submit" class="btn btn-gold w-100">Lanjutkan ke Pembayaran</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const shipCheck = document.getElementById('shipping_required');
            const shipFields = document.getElementById('shipping_fields_wrapper');
            const provSelect = document.getElementById('shipping_province');
            const searchInput = document.getElementById('search_destination');
            const destResults = document.getElementById('destination_results');
            const addressInput = document.getElementById('shipping_address');
            const courierSelect = document.getElementById('shipping_courier');
            const provNameInput = document.getElementById('shipping_province_name');
            const cityNameInput = document.getElementById('shipping_city_name');
            const shippingCostInput = document.getElementById('shipping_cost');
            const servicesContainer = document.getElementById('shipping_services');
            const servicesList = document.getElementById('shipping_services_list');

            const costLabel = document.getElementById('shipping_cost_label');
            const costLoading = document.getElementById('shipping_cost_loading');

            let selectedDestinationId = null;
            let selectedDestinationName = null;
            let selectedServiceCost = 0;

            // Toggle fields visibility
            shipCheck.addEventListener('change', function () {
                if (shipCheck.checked) {
                    shipFields.style.display = 'block';
                    loadProvinces();
                    setRequired(true);
                } else {
                    shipFields.style.display = 'none';
                    setRequired(false);
                    costLabel.textContent = 'Biaya Kirim: Rp 0';
                    shippingCostInput.value = '0';
                    servicesContainer.style.display = 'none';
                    selectedServiceCost = 0;
                }
            });

            function setRequired(val) {
                provSelect.required = val;
                addressInput.required = val;
            }

            // Load provinces via AJAX - RajaOngkir Komerce API
            function loadProvinces() {
                if (provSelect.options.length > 1) return;

                fetch('{{ route("rajaongkir.provinces") }}')
                    .then(r => r.json())
                    .then(data => {
                        let provinces = [];
                        if (data.success === false) {
                            console.error('RajaOngkir error:', data.message);
                            return;
                        }
                        // Response Komerce: { meta: {...}, data: [ { id, name }, ... ] }
                        if (data.data && Array.isArray(data.data)) {
                            provinces = data.data;
                        } else if (Array.isArray(data)) {
                            provinces = data;
                        } else {
                            console.error('Unexpected response format:', data);
                            return;
                        }
                        provinces.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.id;
                            opt.textContent = p.name;
                            provSelect.appendChild(opt);
                        });
                    })
                    .catch(err => console.error('Error loading provinces:', err));
            }

            // Search destinations as user types (RajaOngkir Komerce API)
            let searchTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const query = this.value.trim();
                if (query.length < 3) {
                    destResults.style.display = 'none';
                    selectedDestinationId = null;
                    selectedDestinationName = null;
                    cityNameInput.value = '';
                    return;
                }

                searchTimer = setTimeout(() => {
                    fetch(`{{ route("rajaongkir.destinations") }}?search=${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(data => {
                            destResults.innerHTML = '';
                            if (data.success === false) {
                                destResults.style.display = 'none';
                                return;
                            }
                            let destinations = [];
                            // Response Komerce: { meta: {...}, data: [ { id, label, province_name, city_name, subdistrict_name }, ... ] }
                            if (data.data && Array.isArray(data.data)) {
                                destinations = data.data;
                            } else if (Array.isArray(data)) {
                                destinations = data;
                            } else {
                                destResults.style.display = 'none';
                                return;
                            }

                            if (destinations.length === 0) {
                                destResults.style.display = 'none';
                                return;
                            }

                            destinations.forEach(d => {
                                const item = document.createElement('button');
                                item.type = 'button';
                                item.className = 'list-group-item list-group-item-action';
                                item.textContent = d.label || `${d.subdistrict_name || ''}, ${d.city_name || ''} - ${d.province_name || ''}`;
                                item.dataset.id = d.id;
                                item.dataset.name = d.label || `${d.subdistrict_name}, ${d.city_name}`;
                                item.addEventListener('click', function () {
                                    selectDestination(this.dataset.id, this.dataset.name);
                                });
                                destResults.appendChild(item);
                            });

                            destResults.style.display = 'block';
                        })
                        .catch(err => {
                            console.error('Error searching destinations:', err);
                            destResults.style.display = 'none';
                        });
                }, 300);
            });

            // Hide search results on click outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('#search_destination') && !e.target.closest('#destination_results')) {
                    destResults.style.display = 'none';
                }
            });

            function selectDestination(id, name) {
                selectedDestinationId = id;
                selectedDestinationName = name;
                searchInput.value = name;
                cityNameInput.value = name;
                destResults.style.display = 'none';
                servicesContainer.style.display = 'none';
                selectedServiceCost = 0;
                shippingCostInput.value = '0';
                costLabel.textContent = 'Pilih layanan pengiriman...';
                calculateCost();
            }

            // Calculate cost when city or courier changes
            courierSelect.addEventListener('change', function () {
                if (selectedDestinationId) {
                    servicesContainer.style.display = 'none';
                    selectedServiceCost = 0;
                    shippingCostInput.value = '0';
                    costLabel.textContent = 'Pilih layanan pengiriman...';
                    calculateCost();
                }
            });

            function calculateCost() {
                if (!selectedDestinationId) return;

                costLoading.style.display = 'inline-block';
                costLabel.textContent = 'Menghitung...';

                fetch('{{ route("rajaongkir.calculate-cost") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        origin: '{{ config("services.rajaongkir.origin") }}',
                        destination: selectedDestinationId,
                        weight: 1000,
                        courier: courierSelect.value,
                        service: 'lowest'
                    })
                })
                .then(r => r.json())
                .then(data => {
                    costLoading.style.display = 'none';
                    if (data.success === false) {
                        costLabel.textContent = 'Gagal: ' + (data.message || 'Error tidak diketahui');
                        return;
                    }

                    // Response Komerce: { meta: {...}, data: [ { name, code, service, cost, etd }, ... ] }
                    let services = [];
                    if (data.data && Array.isArray(data.data)) {
                        services = data.data;
                    } else if (Array.isArray(data)) {
                        services = data;
                    } else {
                        costLabel.textContent = 'Tidak ada layanan untuk rute ini';
                        return;
                    }

                    if (services.length === 0) {
                        costLabel.textContent = 'Kurir tidak mendukung rute ini';
                        return;
                    }

                    // Tampilkan daftar layanan
                    servicesList.innerHTML = '';
                    services.forEach((svc, idx) => {
                        const div = document.createElement('div');
                        div.className = 'd-flex align-items-center justify-content-between p-2 mb-1 border rounded shipping-result-item';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `
                            <div>
                                <strong>${svc.service}</strong>
                                <small class="d-block text-muted">Estimasi: ${svc.etd || '-'}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(svc.cost)}</span>
                            </div>
                        `;
                        div.addEventListener('click', function () {
                            // Remove selected from all
                            document.querySelectorAll('.shipping-result-item').forEach(el => el.classList.remove('selected'));
                            div.classList.add('selected');
                            selectedServiceCost = svc.cost;
                            shippingCostInput.value = svc.cost;
                            costLabel.textContent = 'Biaya Kirim: Rp ' + new Intl.NumberFormat('id-ID').format(svc.cost);
                        });
                        // Auto-select first service
                        if (idx === 0) {
                            div.click();
                        }
                        servicesList.appendChild(div);
                    });

                    servicesContainer.style.display = 'block';
                })
                .catch(err => {
                    costLoading.style.display = 'none';
                    costLabel.textContent = 'Gagal menghitung biaya';
                    console.error('Error calculating cost:', err);
                });
            }
        });
    </script>
</body>
</html>
