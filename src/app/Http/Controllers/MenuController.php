<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Menu::all();
        $allMenu = [];
        foreach ($data as $menu) {
            # code...
            $points = [];
            foreach ($menu['opinions'] as $opinion) {
                array_push($points, $opinion->points);
            }
            if(sizeof($points) > 0){
                $menu['score'] = round((array_sum($points)/count($points)), 2);
            }else {
                $menu['score'] = 0;
            }
            array_push($allMenu, $menu);
        }
        return response()->json($allMenu,200);
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
            'name' => 'required | max:100',
            'description' => 'required | max:200',
            'price' => 'required | numeric | min:0',
            'image' => 'required',
            'allergens' => 'required',
            'type' => 'required | in:entrante,plato principal,postre'
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
            'allergens' => json_encode($request->allergens),
            'type' => $request->type
        ]);
        return response()->json(["response" => true, "message" => "Plato registrado", "error_list" => $validator->errors()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $food= Menu::where('id', $id)->first();
        if($food){ 
            $food['opinions'] = $food->opinions;
            $points = [];
            foreach ($food['opinions'] as $opinion) {
                array_push($points, $opinion->points);
            }
            if(sizeof($points) > 0){
                $menu['score'] = round((array_sum($points)/count($points)), 2);
            }else {
                $menu['score'] = 0;
            }
            return response()->json(['response' => true, 'message' => 'Plato encontrado', 'food' => $food],200);
        }
        return response()->json(['response' => false, 'message' => 'Plato no encontrado'],400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'min:1 | max:100',
            'description' => 'min:1 | max:200',
            'price' => 'numeric | min:0'
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        if($request->name && $request->name !== $menu->name){
            $menu->update(['name' => $request->name]);
        }
        if($request->description && $request->description !== $menu->description){
            $menu->update(['description' => $request->description]);
        }
        if($request->price && $request->price !== $menu->price)
            $menu->update(['price' => $request->price]);

        if($request->allergens && $request->allergens !== json_decode($menu->allergens)){
            $menu->update(['allergens' => json_encode($request->allergens)]);
        }
        if($request->type && $request->type !== json_decode($menu->type)){
            $menu->update(['type' => $request->type]);
        }
        return response()->json(["response" => true, "message" => "Plato actualizado correctamente", "error_list"=>$validator->errors()]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
        $image = $menu->image;
        $menu->delete();
        return response()->json([ "response" => true, "message" => "Plato borrado con exito", "image" => $image]);
    }
}
