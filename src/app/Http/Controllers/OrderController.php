<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Order::all();
        $allOrders = [];
        foreach ($data as $order) {
            # code...
            $order['menu'] = $order->menu;
            $reservation = $order->reservation;
            $reservation['tables'] = $reservation->getTables;
            $order['reservation'] = $reservation;
            array_push($allOrders, $order);
            // return response()->json([$order->menu()]);
        }
        return response()->json($data,200);
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
            'key' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message' => 'Necesitas poner el código de tu reserva para pedir'], 400);
        }
        $key = $request->key;
        $cart = $request->cart;

        $reservation = Reservation::where('key', $key)->first();
        
        if($reservation){
            foreach ($cart as $value) {
                # code...
                $food =  Menu::where('id', $value['idFood'])->first();
                if($food){
                    Order::create([
                        'menu_id' => $food->id,
                        'reservation_id' => $reservation->id,
                        'note' => $value['note'],
                        'number' => $value['num'],
                        'tracking' => 'Pendiente' // Preparando, Sirviendo, Completado
                    ]);
                }
            }
            return response()->json(['response' => true, 'message' => '¡Pedido en marcha!'], 200);
        }else{
            return response()->json(['response' => false, 'message' => 'El código de reserva no existe'], 400);
        }
        // return response()->json(["response" => true, "message" => "Plato registrado", "error_list" => $validator->errors()], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $order = Order::where('id', $id)->first();
        if($order){ 
            $order['menu'] = $order->menu;
            $reservation = $order->reservation;
            $reservation['tables'] = $reservation->getTables;
            $order['reservation'] = $reservation;
            return response()->json(['response' => true, 'message' => 'Pedido encontrado', 'order' => $order],200);
        }
        return response()->json(['response' => false, 'message' => 'Pedido no encontrado'],400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
        if($request->tracking){
            $validator = Validator::make($request->all(), [
                'tracking' => 'in:Preparando,Sirviendo,Completado'
            ]);
            if ($validator->fails()) {
                return response()->json(['response' => false, 'message' => 'Error al cambiar el tracking del pedido'], 400);
            }
        }
        if($request->paid){
            $validator = Validator::make($request->all(), [
                'paid' => 'boolean'
            ]);
            if ($validator->fails()) {
                return response()->json(['response' => false, 'message' => 'Error al cambiar el pago del pedido'], 400);
            }
        }
        
        if($request->tracking && $request->tracking !== $order->tracking){
            $order->update(['tracking' => $request->tracking]);
        }
        if($request->paid && $request->paid !== $order->paid){
            $order->update(['paid' => $request->paid]);
        }
        return response()->json(["response" => true, "message" => "Pedido actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
        $order->delete();
        return response()->json([ "response" => true, "message" => "Pedido borrado con exito"]);
    }
}
