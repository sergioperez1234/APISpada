<?php

namespace App\Http\Controllers;
use App\Models\Imagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class imagenesController extends Controller
{

    public function index(){
        $imagenes = Imagenes::all();

        if ($imagenes->isEmpty()) {

            $data = [
                'status' => 200,
                'message' => 'No se encontraron imagenes',
            ];
            return response()->json($data, 200);
        }

        $data = [
            'imagenes' => $imagenes,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'url' => 'required|string|max:2048'
        ]);
        

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $imagen = Imagenes::create([
            'url' => $request->url
        ]);

        if (!$imagen) {

            $data = [
                'status' => 500,
                'message' => 'Error al crear la imagen'
            ];
            return response()->json($data, 500);
        }

        $data = [
            'status' => 201,
            'imagen' => $imagen,
            'message' => 'Imagen creada correctamente'
        ];
        return response()->json($data, 201);
    }

    public function show($id){
        $imagen = Imagenes::find($id);
        if(!$imagen){
            $data = [
                'status' => 404,
                'message' => 'No se encontro la imagen'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'imagen' => $imagen
        ];
        return response()->json($data, 200);
    }

    public function mostrarImagen($id) {
        // Hacer una solicitud GET a la API
        $response = Http::get(url("/api/imagenes/$id"));
    
        // Decodificar el JSON
        $data = $response->json();
    
        // Extraer la URL de la imagen
        $url = $data['imagen']['url'];
    
        // Asegurarse de que la URL comienza con una barra diagonal
        if (!Str::startsWith($url, '/')) {
            $url = '/' . $url;
        }
    
        // Preparar la URL completa
        $url = url($url);
    
        return view('vista', ['url' => $url]);
    }

    public function update(Request $request, $id){
        $imagen = Imagenes::find($id);

        if(!$imagen){
            $data = [
                'status' => 404,
                'message' => 'No se encontro la imagen'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:2048'
        ]);

        if ($validator->fails()) {

            $data = [
                'status' => 400,
                'errors' => $validator->errors(),
                'message' => 'Error de validacion de los datos'
            ];
            return response()->json($data, 400);
        }

        $imagen->update([
            'url' => $request->url
        ]);

        $data = [
            'status' => 200,
            'imagen' => $imagen,
            'message' => 'Imagen actualizada correctamente'
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
        $imagen = Imagenes::find($id);
        if(!$imagen){
            $data = [
                'status' => 404,
                'message' => 'No se encontro la imagen'
            ];
            return response()->json($data, 404);
        }

        $imagen->delete();
        $data = [
            'status' => 200,
            'message' => 'Imagen eliminada correctamente'
        ];
        return response()->json($data, 200);
    }

}
