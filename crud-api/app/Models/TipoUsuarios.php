<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuarios extends Model
{
    use HasFactory;

    protected $table = 'tipoUsuario';

    public $timestamps = false;

    protected $fillable = [
        'tipo'
    ];
}
