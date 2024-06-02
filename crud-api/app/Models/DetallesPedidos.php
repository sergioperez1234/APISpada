<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesPedidos extends Model
{
    use HasFactory;

    protected $table = 'detallesPedidos';

    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_pieza',
        'cantidad'
    ];
}
