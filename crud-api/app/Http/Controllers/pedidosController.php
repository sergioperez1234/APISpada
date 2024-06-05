<?php

namespace App\Http\Controllers;
use App\Models\pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\detallesPedidos;

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

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'precio' => 'required|integer',
        'productos' => 'required|array'
    ]);

    if ($validator->fails()) {
        $data = [
            'status' => 400,
            'errors' => $validator->errors(),
            'message' => 'Error de validacion de los datos'
        ];
        return response()->json($data, 400);
    }

    // Extract the idUsuario and idComprador from the token
    try {
        $token = $request->bearerToken();
        $payload = JWTAuth::setToken($token)->getPayload();
        $idUsuario = $payload->get('sub');
        $idComprador = $payload->get('sub'); // Assuming idComprador is the same as idUsuario
    } catch (JWTException $e) {
        return response()->json(['error' => 'Token inválido'], 401);
    }

    DB::beginTransaction();

    try {
        // Create the pedido
        $pedido = pedidos::create([
            'idComprador' => $idComprador,
            'precio' => $request->precio,
            'idUsuario' => $idUsuario
        ]);

        // Create the detallesPedidos
        foreach ($request->productos as $producto) {
            $validator = Validator::make($producto, [
                'idPieza' => 'required|integer',
                'cantidad' => 'required|integer'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                $data = [
                    'status' => 400,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validacion de los datos del producto'
                ];
                return response()->json($data, 400);
            }

            DetallesPedidos::create([
                'idPedido' => $pedido->id,
                'idPieza' => $producto['idPieza'],
                'cantidad' => $producto['cantidad']
            ]);
        }

        DB::commit();

        $data = [
            'status' => 201,
            'pedido' => $pedido,
            'message' => 'Pedido y detalles creados correctamente'
        ];
        return response()->json($data, 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear el pedido o los detalles: ' . $e->getMessage());
        $data = [
            'status' => 500,
            'message' => 'Error al crear el pedido o los detalles',
            'error' => $e->getMessage()
        ];
        return response()->json($data, 500);
    }
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

    public function registrarPedido(Request $request){
        $pedidos = pedidos::create([
            'idComprador' => $request->idComprador,
            'precio' => $request->precio
        ]);
    }
}
