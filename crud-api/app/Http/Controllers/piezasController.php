<?php

namespace App\Http\Controllers;
use App\Models\piezas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class piezasController extends Controller
{

    public function index(){
        $piezas = Piezas::all();

        if ($piezas->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron piezas',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'piezas' => $piezas,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        // Decodificar el JSON recibido
        $data = $request->json()->all();

        // Validar los datos JSON
        $validator = Validator::make($data, [
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'tipo' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'imagen' => 'required|string' // Validación para la imagen en base64
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validación de los datos'
            ];
            return response()->json($response, 400);
        }

        // Decodificar la imagen base64
        $imageData = $data['imagen'];

        // Verificar el tipo de imagen
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            // Verificar si el tipo es uno de los formatos permitidos
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Formato de imagen no soportado'
                ], 400);
            }

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Error al decodificar la imagen'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Formato de imagen no válido'
            ], 400);
        }

        // Guardar la imagen en la carpeta public/images
        $imageName = time() . '.' . $type;
        $imagePath = public_path('images') . '/' . $imageName;
        file_put_contents($imagePath, $imageData);

        // Crear la pieza
        $pieza = Piezas::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'tipo' => $data['tipo'],
            'modelo' => $data['modelo'],
            'imagen' => 'images/' . $imageName // Guardar la ruta de la imagen
        ]);

        if (!$pieza) {
            $response = [
                'status' => 500,
                'message' => 'Error al crear la pieza'
            ];
            return response()->json($response, 500);
        }

        $response = [
            'status' => 201,
            'pieza' => $pieza,
            'message' => 'Pieza creada correctamente'
        ];
        return response()->json($response, 201);
    }

    public function show($id){
        $piezas = Piezas::find($id);
        if(!$piezas){
            $data = [
                'status' => 404,
                'message' => 'No se encontro la pieza'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'piezas' => $piezas
        ];
        return response()->json($data, 200);
    }

    public function search(Request $request)
{
    // Obtener los parámetros de la solicitud
    $tipo = $request->input('tipo', -1);
    $minPrecio = $request->input('minPrecio', -1);
    $maxPrecio = $request->input('maxPrecio', -1);
    $nombre = $request->input('nombre', -1);
    $ordenacion = $request->input('ordenacion', 'ascendente'); // 'ascendente' por defecto

    // Construir la consulta
    $query = Piezas::query();

    if ($tipo != -1) {
        $query->where('tipo', $tipo);
    }

    if ($minPrecio != -1) {
        $query->where('precio', '>=', $minPrecio);
    }

    if ($maxPrecio != -1) {
        $query->where('precio', '<=', $maxPrecio);
    }

    if ($nombre != -1) {
        $query->where('nombre', 'like', '%' . $nombre . '%');
    }

    // Convertir 'ascendente' y 'descendente' a 'asc' y 'desc'
    if ($ordenacion == 'ascendente') {
        $query->orderBy('precio', 'asc');
    } elseif ($ordenacion == 'descendente') {
        $query->orderBy('precio', 'desc');
    } else {
        return response()->json(['error' => 'El parámetro ordenacion debe ser "ascendente" o "descendente"'], 400);
    }

    // Ejecutar la consulta y obtener los resultados
    $piezas = $query->get();

    // Comprobar si hay resultados
    if ($piezas->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'No se encontraron piezas que coincidan con los criterios'
        ], 404);
    }

    // Devolver los resultados
    return response()->json([
        'status' => 200,
        'piezas' => $piezas
    ], 200);
}

    
    public function destroy($id){
        $piezas = Piezas::find($id);
        if(!$piezas){
            $data = [
                'status' => 404,
                'message' => 'No se encontro la pieza'
            ];
            return response()->json($data, 404);
        }

        $piezas->delete();
        $data = [
            'status' => 200,
            'message' => 'Pieza eliminada correctamente'
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
{
    $pieza = Piezas::find($id);

    if (!$pieza) {
        $data = [
            'status' => 404,
            'message' => 'No se encontró la pieza'
        ];
        return response()->json($data, 404);
    }

    // Decodificar el JSON recibido
    $data = $request->json()->all();

    // Validar los datos JSON
    $validator = Validator::make($data, [
        'nombre' => 'required|string|max:50',
        'descripcion' => 'nullable|string|max:255',
        'precio' => 'required|numeric|min:0',
        'tipo' => 'required|string|max:50',
        'modelo' => 'required|string|max:50',
        'imagen' => 'required|string' // Validación para la imagen en base64
    ]);

    if ($validator->fails()) {
        $response = [
            'status' => 400,
            'errors' => $validator->errors(),
            'message' => 'Error de validación de los datos'
        ];
        return response()->json($response, 400);
    }

    // Decodificar la imagen base64
    $imageData = $data['imagen'];

    // Verificar el tipo de imagen
    if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
        $imageData = substr($imageData, strpos($imageData, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        // Verificar si el tipo es uno de los formatos permitidos
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            return response()->json([
                'status' => 400,
                'message' => 'Formato de imagen no soportado'
            ], 400);
        }

        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            return response()->json([
                'status' => 400,
                'message' => 'Error al decodificar la imagen'
            ], 400);
        }
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'Formato de imagen no válido'
        ], 400);
    }

    // Guardar la imagen en la carpeta public/images
    $imageName = time() . '.' . $type;
    $imagePath = public_path('images') . '/' . $imageName;
    file_put_contents($imagePath, $imageData);

    // Actualizar la pieza
    $pieza->update([
        'nombre' => $data['nombre'],
        'descripcion' => $data['descripcion'],
        'precio' => $data['precio'],
        'tipo' => $data['tipo'],
        'modelo' => $data['modelo'],
        'imagen' => 'images/' . $imageName // Guardar la ruta de la imagen
    ]);

    $data = [
        'status' => 200,
        'pieza' => $pieza,
        'message' => 'Pieza actualizada correctamente'
    ];
    return response()->json($data, 200);
}

}
