<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucursalController extends Controller
{
    //

    public function ingreso_sucursal(Request $request){

    	$post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
    	$nuevo_ingreso_sucursal = new \App\sucursales;
    	$nuevo_ingreso_sucursal->nombre = $post['descripcion'];   // Nombre descriptivo
    	$nuevo_ingreso_sucursal->direccion = $post['address_level_1'];
    	$nuevo_ingreso_sucursal->comuna = $post['address_level_2'];
    	$nuevo_ingreso_sucursal->ciudad = $post['address_country'];
    	$nuevo_ingreso_sucursal->usuario = $post['usuario']; // nombre empresa
    	$nuevo_ingreso_sucursal->latitud = $post['lat'];
        $nuevo_ingreso_sucursal->longitud = $post['lng'];
        $nuevo_ingreso_sucursal->telefono = $post['telefono'];
		$nuevo_ingreso_sucursal->save();
        echo json_encode(1);
    }




    public function get_sucursales(Request $request){

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        $nuevo_ingreso_sucursal =  \App\sucursales::where('usuario', $post['usuario'])->get();
        echo json_encode($nuevo_ingreso_sucursal);      
    }


        public function update_sucursales(Request $request){

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        $editar_sucursal =  \App\sucursales::where('id', $post['id']);
        $editar_sucursal->update(['telefono' => $post['telefono']]);
        echo json_encode(array("estatus" => true));      
    }
}
