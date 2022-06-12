<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Opinion;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'allergens',
        'type'
    ];

    // El plato recupera todos los pedidos donde aparece (0 a muchos)
    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function opinions() {
        return $this->hasMany(Opinion::class);
    }
}
