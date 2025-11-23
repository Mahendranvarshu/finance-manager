<?php

namespace App\Http\Controllers;

use App\Models\Collector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CollectorAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('collector')->check()) {
            return redirect()->route('collector.dashboard');
        }
        return view('collector.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $collector = Collector::where('username', $request->username)
            ->where('status', 'active')
            ->first();

        if (!$collector || !Hash::check($request->password, $collector->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        Auth::guard('collector')->login($collector, $request->filled('remember'));

        return redirect()->route('collector.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('collector')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('collector.login');
    }
}

