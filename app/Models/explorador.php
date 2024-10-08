<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class explorador extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'idade',
        'latitude',
        'longitude',
        'inventario',
    ];

    public function item()
    {
        return $this->hasMany(Item::class, 'explorador_id');
    }
}

