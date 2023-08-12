<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $creds = $request->only(['email','password']);

        // $creds = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|min:6'
        // ]);

        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $creds = $validator->validated();

        // try {
        //     if (!$token = auth()->attempt($creds)) {
        //         throw new \Exception('Email atau password salah', 401);
        //     }
        
        //     return response()->json([
        //         'success' => true,
        //         'token' => $token,
        //         'user' => Auth::user()
        //     ], 201);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ], $e->getCode());
        // }

        if(!$token = auth()->attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
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
            'email' => 'required|email|unique:users,email',
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
            'lastname' => 'required',
            'email' => 'email|unique:users,email,' . Auth::user()->id
        ]);
        
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        
        if ($request->has('email')) {
            $user->email = $request->email;
        }

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
