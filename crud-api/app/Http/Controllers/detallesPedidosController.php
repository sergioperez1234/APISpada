<?php

namespace App\Http\Controllers;
use App\Models\detallesPedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class detallesPedidosController extends Controller
{

    public function index(){
        $detallesPedidos = detallesPedidos::all();

        if ($detallesPedidos->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron detallesPedidos',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'detallesPedidos' => $detallesPedidos,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'id_pedido' => 'required|integer',
            'id_pieza' => 'required|integer',
            'cantidad' => 'required|integer'
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $detallesPedidos = detallesPedidos::create([
            'id_pedido' => $request->id_pedido,
            'id_pieza' => $request->id_pieza,
            'cantidad' => $request->cantidad
        ]);

        if (!$detallesPedidos) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear el detallesPedidos'
            ];
            return response()->json($data, 500);
        }

        $data = [
            'status' => 201,
            'detallesPedidos' => $detallesPedidos,
            'message' => 'DetallesPedidos creado correctamente'
        ];
        return response()->json($data, 201);
    }

    public function show($id){
        $detallesPedidos = detallesPedidos::find($id);
        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el detallesPedidos'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'detallesPedidos' => $detallesPedidos
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
        $detallesPedidos = detallesPedidos::find($id);
        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el detallesPedidos'
            ];
            return response()->json($data, 404);
        }

        $detallesPedidos->delete();
        $data = [
            'status' => 200,
            'message' => 'DetallesPedidos eliminado correctamente'
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $detallesPedidos = detallesPedidos::find($id);

        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontro el detallesPedidos'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pedido' => 'required|integer',
            'id_pieza' => 'required|integer',
            'cantidad' => 'required|integer'
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $detallesPedidos->update([
            'id_pedido' => $request->id_pedido,
            'id_pieza' => $request->id_pieza,
            'cantidad' => $request->cantidad
        ]);

        $data = [
            'status' => 200,
            'detallesPedidos' => $detallesPedidos,
            'message' => 'DetallesPedidos actualizado correctamente'
        ];
        return response()->json($data, 200);
    }
}
