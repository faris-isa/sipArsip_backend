<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function userDetail($username) {
        $user = array();
        if($username != "") {
            $user  =  User::where("username", $username)->first();
            return $user;
        }
    }

    public function login(Request $request){
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
            'message' => 'Invalid login details'
                       ], 400);
                   }

        // check if entered username exists in db
        $user = User::where('username', $request->username)->first();
        $passHash = Hash::make($request->password);

        // if username exists then we will check password for the same email
        if (!$user || !Hash::check($request->password, $user->password)){
            return response()->json(["status" => 401, "message" => "Gagal login, periksa username dan password !"]);
        }
        $user = $this->userDetail($request->username);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(["status" => 201, "message" => "Login berhasil", "data" => $user, 'access_token' => $token]);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json(["status" => 200, "message" => "Logged Out"]);
    }

    public function welcome(){
        return view('welcome');
    }

}
