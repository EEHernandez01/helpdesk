<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'id_employee',
        'department_id',
        'hire_date',
        'is_online',
        'status',
        'empresa_id',
    ];
    protected $casts = [
        'hire_date' => 'date',
        'is_online' => 'boolean',
    ];
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
    public function pc()
    {
        return $this->hasMany(Computer::class, 'assigned_user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }
}
