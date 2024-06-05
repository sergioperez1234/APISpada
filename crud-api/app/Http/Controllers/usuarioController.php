<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioController extends Controller
{
    /**
     * Retorna todos los usuarios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        // Obtiene todos los usuarios
        $usuarios = Usuario::all();

        // Verifica si no se encontraron usuarios
        if ($usuarios->isEmpty()) {
            $data = [
                'status' => 200,
                'message' => 'No se encontraron usuarios',
            ];
            return response()->json($data, 200);
        }

        // Retorna los usuarios obtenidos en formato JSON
        $data = [
            'usuarios' => $usuarios,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos del usuario
     * @return \Illuminate\Http\JsonResponse La respuesta en formato JSON con el estado del proceso
     */
    public function store(Request $request){
        // Valida los datos del usuario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:usuarios',
            'password' => 'required|string|min:6|max:16',
            'telefono' => 'required|digits:9',
            'tipoUsuario' => 'required|boolean', // Cambiado a boolean
            'direccion' => 'required|string|max:50',
        ]);

        // Si hay errores de validación, devuelve un error en formato JSON
        if ($validator->fails()) {
            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ];
            return response()->json($data, 400);
        }

        // Crea el nuevo usuario
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => $request->password,
            'telefono' => $request->telefono,
            'tipoUsuario' => $request->tipoUsuario,
            'direccion' => $request->direccion
        ]);

        // Si no se pudo crear el usuario, devuelve un error en formato JSON
        if (!$usuario) {
            $data = [
                'status' => 500,
                'message' => 'Error al crear el usuario'
            ];
            return response()->json($data, 500);
        }

        // Si todo ha ido bien, devuelve el usuario creado en formato JSON
        $data = [
            'status' => 201,
            'usuario' => $usuario,
            'message' => 'Usuario creado correctamente'
        ];
        return response()->json($data, 201);
    }

    /**
     * Muestra un usuario en particular.
     *
     * @param int $id El ID del usuario a mostrar
     * @return \Illuminate\Http\JsonResponse El usuario en formato JSON
     */
    public function show($id){
        // Busca el usuario por ID
        $usuario = Usuario::find($id);

        // Si no se encontró el usuario, devuelve un error en formato JSON
        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el usuario'
            ];
            return response()->json($data, 404);
        }

        // Si se encontró el usuario, devuelve el usuario en formato JSON
        $data = [
            'status' => 200,
            'usuario' => $usuario
        ];
        return response()->json($data, 200);
    }

    /**
     * Elimina un usuario en particular.
     *
     * @param int $id El ID del usuario a eliminar
     * @return \Illuminate\Http\JsonResponse El estado de la operación en formato JSON
     */
    public function destroy($id){
        // Busca el usuario por ID
        $usuario = Usuario::find($id);

        // Si no se encontró el usuario, devuelve un error en formato JSON
        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el usuario'
            ];
            return response()->json($data, 404);
        }

        // Si se encontró el usuario, elimina el usuario y devuelve un mensaje de éxito en formato JSON
        $usuario->delete();
        $data = [
            'status' => 200,
            'message' => 'Usuario eliminado correctamente'
        ];
        return response()->json($data, 200);
    }

    /**
     * Actualiza un usuario en particular.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos del usuario
     * @param int $id El ID del usuario a actualizar
     * @return \Illuminate\Http\JsonResponse El estado de la operación en formato JSON
     */
    public function update(Request $request, $id){
        // Busca el usuario por ID
        $usuario = Usuario::find($id);

        // Si no se encontró el usuario, devuelve un error en formato JSON
        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el usuario'
            ];
            return response()->json($data, 404);
        }

        // Valida los datos del usuario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:usuarios,email,'.$id,
            'password' => 'required|string|min:6|max:16',
            'telefono' => 'required|digits:9',
            'tipoUsuario' => 'required|boolean', // Cambiado a boolean
            'direccion' => 'required|string|max:50',
        ]);

        // Si hay errores de validación, devuelve un error en formato JSON
        if ($validator->fails()) {
            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ];
            return response()->json($data, 400);
        }

        // Actualiza los datos del usuario
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = $request->password; // Hasheado automáticamente por el mutador
        $usuario->telefono = $request->telefono;
        $usuario->tipoUsuario = $request->tipoUsuario;
        $usuario->direccion = $request->direccion;

        $usuario->save();

        // Si todo ha ido bien, devuelve el usuario actualizado en formato JSON
        $data = [
            'status' => 200,
            'usuario' => $usuario,
            'message' => 'Usuario actualizado correctamente'
        ];
        return response()->json($data, 200);
    }

    /**
     * Función para el inicio de sesión de usuarios.
     *
     * @param Request $request La solicitud HTTP con los datos del usuario (email y contraseña)
     * @return \Illuminate\Http\JsonResponse La respuesta en formato JSON con el estado del proceso
     */
    public function login(Request $request){
        // Valida los datos del usuario
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:6|max:16',
        ]);

        // Si hay errores de validación, devuelve un error en formato JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ], 400);
        }

        // Obtiene los datos del usuario
        $email = $request->email;
        $password = $request->password;

        // Busca al usuario por su dirección de correo electrónico
        $usuario = Usuario::where('email', $email)->first();

        // Verifica si se encontró un usuario y si la contraseña coincide
        if (!$usuario || !Hash::check($password, $usuario->password)) {
            // Si las credenciales no coinciden, devuelve un mensaje de error
            return response()->json([
                'status' => 404,
                'message' => 'No se encontró el usuario o la contraseña es incorrecta'
            ], 404);
        }

        // Si todo está bien, devuelve el usuario encontrado en formato JSON
        return response()->json([
            'status' => 200,
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Obtiene los atributos de un token JWT
     *
     * Esta función toma un token JWT en el cuerpo de la solicitud, lo decodifica
     * y devuelve todos sus atributos en formato JSON. Si el token es inválido,
     * devuelve un mensaje de error.
     *
     * @param Request $request La solicitud HTTP que contiene el token JWT
     * @return \Illuminate\Http\JsonResponse Los atributos del token JWT en formato JSON
     *         o un mensaje de error si el token es inválido
     */
    public function getTokenAttributes(Request $request)
    {
        try {
            // Obtiene el token del request
            $token = $request->bearerToken();
            
            // Decodifica el token para obtener sus atributos
            $payload = JWTAuth::setToken($token)->getPayload();

            // Obtiene todos los atributos del payload
            $attributes = $payload->toArray();

            // Devuelve los atributos del token en formato JSON
            return response()->json(['attributes' => $attributes], 200);
        } catch (JWTException $e) {
            // Si el token es inválido, devuelve un mensaje de error
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }
}
