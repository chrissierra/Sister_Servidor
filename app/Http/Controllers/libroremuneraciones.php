<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class libroremuneraciones extends Controller
{
    //
    public $dia_e, $dia_s, $anio, $mes, $cuantia_esperada, $cuantia_diferencia_real_esperada, $horaEntrada, $horaSalida, $horarioNoche;
    function __construct(){

        date_default_timezone_set('America/Santiago');
        $this->tiempo = time();
        $this->mes = date('m')*1;
        $this->anio = date('Y');
        $this->dia_e = (date('d') *1) . 'e';
        $this->dia_s = (date('d') *1) . 's';
        $this->fecha = date('d/m/Y');
        $this->turnoExtraEnCurso = 0;
        $this->horarioNoche = 0;

    } // Fin función __construct


    public function diario(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])
    						->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->get();
    	return json_decode($tabla);
    	
    }


        public function diarioUltimos(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('usuario_cliente', $post['id'])
                            ->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->orderBy('id', 'desc')
                            ->take($post['ultimosN'])
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



        public function getmovimientounitario(Request $request){

        $post = $request->json()->all();

        $tabla = \App\asistencia::where('id', $post['id'])
                            ->get();

        return json_decode($tabla);
        
    }


       public function actualmenteTrabajando(Request $request){

        $post = $request->json()->all();
        $resultado = array();
        $tiempo_a = 13*60*60;
        $dif = $this->tiempo - $tiempo_a;

        $buscarTrabajador = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma',$post['id'] )
        ->get();

        foreach ($buscarTrabajador as $key => $value) {
            # code...
            $tabla = \App\asistencia::where('id_trabajador', $value['id'])
                            ->where('tiempo','>', $dif )
                            ->orderBy('id', 'desc')
                            ->first();

            if($tabla['tipo_movimiento'] === 'entrada'){
                array_push($resultado, $tabla);
            }
        }


       /* $tabla = \App\asistencia::where('usuario_cliente', $post['id'])
                            ->where('tiempo','>', $dif )
                            ->orderBy('id', 'desc')
                            ->take($post['ultimosN'])
                            ->get();*/

        return json_decode($tabla);
        
    }



   public function actualmenteTrabajandoPorSucursal(Request $request){

        $post = $request->json()->all();

        $tiempo_a = 13*60*60;
        $dif = $this->tiempo - $tiempo_a;

        $tabla = \App\asistencia::where('usuario_cliente', $post['id'])
                            ->where('tiempo','>', $dif ) // No debe decir $mes + 1 
                            ->where('sucursal',$post['sucursal']) // No debe decir $mes + 1 
                            ->orderBy('id', 'desc')
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
