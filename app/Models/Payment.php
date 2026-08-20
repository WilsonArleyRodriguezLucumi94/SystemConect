<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['client_id', 'amount', 'due_date', 'paid_at', 'status'];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
