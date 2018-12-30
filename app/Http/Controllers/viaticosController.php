<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class viaticosController extends Controller
{
    //

	public function GetViaticos(Request $request){
		
	}


	public function InsertViaticos(Request $request){
			$post = $request->json()->all();

	    	$planilla = new \App\viaticos;

	    	foreach ($post as $key => $value) {
	    		$planilla->$key = $value;
	    	}


	    	$planilla->save();
	    	echo json_encode($post);
	}
}
