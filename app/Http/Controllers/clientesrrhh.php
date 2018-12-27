<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class clientesrrhh extends Controller
{
    //

    public function actualizarDatosClientes(Request $request){
    	    
    	    $post = $request->json()->all();

	        $planilla =  \App\clientes_rrhh::where('id', $post['id']);

	        foreach ($post as $key => $value) {
	            $planilla->update([$key => $value]);
	        }


	        echo json_encode($post);
    }


        public function GetDatosClientes(Request $request){
    	    
    	    $post = $request->json()->all();

	        $planilla =  \App\clientes_rrhh::where('id', $post['id']);

	       echo json_encode($planilla->get());
    }
}
