<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $creds = $request->only(['email','password']);

        if(!$token = auth()->attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ], 201);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = new User;

        try {
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);;
            $user->save();

            return $this->login($request);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => '' . $e
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'Logout sukses'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ], 401);
        }
    }

    public function me() 
    {
        return response()->json(auth()->user());
    }


    public function addPhotoScreen(Request $request) 
    {
        $user = User::find(Auth::user()->id);
        $photo = '';

        if($request->photo != '') {
            $photo = time().'.jpg';
            file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
            $user->photo = $photo;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo,
        ], 201);
    }

    public function userProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required'
        ]);
        
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $photo = '';

        if($request->photo != '') {
            $photo = time().'.jpg';
            file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
            $user->photo = $photo;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo,
            'user' => $user
        ], 201);
    }
}
