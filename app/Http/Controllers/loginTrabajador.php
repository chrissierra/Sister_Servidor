<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginTrabajador extends Controller
{
    //
    public function loginTrabajadorDashboard(Request $request){
    	$post = $request->json()->all();

    	 $logueos = \App\ingreso_empleados::where('rut', $post["rut"]);

        
       
        if($logueos->count() == 0){
        
        abort(403, 'Unauthorized action.');
        
        }else{

           // if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {

         if ($post["clave"]  === $logueos->get()[0]['claveTrabajador'] ) {
            echo json_encode($logueos->get()[0]);
        } else {
            echo json_encode(array("error"=>'Contraseña Errónea'));
        }
        
        }
    }
}
