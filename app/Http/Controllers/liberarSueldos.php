<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class liberarSueldos extends Controller
{
    //

    public function InsertSueldo(Request $request){

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        $planilla = new \App\SueldosLiquidacion;
        $planilla->haberesImponibles = json_encode($post["imponibles"]);
        $planilla->haberesNoImponibles= json_encode($post["noimponibles"]);
        $planilla->trabajador_id= $post["trabajador_id"];
        $planilla->dias_calendarizados= $post["dias_calendarizados"];
        $planilla->empresa= $post["empresa"];
        $planilla->dias_trabajados= $post["dias_trabajados"];
        $planilla->sueldo_escrito= $post["sueldo_escrito"];
        $planilla->descuentos= json_encode($post["descuentos"]);       
        $planilla->mes_pagado= $post["mesEnCurso"];
        $planilla->anio_pagado= $post["anioEnCurso"]; 
        $planilla->sueldoLiquido= $post["sueldoLiquido"];
        $planilla->totalHaberesImp= $post["totalHaberesImp"];        
        $planilla->totalHaberesTotales= $post["totalHaberesTotales"];
        $planilla->totalDescuentos= $post["totalDescuentos"];
        $planilla->totalHaberesNoImp= $post["totalHaberesNoImp"];

        $planilla->save();
        echo json_encode($post);

    }

        public function getSueldoLiberado(Request $request){
            date_default_timezone_set('America/Santiago');
            $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
            echo json_encode($sueldos_tabla =  \App\SueldosLiquidacion::where('trabajador_id', $post["id"])->where('mes_pagado', $post['mes'])->where('anio_pagado', $post['anio'])->count());

        

    }


    public function getSueldosLiberados(Request $request){
           date_default_timezone_set('America/Santiago');
            $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
            $sueldos_liberados =  \App\SueldosLiquidacion::where('trabajador_id', $post["id"])->get()->toJson();
            echo $sueldos_liberados;
        }



        public function getSueldosLiberadosPorFecha(Request $request){
           date_default_timezone_set('America/Santiago');
            $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
            $sueldos_liberados =  \App\SueldosLiquidacion::where('trabajador_id', $post["id"])
            ->where('trabajador_id', $post["id"])
            ->where('mes_pagado', $post["mes"])
            ->where('anio_pagado', $post["anio"])
            ->get()->toJson();
            echo $sueldos_liberados;
        }
}
