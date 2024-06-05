<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'usuarios';

    public $timestamps = false;

    protected $fillable = [
        'nombre', 
        'password', 
        'email', 
        'telefono',
        'tipoUsuario',
        'direccion'
    ];

    /**
     * Automáticamente hashea la contraseña al establecerla.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Se establece la contraseña hasheada en el atributo 'password'
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Obtiene el identificador que se almacenará en la clave 'subject' del JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // Se devuelve la clave principal del modelo
        return $this->getKey();
    }

    /**
     * Devuelve un array con las reivindicaciones personalizadas que se agregarán al JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // No se agregan reivindicaciones personalizadas en este modelo
        return [];
    }
}
