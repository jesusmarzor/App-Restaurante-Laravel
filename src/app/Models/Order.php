<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\Reservation;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'menu_id',
        'reservation_id',
        'note',
        'number',
        'tracking',
        'paid'
    ];

    // 1 a muchos (inversa)
    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    // 1 a muchos (inversa)
    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
    
}
