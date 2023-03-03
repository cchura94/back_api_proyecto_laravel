<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        # validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);      

        // verificar correo y password
        if(!Auth::attempt($credenciales)){
            return response()->json(["message" => "No Autorizado"], 401);
        }
        
        // generar token
        $user = Auth::user();
        $tokenResult = $user->createToken("Token Auth");
        $token = $tokenResult->plainTextToken;
        // respuesta

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "usuario" => $user
        ]);
    }

    public function registro(Request $request)
    {
        // validacion
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);
        // registrar usuario
        $u = new User;
        $u->name = $request->name;
        $u->email = $request->email;
        $u->password = bcrypt($request->password);
        $u->save();

        // respuesta
        return response()->json(["message" => "El usuario ha sido registrado"], 201);
    }

    public function miPerfil(Request $request)
    {
        $user = Auth::user();

        $user->ip = exec('getmac'); 

        return response()->json($user, 200);
    }

    public function cerrar(Request $request)
    {
        // Auth::user()->tokens()->delete();
        $request->user()->tokens()->delete();

        return response()->json(["message" => "Logout"], 200);
    }
}
