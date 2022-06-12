<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'reservation_id',
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
}
