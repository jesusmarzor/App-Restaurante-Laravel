<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'opinion',
        'points',
        'menu_id'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
