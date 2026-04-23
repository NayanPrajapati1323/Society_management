<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Society;
use App\Models\Building;
use App\Http\Requests\Society\LoginRequest;
use App\Http\Requests\Society\RegisterRequest;
use App\Services\AuthService;
use App\Repositories\SocietyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    protected $societyRepo;

    public function __construct(AuthService $authService, SocietyRepository $societyRepo)
    {
        $this->authService = $authService;
        $this->societyRepo = $societyRepo;
    }

    public function landing()
    {
        $plans = Plan::with('features')->where('is_active', true)->orderBy('sort_order')->get();
        return view('society.landing', compact('plans'));
    }

    public function getSocietiesByCity($city)
    {
        return response()->json($this->societyRepo->getByCity($city));
    }

    public function getBuildings(Society $society)
    {
        return response()->json($society->buildings);
    }

    public function getFloors(Building $building)
    {
        $floors = $building->units()->distinct()->pluck('floor')->sort();
        return response()->json($floors);
    }

    public function getUnits(Building $building, Request $request)
    {
        $query = $building->units()->where('status', 'vacant');
        if ($request->has('floor')) {
            $query->where('floor', $request->floor);
        }
        return response()->json($query->get());
    }

    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user());
        return view('society.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request->only('email', 'password'), $request->boolean('remember'));
            return $this->redirectByRole($user);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput($request->only('email'));
        }
    }

    public function showRegister()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user());
        return view('society.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated(), $request->file('document'));
        return redirect()->route('society.login')->with('success', 'Registration successful. Wait for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('society.landing');
    }

    private function redirectByRole($user)
    {
        return match ($user->role_id) {
            1 => redirect()->route('super-admin.dashboard'),
            2 => redirect()->route('society-admin.dashboard'),
            default => redirect()->route('society.user.dashboard'),
        };
    }
}
