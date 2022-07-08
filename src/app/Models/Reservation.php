<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Table;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'number',
        'diners',
        'key',
        'tables'
    ];

    // La reserva recupera todos los pedidos donde aparece
    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function getTables() {
        return $this->hasMany(Table::class);
    }

    public function getRandom($length = 16){
        $data = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($data, $length)), 0, $length);
    }
}
