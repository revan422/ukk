use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

public function processPayment(Request $request)
{
    $bookingData = session('booking_data'); // Ambil data dari session/form sebelumnya
    $flight = Flight::findOrFail($bookingData['flight_id']);
    
    // 1. Buat Data Booking
    $bookingCode = 'TRX-' . strtoupper(Str::random(8));
    $booking = Booking::create([
        'user_id'          => auth()->id(),
        'flight_id'        => $flight->id,
        'booking_code'     => $bookingCode,
        'total_passengers' => 1,
        'total_price'      => $bookingData['price'],
        'status'           => 'pending',
    ]);

    // 2. Buat Data Passenger
    Passenger::create([
        'booking_id'  => $booking->id,
        'full_name'   => $bookingData['passenger']['full_name'],
        'gender'      => $bookingData['passenger']['gender'] ?? 'male',
        'seat_number' => $bookingData['seat_number'],
    ]);

    // 3. Konfigurasi Midtrans
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production', false);
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id'     => $bookingCode,
            'gross_amount' => (int) $bookingData['price'],
        ],
        'customer_details' => [
            'first_name' => $bookingData['passenger']['full_name'],
            'email'      => auth()->user()->email,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    // 4. Buat Record Pembayaran Awal (Sesuai PDM)
    Payment::create([
        'booking_id'       => $booking->id,
        'payment_method'   => 'Midtrans',
        'amount'           => $bookingData['price'],
        'payment_status'   => 'pending',
        'transaction_code' => $bookingCode,
    ]);

    // Simpan snap token & redirect ke detail booking
    return redirect()->route('bookings.show', $booking->id)->with('snapToken', $snapToken);
}
