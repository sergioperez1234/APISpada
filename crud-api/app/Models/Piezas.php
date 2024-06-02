<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piezas extends Model
{
    use HasFactory;

    protected $table = 'piezas';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'tipo',
        'modelo',
        'imagen'
    ];
}
