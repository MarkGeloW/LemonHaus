<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (auth()->check()) {
            // Redirect authenticated users to their role-specific page
            $role = auth()->user()->role;

            return match ($role) {
                'admin' => redirect()->route('dashboard'),
                'cashier' => redirect()->route('orders.index'),
                'kitchen' => redirect()->route('kitchen.index'),
                'inventory' => redirect()->route('inventory.index'),
                default => redirect('/'),
            };
        }

        return view('auth.login');
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
{
    $request->authenticate();

    $request->session()->regenerate();

    $role = auth()->user()->role;

 return match ($role) {
    'admin' => redirect()->route('dashboard'),
    'cashier' => redirect()->route('cashier.dashboard'),
    'kitchen' => redirect()->route('kitchen.dashboard'),
    'inventory' => redirect()->route('inventory.dashboard'),
    default => redirect()->route('login'),
};
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
