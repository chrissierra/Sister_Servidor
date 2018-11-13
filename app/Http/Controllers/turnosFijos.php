<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class turnosFijos extends Controller
{

    public $diasFaltados = [];
    public $diaEnCursoEstado;

    function __contruct(){
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->json()->all();
        
        $tablaTurnoFijo = new \App\turnosFijos;

         foreach ($post as $key => $value) {
	           # code...
	      		  $tablaTurnoFijo->$key = $value;
	      }

        $tablaTurnoFijo->save();
       
       
    }



        /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTurnos(Request $request)
    {
        $post = $request->json()->all();
        
        $tablaTurnoFijo =  \App\turnosFijos::where('trabajador_id', $post['trabajador_id']);

         foreach ($post as $key => $value) {
	           # code...
	      		  $tablaTurnoFijo->update([$key => $value]);
	      }

      
       
       
    }


        /**
     * GET turnos from DB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTurnos(Request $request)
    {
        $post = $request->json()->all();
        
        $tablaTurnoFijo =  \App\turnosFijos::where('trabajador_id', $post['trabajador_id']);

         return json_encode($tablaTurnoFijo->get());
       
       
    }


    public function DiasTrabajados(Request $request){

        $diasFaltados = 0;

        $post = $request->json()->all();

        $id = $post['id'];

        date_default_timezone_set('America/Santiago');
        
        $planilla = \App\turnosfijos::where('trabajador_id', $id);
        
        if($planilla->count() == 0)
            return 0;

        $first_of_month = mktime (0,0,0, (date('n')-1) , 1, date('o')); 
        
        $diasdelmesanterior = date('t', $first_of_month);

        $horasTrabajadas = 0;
        $esperado = 0;
        
        for ($i=1; $i < $diasdelmesanterior; $i++) { 
           $diaSemana= -1 + date("N", mktime(0, 0, 0, (date('n')-1),  $i, date('o')));
           $dia_e =   $diaSemana . 'e';
           $dia_s =   $diaSemana . 's';

       
           if(strpos($planilla->get()[0][$dia_e], ':') > 1){

                $esperado += (explode(':', $planilla->get()[0][$dia_s])[0] +  (explode(':', $planilla->get()[0][$dia_s])[1] / 60) )-(explode(':', $planilla->get()[0][$dia_e])[0] +  (explode(':', $planilla->get()[0][$dia_e])[1] / 60) );

             # Quiere decir que trabaja ese día
            $cuantiaEntrada = $this->BuscandoEnTablaAsistencia($id, (date('n')-1),  date('o'), $i , 'entrada')[0]['cuantia_entrada'];

            $cuantiaSalida = $this->BuscandoEnTablaAsistencia($id, (date('n')-1),  date('o'), $i , 'salida')[0]['cuantia_salida'];

            $horasTrabajadas += $this->EstableceAusentismo($cuantiaEntrada, $cuantiaSalida);

            $this->diaEnCursoEstado = 0;
                     
            //$horasTrabajadas += $cuantiaSalida - $cuantiaEntrada;

           }else{
             # No trabaja ese día  |      VER EVENTUALES HORAS EXTRAS ... 

           }
            # code...
        }

        $valorRetornar = array('horasTrabajadas'=> $horasTrabajadas, 'esperado' => $esperado, 'DiasFaltados' => count(array_unique($this->diasFaltados)) );
        return json_encode($valorRetornar);

    } // ******* FIN FUNCION DIASTRABAJADOS  *********


    private function BuscandoEnTablaAsistencia($id, $mes, $anio, $dia, $mov){
        //echo $dia . '<br>';

        $tablaAsistencia = \App\asistencia::where('id_trabajador', $id)
                            ->where('mes', $mes  ) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', $anio)
                            ->where('dia', $dia)
                            ->where('tipo_movimiento', $mov);
        
        if($tablaAsistencia->count() == 0){
                       
                        array_push($this->diasFaltados, $dia);
                        $this->diaEnCursoEstado = 1;
                        return 0;
                
        }else{
            return $tablaAsistencia->get();
        }

                            

    } // **** Fin funcíón BuscandoEnTablaAsistencia


    private function EstableceAusentismo($cuantiaEntrada, $cuantiaSalida){
           if($this->diaEnCursoEstado == 1 ){
                return 0;
                        }else{
                return $cuantiaSalida - $cuantiaEntrada;
            }
    }







}

// Recorrer cada día del mes anterior via date(), ver qué día de la semana es, contrastarlo con el día en tabla turnos y luego verlo en tabla asistencia
# -> 1. recorrer cada día del mes anterior
# -> 2. A cada día contrastar en tabla turnos primero : si se trabaja obtener hora entrada  y salida
# -> 3. Ir a tabla asistencia y hago query where ese día,  luego ver si se trabajó, guardar real  y planificado
