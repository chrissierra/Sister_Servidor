<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarcajeDesdeApp extends Controller
{
    //
    public function guardarImagenesProcesoBiometricoEnMarcaje(Request $request){
    	$post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
    	   // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $request->image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(10).'.'.'png';
        \File::put(storage_path(). '/' . $imageName, base64_decode($image));
    	$arrayResponse = array('Labase64' => 'hola');
    	return json_encode($request->image);

    }
}
