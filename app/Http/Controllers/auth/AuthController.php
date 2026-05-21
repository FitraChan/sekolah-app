<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function FormLogin()
    {
        return view('auth.login');
    }

    public function Login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // cek role admin
           if ($user->hasRole('admin')) {

                return redirect('/admin');

            }

            Auth::logout();

            return back()->with('error', 'Bukan admin');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function Logout()
    {
        Auth::logout();

        return redirect()->route('form-login');
    }
}