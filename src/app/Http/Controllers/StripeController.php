<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    //
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $charge = Charge::create([
            'source' => "tok_".$request->source,
            'currency' => $request->currency,
            'amount' =>  $request->amount * 100,
            'description' => 'Pago de los pedidos',
        ]);

        if($charge){
            return response()->json(['response' => true, "message" => "¡Pago realizado con éxito! Gracias por su visita"], 200);
        }else{
            return response()->json(['response' => false, "message" => "Ha ocurrido un problema con el pago"], 400);
        }
    }
}
