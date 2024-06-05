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
    /**
     * Registra un nuevo usuario.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos del usuario
     * @return \Illuminate\Http\JsonResponse La respuesta en formato JSON con el token JWT generado
     */
    public function register(Request $request){
        // Valida los datos del usuario
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


    /**
     * Función para el inicio de sesión de usuarios.
     *
     * @param Request $request La solicitud HTTP con los datos del usuario (email y contraseña)
     * @return \Illuminate\Http\JsonResponse La respuesta en formato JSON con el estado del proceso
     */
    public function login(Request $request)
    {
        // Valida los datos del usuario
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // Si hay errores de validación, devuelve un error en formato JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ], 400);
        }

        try {
            // Busca al usuario por su dirección de correo electrónico
            $user = Usuario::where('email', $request->email)->first();

            // Verifica si se encontró un usuario y si la contraseña coincide
            if (!$user || !Hash::check($request->password, $user->password)) {
                // Si las credenciales no coinciden, devuelve un mensaje de error
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }

            // Si las credenciales son válidas, genera un token JWT con el claim personalizado 'tipoUsuario'
            $token = JWTAuth::claims([
                'tipoUsuario' => $user->tipoUsuario,
            ])->fromUser($user);

        } catch (JWTException $e) {
            // Si hay un error al crear el token, devuelve un mensaje de error
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

        // Si todo está bien, devuelve el token JWT
        return response()->json(['token' => $token], 200);
    }



    /**
     * Función para cerrar la sesión del usuario.
     *
     * Esta función intenta invalidar el token JWT proporcionado en la solicitud.
     * Devuelve un mensaje de éxito si el cierre de sesión fue exitoso, o un mensaje de error
     * si ocurrió alguna excepción durante el proceso.
     *
     * @param Request $request La solicitud HTTP con el token JWT del usuario
     * @return \Illuminate\Http\JsonResponse El mensaje de éxito o el mensaje de error
     */
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
    /**
     * Función para renovar el token de sesión del usuario.
     *
     * Esta función toma el token JWT actual y lo renueva, devuelve el token renovado.
     *
     * @return \Illuminate\Http\JsonResponse El token renovado en formato JSON
     */
    public function refresh()
    {
        // Renueva el token JWT actual
        // Devuelve el token renovado en formato JSON
        return response()->json([
            'token' => JWTAuth::refresh(JWTAuth::getToken())
        ]);
    }

    /**
     * Función para obtener los datos del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse Los datos del usuario autenticado en formato JSON
     */
    public function me()
    {
        // Devuelve los datos del usuario autenticado en formato JSON
        return response()->json(JWTAuth::user());
    }
}
