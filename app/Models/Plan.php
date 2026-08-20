<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    // Esta línea es la que soluciona el error
    protected $fillable = [
        'name', 
        'download_speed', 
        'upload_speed', 
        'price'
    ];

    // Relación: Un plan tiene muchos clientes
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}