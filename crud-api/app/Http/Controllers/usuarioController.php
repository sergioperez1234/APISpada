<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class usuarioController extends Controller
{
    public function index(){
        $usuarios = Usuario::all();

        if ($usuarios->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron usuarios',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'usuarios' => $usuarios,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:usuarios',
            'password' => 'required|string|min:6|max:16',
            'telefono' => 'required|digits:9',
            'tipoUsuario' => 'required|digits:1',
            'direccion' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' =>$request->password,
            'telefono' => $request->telefono,
            'tipoUsuario' => $request->tipoUsuario,
            'direccion' => $request->direccion
        ]);

        if (!$usuario) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear el usuario'
            ];
            return response()->json($data, 500);
        }

        $data = [
            'status' => 201,
            'usuario' => $usuario,
            'message' => 'Usuario creado correctamente'
        ];
        return response()->json($data, 201);

    }

    public function show($id){
        $usuario = Usuario::find($id);
        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el usuario'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'usuario' => $usuario
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
        $usuario = Usuario::find($id);
        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el usuario'
            ];
            return response()->json($data, 404);
        }

        $usuario->delete();
        $data = [
            'status' => 200,
            'message' => 'Usuario eliminado correctamente'
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $usuario = Usuario::find($id);

        if(!$usuario){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el usuario'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:usuarios,email,'.$id,
            'password' => 'required|string|min:6|max:16',
            'telefono' => 'required|digits:9',
            'tipoUsuario' => 'required|digits:1',
            'direccion' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->telefono = $request->telefono;

        $usuario->save();

        $data = [
            'status' => 200,
            'usuario' => $usuario,
            'message' => 'Usuario actualizado correctamente'
        ];
        return response()->json($data, 200);
    }

    //Metodo login comparar datos y devolver true/false

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:50',
        'password' => 'required|string|min:6|max:16',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->errors(),
            'message' => 'Error de validación de los datos'
        ], 400);
    }

    $email = $request->email;
    $password = $request->password;

    $usuario = Usuario::where('email', $email)->first();

    if (!$usuario || !Hash::check($password, $usuario->password)) {
        return response()->json([
            'status' => 404,
            'message' => 'No se encontró el usuario o la contraseña es incorrecta'
        ], 404);
    }

    return response()->json([
        'status' => 200,
        'usuario' => $usuario
    ], 200);
}


    //Metodo crear cuenta 

}
