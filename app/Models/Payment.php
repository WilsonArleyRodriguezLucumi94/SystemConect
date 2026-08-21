<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'amount',
        'due_date',
        'paid_at',
        'status',
        'payment_method',
        'proof_image',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
