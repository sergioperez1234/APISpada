<?php

namespace App\Http\Controllers;
use App\Models\pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class pedidosController extends Controller
{

    public function index(){
        $pedidos = pedidos::all();

        if ($pedidos->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron pedidos',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'pedidos' => $pedidos,
            'status' => 200
        ]; 

        return response()->json($data, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'idComprador' => 'required|digits:9',
            'precio' => 'required|digits:9'
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $pedidos = pedidos::create([
            'idComprador' => $request->idComprador,
            'precio' => $request->precio
        ]);

        if (!$pedidos) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear el pedido'
            ];
            return response()->json($data, 500);
        }

        $data = [
            'status' => 201,
            'pedidos' => $pedidos,
            'message' => 'Pedido creado correctamente'
        ];
        return response()->json($data, 201);
    }

    public function show($id){
        $pedidos = pedidos::find($id);
        if(!$pedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el pedido'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'pedidos' => $pedidos
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
        $pedidos = pedidos::find($id);
        if(!$pedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el pedido'
            ];
            return response()->json($data, 404);
        }

        $pedidos->delete();
        $data = [
            'status' => 200,
            'message' => 'Pedido eliminado correctamente'
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $pedidos = pedidos::find($id);

        if(!$pedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el pedido'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'idComprador' => 'required|digits:9',
            'precio' => 'required|digits:9'
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $pedidos->idComprador = $request->idComprador;
        $pedidos->precio = $request->precio;
        $pedidos->save();
        $data = [
            'status' => 200,
            'pedidos' => $pedidos,
            'message' => 'Pedido actualizado correctamente'
        ];
        return response()->json($data, 200);
    }
}
