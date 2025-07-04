<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// Models
use App\Models\HomeContent;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $viewData = [
            'title' => 'Login',
            'logo' => HomeContent::get('logo')->first(),
        ];

        return view('auth.login', $viewData);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->role == 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false))->with('success', 'Login Berhasil');
        }
        if (Auth::user()->role == 'pelanggan') {
            return redirect()->intended(route('home', absolute: false))->with('success', 'Login Berhasil');
        }

        return back()->with('error', 'Role not found');
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
