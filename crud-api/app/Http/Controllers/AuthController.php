<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:usuarios',
        'password' => 'required|string|min:6|confirmed',
        'telefono' => 'required|string|max:255',
        'tipoUsuario' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $usuario = Usuario::create([
        'nombre' => $request->nombre,
        'email' => $request->email,
        'password' => $request->password,
        'telefono' => $request->telefono,
        'tipoUsuario' => $request->tipoUsuario,
        'direccion' => $request->direccion,
    ]);

    $token = JWTAuth::fromUser($usuario);

    return response()->json(['token' => $token], 201);
}


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    try {
        // Busca al usuario por su dirección de correo electrónico
        $user = Usuario::where('email', $request->email)->first();

        // Verifica si se encontró un usuario y si la contraseña coincide
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Si las credenciales no coinciden, devuelve un mensaje de error
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Si las credenciales son válidas, genera un token JWT
        $token = JWTAuth::fromUser($user);
        
    } catch (JWTException $e) {
        // Si hay un error al crear el token, devuelve un mensaje de error
        return response()->json(['error' => 'No se pudo crear el token'], 500);
    }

    // Si todo está bien, devuelve el token JWT
    return response()->json(['token' => $token]);
}


public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Token no proporcionado'], 400);
            }

            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Cierre de sesión exitoso']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Algo salió mal, no se pudo cerrar sesión'], 500);
        }
    }
    public function refresh()
    {
        return response()->json([
            'token' => JWTAuth::refresh(JWTAuth::getToken())
        ]);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
