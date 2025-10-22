<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;
    protected $fillable = [
        'computer_name',
        'asset_tag',
        'serial_number',
        'brand',
        'model',
        'processor',
        'ram',
        'storage',
        'operating_system',
        'ip_address',
        'mac_address',
        'type',
        'status',
        'purchase_date',
        'warranty_expiry',
        'location',
        'assigned_user_id',
        'assigned_date',
        'is_leased',
        'notes',
        'empresa_id',
        'department_id',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
