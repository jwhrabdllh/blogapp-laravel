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

        if(!$token=auth()->attempt($creds)){
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal login'
            ], 401);
        }
        return response()->json([
            'success' =>true,
            'token' => $token,
            'user' => Auth::user()
        ], 201);
    }

    public function register(Request $request)
    {
        $pass = Hash::make($request->password);

        $user = new User;

        try{
            $user->email = $request->email;
            $user->password = $pass;
            $user->save();
            return $this->login($request);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => ''.$e
            ], 401);
        }
    }

    public function userInfo(Request $request){
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $photo = '';

        if($request->photo!=''){
            $photo = time().'.jpg';
            file_put_contents('storage/profiles/'.$photo,base64_decode($request->photo));
            $user->photo = $photo;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo,
            'user' => $user
        ]);
       
    }
}
