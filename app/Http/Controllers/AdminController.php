<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Booking;
use App\Models\User;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use App\Models\Seat;
use App\Models\Destination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // =================== DASHBOARD ===================
    public function index()
    {
        $totalFlights = Flight::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalUsers = User::where('role', 'customer')->count();
        $totalStaff = User::whereIn('role', ['staff', 'manager'])->count();
        $totalAirlines = Airline::count();
        $totalAirports = Airport::count();
        $recentBookings = Booking::with(['user', 'flight'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFlights', 'totalBookings', 'totalRevenue', 'totalUsers',
            'totalStaff', 'totalAirlines', 'totalAirports', 'recentBookings'
        ));
    }

    // =================== USERS CRUD ===================
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users-form', ['user' => null, 'title' => 'Tambah User Baru']);
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,manager,staff,customer',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan!');
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users-form', ['user' => $user, 'title' => 'Edit User']);
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,manager,staff,customer',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
    }

    public function usersDelete($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus admin terakhir!'], 400);
        }
        
        $user->delete();
        
        return response()->json(['success' => true, 'message' => 'User berhasil dihapus!']);
    }

    // =================== FLIGHTS CRUD ===================
    public function flights()
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->orderBy('departure_time', 'desc')->get();
        return view('admin.flights', compact('flights'));
    }

    public function flightsCreate()
    {
        $airlines = Airline::all();
        $airports = Airport::all();
        $airplanes = Airplane::all();
        return view('admin.flights-form', compact('airlines', 'airports', 'airplanes'));
    }

    public function flightsStore(Request $request)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'flight_class' => 'required|in:economy,business,first',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,on_time,delayed,cancelled',
        ]);

        Flight::create($validated);
        return redirect()->route('admin.flights')->with('success', 'Penerbangan berhasil ditambahkan!');
    }

    public function flightsEdit($id)
    {
        $flight = Flight::findOrFail($id);
        $airlines = Airline::all();
        $airports = Airport::all();
        $airplanes = Airplane::all();
        return view('admin.flights-form', compact('flight', 'airlines', 'airports', 'airplanes'));
    }

    public function flightsUpdate(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'flight_class' => 'required|in:economy,business,first',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'status' => 'required|in:scheduled,on_time,delayed,cancelled',
        ]);

        $flight->update($validated);
        return redirect()->route('admin.flights')->with('success', 'Penerbangan berhasil diperbarui!');
    }

    public function flightsDelete($id)
    {
        Flight::findOrFail($id)->delete();
        return redirect()->route('admin.flights')->with('success', 'Penerbangan berhasil dihapus!');
    }

    // =================== AIRLINES CRUD ===================
    public function airlines()
    {
        $airlines = Airline::withCount('airplanes')->get();
        return view('admin.airlines', compact('airlines'));
    }

    public function airlinesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:airlines,code',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        Airline::create($validated);
        return redirect()->route('admin.airlines')->with('success', 'Maskapai berhasil ditambahkan!');
    }

    public function airlinesUpdate(Request $request, $id)
    {
        $airline = Airline::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:airlines,code,' . $id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        $airline->update($validated);
        return redirect()->route('admin.airlines')->with('success', 'Maskapai berhasil diperbarui!');
    }

    public function airlinesDelete($id)
    {
        Airline::findOrFail($id)->delete();
        return redirect()->route('admin.airlines')->with('success', 'Maskapai berhasil dihapus!');
    }

    // =================== AIRPORTS CRUD ===================
    public function airports()
    {
        $airports = Airport::all();
        return view('admin.airports', compact('airports'));
    }

    public function airportsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:airports,code',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        Airport::create($validated);
        return redirect()->route('admin.airports')->with('success', 'Bandara berhasil ditambahkan!');
    }

    public function airportsUpdate(Request $request, $id)
    {
        $airport = Airport::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:airports,code,' . $id,
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $airport->update($validated);
        return redirect()->route('admin.airports')->with('success', 'Bandara berhasil diperbarui!');
    }

    public function airportsDelete($id)
    {
        Airport::findOrFail($id)->delete();
        return redirect()->route('admin.airports')->with('success', 'Bandara berhasil dihapus!');
    }

    // =================== AIRPLANES CRUD ===================
    public function airplanes()
    {
        $airplanes = Airplane::with('airline')->get();
        return view('admin.airplanes', compact('airplanes'));
    }

    public function airplanesStore(Request $request)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'registration_number' => 'nullable|string|max:20',
        ]);

        $airplane = Airplane::create($validated);

        // Auto-generate seats
        $classes = ['economy', 'business', 'first'];
        $seatLetters = range('A', 'F');
        $seatNumber = 1;
        for ($i = 1; $i <= $airplane->capacity; $i++) {
            $class = $i <= $airplane->capacity * 0.1 ? 'first' :
                     ($i <= $airplane->capacity * 0.3 ? 'business' : 'economy');
            $row = ceil($i / 6);
            $letter = $seatLetters[($i - 1) % 6];
            Seat::create([
                'airplane_id' => $airplane->id,
                'seat_number' => $row . $letter,
                'class' => $class,
                'status' => 'available',
            ]);
        }

        return redirect()->route('admin.airplanes')->with('success', 'Pesawat dan kursi berhasil ditambahkan!');
    }

    public function airplanesUpdate(Request $request, $id)
    {
        $airplane = Airplane::findOrFail($id);
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'registration_number' => 'nullable|string|max:20',
        ]);

        $airplane->update($validated);
        return redirect()->route('admin.airplanes')->with('success', 'Pesawat berhasil diperbarui!');
    }

    public function airplanesDelete($id)
    {
        $airplane = Airplane::findOrFail($id);
        $airplane->seats()->delete();
        $airplane->delete();
        return redirect()->route('admin.airplanes')->with('success', 'Pesawat berhasil dihapus!');
    }

    // =================== BOOKINGS ===================
    public function bookings()
    {
        $bookings = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passenger'])
            ->orderBy('created_at', 'desc')->get();
        return view('admin.bookings', compact('bookings'));
    }
}
