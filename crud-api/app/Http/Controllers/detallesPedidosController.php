<?php

namespace App\Http\Controllers;
use App\Models\detallesPedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class detallesPedidosController extends Controller
{

    /**
     * Esta función retorna todos los detallesPedidos registrados en la base de datos.
     * Si no hay ninguno, retorna un mensaje de estado 200 con el mensaje 'No se encontraron detallesPedidos'.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $detallesPedidos = detallesPedidos::all();

        // Si no hay detallesPedidos registrados, retorna un mensaje de estado 200 con el mensaje 'No se encontraron detallesPedidos'
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

    /**
     * Esta función crea un nuevo detalle de pedido en la base de datos.
     * Recibe los datos del pedido en formato JSON a través de la petición HTTP.
     * Si todos los datos son válidos, retorna un mensaje de estado 201 con el mensaje 'DetallesPedidos creado correctamente'.
     * Si hay algún error de validación, retorna un mensaje de estado 400 con los errores de validación.
     * Si hay algún error al crear el detalle de pedido, retorna un mensaje de estado 500 con el mensaje 'Error al crear el detalle de pedido'.
     *
     * @param \Illuminate\Http\Request $request Petición HTTP con los datos del detalle de pedido
     * @return \Illuminate\Http\JsonResponse Mensaje de estado y mensaje de respuesta
     */
    public function store(Request $request){

        // Valida los datos recibidos
        $validator = Validator::make($request->all(), [
            'id_pedido' => 'required|integer',
            'id_pieza' => 'required|integer',
            'cantidad' => 'required|integer'
        ]);

        // Si hay algún error de validación, retorna un mensaje de estado 400 con los errores de validación
        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ];
            return response()->json($data, 400);
        }

        // Crea un nuevo detalle de pedido con los datos recibidos
        $detallesPedidos = detallesPedidos::create([
            'id_pedido' => $request->id_pedido,
            'id_pieza' => $request->id_pieza,
            'cantidad' => $request->cantidad
        ]);

        // Si no se pudo crear el detalle de pedido, retorna un mensaje de estado 500 con el mensaje 'Error al crear el detalle de pedido'
        if (!$detallesPedidos) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear el detalle de pedido'
            ];
            return response()->json($data, 500);
        }

        // Retorna un mensaje de estado 201 con el mensaje 'DetallesPedidos creado correctamente' y el detalle de pedido creado
        $data = [
            'status' => 201,
            'detallesPedidos' => $detallesPedidos,
            'message' => 'DetallesPedidos creado correctamente'
        ];
        return response()->json($data, 201);
    }

    /**
     * Esta función retorna los detalles de un pedido por su ID.
     * Si no se encuentra el detalle de pedido, retorna un mensaje de estado 404 con el mensaje 'No se encontró el detalle de pedido'
     * Si se encuentra el detalle de pedido, retorna un mensaje de estado 200 con el mensaje 'DetallesPedidos encontrados' y los detalles del pedido
     *
     * @param int $id ID del detalle de pedido a buscar
     * @return \Illuminate\Http\JsonResponse Mensaje de estado y mensaje de respuesta
     */
    public function show($id){
        $detallesPedidos = detallesPedidos::find($id);
        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el detalle de pedido'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'detallesPedidos' => $detallesPedidos
        ];
        return response()->json($data, 200);
    }

    /**
     * Esta función elimina un detalle de pedido por su ID.
     * Si no se encuentra el detalle de pedido, retorna un mensaje de estado 404 con el mensaje 'No se encontró el detalle de pedido'
     * Si se encuentra el detalle de pedido, retorna un mensaje de estado 200 con el mensaje 'DetallesPedidos eliminado correctamente'
     *
     * @param int $id ID del detalle de pedido a eliminar
     * @return \Illuminate\Http\JsonResponse Mensaje de estado y mensaje de respuesta
     */
    public function destroy($id){
        // Busca el detalle de pedido por su ID
        $detallesPedidos = detallesPedidos::find($id);
        
        // Si no se encuentra el detalle de pedido, retorna un mensaje de estado 404
        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el detalle de pedido'
            ];
            return response()->json($data, 404);
        }

        // Elimina el detalle de pedido
        $detallesPedidos->delete();
        
        // Retorna un mensaje de estado 200 con el mensaje 'DetallesPedidos eliminado correctamente'
        $data = [
            'status' => 200,
            'message' => 'DetallesPedidos eliminado correctamente'
        ];
        return response()->json($data, 200);
    }

    /**
     * Esta función actualiza un detalle de pedido por su ID.
     * Si no se encuentra el detalle de pedido, retorna un mensaje de estado 404 con el mensaje 'No se encontró el detalle de pedido'
     * Si se encuentra el detalle de pedido, retorna un mensaje de estado 200 con el mensaje 'DetallesPedidos actualizado correctamente'
     *
     * @param \Illuminate\Http\Request $request Petición HTTP con los datos del detalle de pedido
     * @param int $id ID del detalle de pedido a actualizar
     * @return \Illuminate\Http\JsonResponse Mensaje de estado y mensaje de respuesta
     */
    public function update(Request $request, $id){
        // Busca el detalle de pedido por su ID
        $detallesPedidos = detallesPedidos::find($id);
        
        // Si no se encuentra el detalle de pedido, retorna un mensaje de estado 404
        if(!$detallesPedidos){
            $data = [
                'status' => 404,
                'message' => 'No se encontró el detalle de pedido'
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
                'message' => 'Error de validación de los datos'
            ];
            return response()->json($data, 400);
        }

        // Actualiza el detalle de pedido
        $detallesPedidos->update([
            'id_pedido' => $request->id_pedido,
            'id_pieza' => $request->id_pieza,
            'cantidad' => $request->cantidad
        ]);
        
        // Retorna un mensaje de estado 200 con el mensaje 'DetallesPedidos actualizado correctamente' y el detalle de pedido actualizado
        $data = [
            'status' => 200,
            'detallesPedidos' => $detallesPedidos,
            'message' => 'DetallesPedidos actualizado correctamente'
        ];
        return response()->json($data, 200);
    }
}
