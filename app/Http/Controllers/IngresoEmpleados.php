<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use File;
use Storage;

class IngresoEmpleados extends Controller
{
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


    private function verificar_si_existe_registro(){

    }



            //
    private function Enrolamiento_por_importacion($post){

        $jefatura = \App\jefaturas::where('id', $post['jefatura_id'])->get();
        $cargos = \App\cargos::where('id', $post['cargo_id'])->get();
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();
        $planilla = new \App\ingreso_empleados;

       // foreach ($post as $key => $value) {
       //     $planilla->$key = $value;
       // }

        echo "post['jefatura_id']-> " . $post['jefatura_id'];

        foreach ($post as $key => $value) {
            $planilla->updateOrCreate([ 'id' => $post['id'] ], [$key => $value]);
        }

        $planilla->jefatura = $jefatura[0]['nombre'];
        $planilla->cargo_nombre = $cargos[0]['cargo'];
        $planilla->sucursal_nombre = $sucursal_nombre[0]['nombre'];
        $planilla->save(); 

    }


    /**
     * @param File CSV 
     */
    public function Importacion_Trabajadores(Request $request){

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
                Storage::disk('public')->put($nombreArchivo, File::get($filename)); //$contents = Storage::get('public/'.$nombreArchivo);                
                $collection = (new FastExcel)->configureCsv( '\t')->import(storage_path('app/public/'.$nombreArchivo), function ($line) {                
                  // $this->Enrolamiento_por_importacion($line); //echo $line['Valor del HB']. '<br>';
                    dd($line);
                });

                return response()->json(
                    ['response' => 'Ok']
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
