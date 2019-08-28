<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Postulantes extends Controller
{
    //

    
        public function getPostulantes($id)
    {
        //

        $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $id)
        ->where('estatus', 'Postulante')
        ->orderBy('apellido')->get();
        //echo json_encode($planilla->toarray(),JSON_PARTIAL_OUTPUT_ON_ERROR);
        //echo $planilla->toJson();

        return response()->json($planilla);

    }   


            public function getTodos($id)
    {
        //

        $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $id)
        ->orderBy('apellido')->get();
        //echo json_encode($planilla->toarray(),JSON_PARTIAL_OUTPUT_ON_ERROR);
        //echo $planilla->toJson();

        return response()->json($planilla);

    }
}
