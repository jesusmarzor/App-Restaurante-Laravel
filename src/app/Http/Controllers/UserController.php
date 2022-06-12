<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $todosUsers = User::all();
        return response()->json($todosUsers,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function show(User $user)
    // {
    //     //
    //     return response()->json([$user],200);
    // }

    public function show($id){
        $user = User::where('id', $id)->first();
        if($user){ 
            return response()->json(['response' => true, 'message' => 'Usuario encontrado', 'user' => $user],200);
        }
        return response()->json(['response' => false, 'message' => 'Usuario no encontrado'],400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'min:1 | max:255',
            'email' => 'email | max:255 | unique:users,email,'.$user->id,
            'role' => 'in:camarero,cocinero,admin'
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        if($request->name && $request->name !== $user->name){
            $user->update(['name' => $request->name]);
        }
        if($request->email && $request->email !== $user->email){
            $user->update(['email' => $request->email]);
        }
        if($request->password){
            $validator = Validator::make($request->all(), [
                'password' => 'min:8'
            ]);
            if ($validator->fails()) {
                return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
            }
            if(!Hash::check($request->password, $user->password))
                $user->update(['password' => Hash::make($request->password)]);
        }
        if($request->role && $request->role !== $user->role){
            $user->update(['role' => $request->role]);
        }
        return response()->json(["response" => true, "message" => "Usuario actualizado correctamente", "error_list"=>$validator->errors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $image = $user->image;
        $user->delete();
        return response()->json([ "response" => true, "message" => "Usuario borrado con exito", "image" => $image]);
    }
}
