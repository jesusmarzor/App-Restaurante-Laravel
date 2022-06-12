<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Reservation;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $allTables = Table::all();
        return response()->json($allTables,200);
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
            'number' => 'required | numeric | unique:tables'
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Introduce un número de mesa no repetido', "error_list"=>$validator->errors()], 400);
        }

        Table::create([
            'number' => $request->number,
        ]);
        return response()->json(["response" => true, "message" => "Mesa registrada", "error_list" => $validator->errors()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        //

        $validator = Validator::make($request->all(), [
            'reservation_id' => 'nullable | numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'No se ha podido completar la acción', "error_list"=>$validator->errors()], 400);
        }

        if($request->reservation_id === null){
            $reservation = $table->reservation;
            $tables = $reservation->getTables;
            if(sizeof($tables) <= 1 && $reservation->key !== null) {
                return response()->json(["response" => false, "message" => "La reserva ".$reservation->id." tiene que tener mínimo una mesa", "error_list"=>$validator->errors()]);    
            }
        }

        $table->update(['reservation_id' => $request->reservation_id]);

        return response()->json(["response" => true, "message" => "Mesa actualizada correctamente", "error_list"=>$validator->errors()]);
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        //
        $table->delete();
        return response()->json([ "response" => true, "message" => "Mesa borrada con exito"]);
    }
}
