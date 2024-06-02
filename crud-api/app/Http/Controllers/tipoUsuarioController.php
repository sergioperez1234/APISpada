<?php

namespace App\Http\Controllers;
use App\Models\TipoUsuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class tipoUsuarioController extends Controller
{

    public function index()
    {
        $tipos = TipoUsuarios::all();

        if ($tipos->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron tipos de usuarios',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'tipos' => $tipos,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $tipo = TipoUsuarios::create([
            'tipo' => $request->tipo
        ]);

        if (!$tipo) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear el tipo de usuario'
            ];
            return response()->json($data, 500);
        }

        $data = [
            'status' => 201,
            'tipo' => $tipo,
            'message' => 'Tipo de usuario creado correctamente'
        ];
        return response()->json($data, 201);

    }

    public function show($id){

        $tipo = TipoUsuarios::find($id);
        if (!$tipo) {
            $data = [
                'status' => 404,
                'message' => 'Tipo de usuario no encontrado'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'tipo' => $tipo
        ];
        return response()->json($data, 200);
    }


    public function update(Request $request, $id){
        $tipo = TipoUsuarios::find($id);

        if(!$tipo){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el tipo de usuario'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $tipo->tipo = $request->tipo;

        $tipo->save();

        $data = [
            'status' => 200,
            'message' => 'Tipo de usuario actualizado correctamente'
        ];
        return response()->json($data, 200);
    }
}
