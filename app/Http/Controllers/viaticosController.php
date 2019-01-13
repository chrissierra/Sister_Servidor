<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class viaticosController extends Controller
{
    //

	public function GetViaticosPorTrabajador(Request $request){
		$post = $request->json()->all();

		$viaticos = \App\viaticos::where('trabajador_id', $post['trabajador_id'])
					->where('anio', $post['anio'])
					->where('mes', $post['mes'])->get();

		echo json_encode($viaticos);

	}


		public function GetViaticosPorEmpleador(Request $request){
		$post = $request->json()->all();

		$viaticos = \App\viaticos::where('cliente_rrhh', $post['cliente_rrhh'])
					->where('mes', $post['mes'])
					->where('anio', $post['anio'])->get();

		echo json_encode($viaticos);

	}





	public function InsertViaticos(Request $request){
			$post = $request->json()->all();

	    	$viaticos = new \App\viaticos;

	    	foreach ($post as $key => $value) {
	    		$viaticos->$key = $value;
	    	}


	    	$viaticos->save();
	    	echo json_encode(array("estatus"=> "ok"));
	}
}
