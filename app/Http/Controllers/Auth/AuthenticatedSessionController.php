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
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Assign station from URL parameter
        $pcId = $request->input('pc');
        if ($pcId) {
            $station = \App\Models\Station::find($pcId);
            if ($station && !$station->is_occupied) {
                $station->update(['is_occupied' => true, 'user_id' => $user->id]);
                $user->update(['station_id' => $station->id]);
            }
        }

        return redirect()->route('customer.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user && $user->station_id) {
            \App\Models\Station::where('id', $user->station_id)
                ->update(['is_occupied' => false, 'user_id' => null]);
            $user->update(['station_id' => null]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
