<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminAuthController extends Controller
{
    public function loginForm()
    {
        if(auth()->guard('admin')->user()) {
            return back();
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!'
        ]);
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();

        $request->session()->flush();
        
        return redirect(url('admin/login'));
    }
}
