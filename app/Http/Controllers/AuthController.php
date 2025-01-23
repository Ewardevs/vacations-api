<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request){

    $user = User::create([
        "name" => $request->name,
        "email" => $request->email,
        "password" => Hash::make($request->password)
    ]);

    // Enviar respuesta exitosa
    return $this->sendResponse($user, "User registered successfully");
    }

    public function login(LoginRequest $request){

        $user = User::where("email",$request->email)->first();

        if (!$user || !Hash::check($request->password,$user->password)){
            return $this->sendError("Invalid credentials",[],401);
        }


        $token = $user->createToken("laravel")->plainTextToken;


        return $this->sendResponse([
            "user"=>$user,
            "token"=>$token
        ],"User logged in successfully");
    }

    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return $this->sendResponse([], "All tokens have been revoked, user logged out from all devices.");
}

}
