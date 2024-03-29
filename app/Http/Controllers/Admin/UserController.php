<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function home()
    {
        $users = User::all();
        return view('admin.home', compact('users'));
    }

    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function create()
    {
        return view('admin.create');    
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('admin.index')
            ->with('success', 'Berhasil menambah data user!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.edit', compact('user'));    
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Berhasil mengubah data user!');
    }
    
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.index');
    }
}
