<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required | min:1',
            'opinion' => 'max:200 | nullable',
            'points' => 'required | numeric',
            'menu_id' => 'required | numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }

        Opinion::create([
            'name' => $request->name,
            'opinion' => $request->opinion,
            'points' => $request->points,
            'menu_id' => $request->menu_id
        ]);
        return response()->json(["response" => true, "message" => "Comentario registrado", "error_list" => $validator->errors()], 201);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function show(Opinion $opinion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function edit(Opinion $opinion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opinion $opinion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Opinion  $opinion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opinion $opinion)
    {
        //
        $opinion->delete();
        return response()->json([ "response" => true, "message" => "Opinion borrada con exito"]);
    }
}
