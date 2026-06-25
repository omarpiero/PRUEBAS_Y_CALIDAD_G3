<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'nombre',
        'correo',
        'tema',
        'curso',
        'mensaje',
        'leido',
    ];

    protected function casts(): array
    {
        return ['leido' => 'boolean'];
    }
}
