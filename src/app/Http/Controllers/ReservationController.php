<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Reservation::all();
        $allReservations = [];
        foreach ($data as $reservation) {
            # code...
            $reservation['tables'] = $reservation->getTables;
            array_push($allReservations, $reservation);
            // return response()->json([$order->menu()]);
        }
        return response()->json($allReservations,200);
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
        $dateToday = Carbon::now();
        $dateToday->addHours(2);
        $validator = Validator::make($request->all(), [
            'date' => 'required | after:'. $dateToday,
            'name' => 'required | max:100',
            'number' => 'required | numeric | min: 0 | digits: 9',
            'diners' => 'required | numeric | min: 1',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        Reservation::create([
            'date' => date('Y-m-d H:i',strtotime($request->date)),
            'name' => $request->name,
            'number' => $request->number,
            'diners' => $request->diners,
        ]);
        return response()->json(["response" => true, "message" => "Reserva registrada", "error_list" => $validator->errors()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $reservation = Reservation::where('id', $id)->first();
        if($reservation){ 
            $reservation['tables'] = $reservation->getTables;
            $orders = $reservation->orders;
            $allOrdersPaid = true;
            foreach ($orders as $order) {
                # code...
                if(!$order['paid']){
                    $allOrdersPaid = false;
                }
            }
            $reservation['paid'] = $allOrdersPaid;
            return response()->json(['response' => true, 'message' => 'Reserva encontrada', 'reservation' => $reservation],200);
        }
        return response()->json(['response' => false, 'message' => 'Reserva no encontrada'],400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
        $dateToday = Carbon::now();
        $dateToday->addHours(2);
        $validator = Validator::make($request->all(), [
            'date' => 'after:'. $dateToday,
            'name' => 'max:100',
            'number' => 'numeric | min: 0 | digits: 9',
            'diners' => 'numeric | min: 1',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Datos proporcionados incorrectos', "error_list"=>$validator->errors()], 400);
        }
        if($request->date && $request->date !== $reservation->date){
            $reservation->update(['date' => date('Y-m-d H:i',strtotime($request->date))]);
        }
        if($request->name && $request->name !== $reservation->name){
            $reservation->update(['name' => $request->name]);
        }
        if($request->number && $request->number !== $reservation->number)
            $reservation->update(['number' => $request->number]);

        if($request->diners && $request->diners !== $reservation->diners){
            $reservation->update(['diners' => $request->diners]);
        }
        return response()->json(["response" => true, "message" => "Reserva actualizada correctamente", "error_list"=>$validator->errors()]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
        $reservation->delete();
        return response()->json([ "response" => true, "message" => "Reserva borrada con exito"]);
    }

    public function updateKey(Reservation $reservation)
    {
        $key = ($reservation->key == null) ? $reservation->getRandom(5) : null;
        $reservation->update(['key' => $key]);
        return ($key == null) ?  response()->json(["response" => true, "message" => "Reserva desactivada con exito"], 201) : response()->json([ "response" => true, "message" => "Reserva activada con exito"], 201);
    }
}
