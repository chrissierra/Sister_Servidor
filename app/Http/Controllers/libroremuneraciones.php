<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class libroremuneraciones extends Controller
{
    //
    public function diario(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])
    						->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->get();
    	return json_decode($tabla);
    	
    }

    public function mensual(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('usuario_cliente', $post['id'])
                            ->where('mes',$post['mes']) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', $post['anio'])                         
                            ->get();
        return json_decode($tabla);
        
    }



        public function diarioPorTrabajador(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('id_trabajador', $post['id'])
                            ->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->get();
        return json_decode($tabla);
        
    }


    public function mensualPorTrabajador(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('id_trabajador', $post['id'])
                            ->where('mes',$post['mes']) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', $post['anio'])                         
                            ->get();
        return json_decode($tabla);
        
    }


        public function mensualPorSucursal(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('sucursal', $post['sucursal'])
                            ->where('usuario_cliente', $post['id'])
                            ->where('mes',$post['mes']) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', $post['anio'])                         
                            ->get();
        return json_decode($tabla);
        
    }


        public function diarioPorSucursal(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('sucursal', $post['sucursal'])
                            ->where('usuario_cliente', $post['id'])
                            ->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->get();
        return json_decode($tabla);
        
    }



/*
     //
    public function mensual(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])
    						->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2]                         
                            ->get();
    	return json_decode($tabla);
    	
    }



        public function anual(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])    					
                            ->where('anio', explode('-', $post['dia'])[2]                         
                            ->get();
    	return json_decode($tabla);
    	
    }*/

}
