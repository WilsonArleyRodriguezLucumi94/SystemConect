<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;
use App\Models\IpAddress;
use App\Models\CompanyEquipment;
use App\Models\Payment;


class Client extends Model
{
    protected $fillable = [
        'document_number',
        'full_name',
        'phone',
        'address',
        'plan_id',
        'ip_address_id',
        'router_id',
        'company_equipment_id',
        'billing_day',
        'next_due_date',
        'status',
    ];

    // Casteo para manipular 'next_due_date' como objeto Carbon automáticamente
    protected $casts = [
        'next_due_date' => 'date',
    ];

    /* --- RELACIONES --- */

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function ipAddress()
    {
        return $this->belongsTo(IpAddress::class, 'ip_address_id');
    }

    public function companyEquipment()
    {
        return $this->belongsTo(CompanyEquipment::class, 'company_equipment_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
}
