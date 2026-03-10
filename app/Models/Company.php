<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'nombre',
        'logo',
        'favicon',
        'direccion',
        'ciudad',
        'email',
        'telefono',
        'rfc',
        'activo',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'empresa_id');
    }
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/app/public/logos/' . $this->logo);
        }
        return asset('logo.svg');
    }
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon) {
            return asset('storage/app/public/favicons/' . $this->favicon);
        }
        if ($this->logo) {
            return asset('storage/app/public/logos/' . $this->logo);
        }

        return asset('favicon.ico');
    }
}
