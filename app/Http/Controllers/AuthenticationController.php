<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SweetAlert2\Laravel\Swal;

class AuthenticationController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('username', 'password'))) {
            Swal::error(['title' => 'Oops...', 'text' => 'Username or password is incorrect']);
            return redirect()->route('login');
        }

        $request->session()->regenerate();

        return redirect()->intended('categories');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Swal::success(['title' => 'Success', 'text' => 'You have been logged out']);
        return redirect()->route('login');
    }
}
