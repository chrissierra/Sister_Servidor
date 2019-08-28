<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Postulantes extends Controller
{
    //


        public function getPostulantes(Request $request)
    {
        //
		$post = $request->json()->all(); 
        $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $post['nombre_empresa_usuario_plataforma'])
        ->where('estatus', 'Postulante')
        ->orderBy('apellido')->get();
        //echo json_encode($planilla->toarray(),JSON_PARTIAL_OUTPUT_ON_ERROR);
        //echo $planilla->toJson();

        return response()->json($planilla);

    }   


            public function getTodos(Request $request)
    {
        //
		$post = $request->json()->all(); 
        $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $post['nombre_empresa_usuario_plataforma'])
        ->orderBy('apellido')->get();
        //echo json_encode($planilla->toarray(),JSON_PARTIAL_OUTPUT_ON_ERROR);
        //echo $planilla->toJson();

        return response()->json($planilla);

    }
}
