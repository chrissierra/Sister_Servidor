<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mandanteController extends Controller
{
    //
    public function ingresarMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = new \App\mandantes;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$mandantes->nombre_mandante = $post[0]['value'];
    	$mandantes->rut_mandante = $post[1]['value'];
    	$mandantes->clave = password_hash($post[2]['value'], PASSWORD_DEFAULT);
        $mandantes->claveTextual = $post[2]['value'];
    	$mandantes->proveedor_servicios = $post[3]['value'];
    	$mandantes->hitos =$post[4]['value'];
    	$mandantes->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizarMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = \App\mandantes::where('proveedor_servicios', $post[3]['value'])
    	->where('nombre_mandante',$post[0]['value']);   	

    	$mandantes->update(['nombre_mandante' => $post[0]['value']]);
    	$mandantes->update(['rut_mandante' => $post[1]['value']]);
    	$mandantes->update(['clave' => password_hash($post[2]['value'], PASSWORD_DEFAULT)  ]); 
        $mandantes->update(['claveTextual' => $post[2]['value']]); 
    	$mandantes->update(['proveedor_servicios' => $post[3]['value']]);
    	$mandantes->update(['hitos' =>$post[4]['value']]);

    }


    public function getMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = \App\mandantes::where('proveedor_servicios', $post['proveedor_servicios'])->get();         
    	return response()->json($mandantes);
    }

    public function deleteMandante(Request $request){
    	$post = $request->json()->all();
    	\App\mandantes::where('proveedor_servicios', $post[3]['value'])
    	->where('nombre_mandante',$post[0]['value'])->delete(); 
    	echo json_encode(array("estatus"=>'ok'));
    }



        public function getMandantePorRut(Request $request){
            $post = $request->json()->all();
            $mandantes = \App\mandantes::where('rut_mandante', $post['rut_mandante'])->get();
            for ($i=0; $i < count($mandantes); $i++) { 
            $mandantes[$i]['clave'] = hash('md5',  $mandantes[$i]['claveTextual']);
            } 
            return response()->json($mandantes);
        }

            public function logueo(Request $request){
                    $post = $request->json()->all();
                    $logueos = \App\mandantes::where('proveedor_servicios', $post['proveedor_servicios'])
                    ->where('clave', $post['clave']);
                    
                      if($logueos->count() == 0){
        
                            abort(403, 'Unauthorized action.');
                            
                            }else{

                                if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {
                                echo json_encode(array("rut_empresa"=>$logueos->get()[0]["rut_empresa"],"id"=>$logueos->get()[0]["id"], "nombre_empresa"=>$logueos->get()[0]["nombre_empresa"],"nombre_rep"=>$logueos->get()[0]["nombre_rep"]));
                            } else {
                                echo json_encode(array("error"=>'Contraseña Errónea'));
                            }
                            
                            }
           }

}