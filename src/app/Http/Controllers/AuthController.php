<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(["response" => true, "token" => $token, "message" => "Inicio de sesión correcto"], 200);
        }
        return response()->json(['response' => false, "message" => "Email o contraseña incorrecto"], 401);
        
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required | max:255',
            'email' => 'required | email | max:255 | unique:users',
            'image' => 'required',
            'role' => 'required | in:camarero,cocinero,dueño,admin',
            'password' => 'required | min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(["response" => true, "message" => "Registro completado", "error_list" => $validator->errors()], 201);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(["response" => true, "message" => "Cierre de sesión correcto"], 200);
        
    }

    public function getUserAuth()
    {
        $user = Auth::user();
        return response()->json(["response" => true, "user" => $user], 200);
        
    }
}
