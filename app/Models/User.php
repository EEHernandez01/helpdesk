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

        // Verifica si el usuario tiene un rol específico
        public function hasRole($role)
        {
            if (is_array($role)) {
                return in_array($this->role, $role);
            }
            return $this->role === $role;
        }

    // Relación con Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relación con Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    // Relación con Tickets asignados
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Relación con PC

    public function pc()
    {
        return $this->hasMany(Computer::class, 'assigned_user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    /**
     * Canal privado para notificaciones por broadcast.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }
}
