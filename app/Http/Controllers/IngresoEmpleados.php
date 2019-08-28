<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use File;
use Storage;

class IngresoEmpleados extends Controller
{

    public $fracasos = 0;
    //
    public function Enrolamiento(Request $request){

    	$post = $request->json()->all();

        $jefatura = \App\jefaturas::where('id', $post['jefatura_id'])->get();
        $cargos = \App\cargos::where('id', $post['cargo_id'])->get();
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();
    	$planilla = new \App\ingreso_empleados;

    	foreach ($post as $key => $value) {
    		$planilla->$key = $value;
    	}

        $planilla->jefatura = $jefatura[0]['nombre'];
        $planilla->cargo_nombre = $cargos[0]['cargo'];
        $planilla->sucursal_nombre = $sucursal_nombre[0]['nombre'];

    	$planilla->save();
    	echo json_encode($post);

    }


            //
    private function Enrolamiento_por_importacion($post){

        $jefatura = \App\jefaturas::where('id', $post['jefatura_id'])->get();
        $cargos = \App\cargos::where('id', $post['cargo_id'])->get();
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();
        $planilla = new \App\ingreso_empleados;
        $empresa =  \App\clientes_rrhh::where('nombre_empresa', $post['nombre_empresa_usuario_plataforma'])->get();
        // Comprobar que $post['nombre_empresa_usuario_plataforma'] === 

        $planilla = \App\ingreso_empleados::all();

        if($post['id'] == ""){

          $id =  ($planilla->last()->id + 1);
        
        }else{

          $id =  $post['id'];

        }
        \App\ingreso_empleados::updateOrCreate([ 'id' => $post['id'] ], $post);

        
        \App\contraste_fotografico_validacion::updateOrCreate(['trabajador_id' => $post['id']],
            ['trabajador_id' => $post['id'], 'empresa_id' =>  $empresa[0]['id'], 'validado'=> 'false']

        );

        /*
        $contraste = new \App\contraste_fotografico_validacion;
        $contraste->trabajador_id = $post['id'];
        $contraste->empresa_id = $empresa[0]['id'];
        $contraste->validado = 'false';
        $contraste->save();  */

    }


    /**
     * @param File CSV 
     */
    public function Importacion_Trabajadores(Request $request){

            $this->fracasos = 0;
            $validador = Validator::make( $request->all(), $this->parametros_array() );

            if($validador->fails()){
                #Falla por no cumplimiento
                return response()->json(
                    ['data'=> $validador->errors(), 'code'=> 404]
                );

            }else{
                #Subir el archivo...
                $filename = $request->file('filename');
                $extension = $filename->getClientOriginalExtension();
                $nombreArchivo = $filename->getFilename().'.'.$extension;
                Storage::disk('public')->put($nombreArchivo, File::get($filename));                 
                $collection = (new FastExcel)->configureCsv(';', '#', '\n')->import(storage_path('app/public/'.$nombreArchivo), function ($line) use ($request) {                
                    
                    if( strcmp($request->input('nombre_empresa'), $line['nombre_empresa_usuario_plataforma']) !== 0  ){
                        
                        $this->fracasos++;
                        
                        return response()->json(
                            ['response' => 'error', 'error' => 'Debes establecer con claridad el nombre y el rut de la empresa en el importable']
                        );

                    }else{                        

                        $this->Enrolamiento_por_importacion($line); //echo $line['Valor del HB']. '<br>';

                    } 
                  
                 
                });

                return response()->json(
                    ['response' => 'Ok', 'fracasos' => $this->fracasos ]
                );

            }

        }


        /**
         * Devuelve un array.
         *
         * @return array
         */
        public function parametros_array()
        {
            return [
                'name' => 'min:5',
            ];
        }



        public function Actualizacion_registro_Trabajadores(Request $request){

        $post = $request->json()->all();

        $planilla =  \App\ingreso_empleados::where('id', $post['id']);
        
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();

        //echo "post['sucursal_id']" . $post['sucursal_id'];

        //echo $sucursal_nombre[0]['nombre'];
        
        foreach ($post as $key => $value) {
            $planilla->update([$key => $value]);
        }

        $planilla->update(['sucursal_nombre' => $sucursal_nombre[0]['nombre']]);
        
        echo json_encode($post);

    }


}
