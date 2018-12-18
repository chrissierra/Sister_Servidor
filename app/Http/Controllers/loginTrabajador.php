<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginTrabajador extends Controller
{
    //
    public function loginTrabajadorDashboard(Request $request){
    	 $post = $request->json()->all();

    	 $logueos = \App\ingreso_empleados::where('rut', $post["rut"]);

        
       
        if($logueos->count() === 0){
        
        
         abort(403, 'Unauthorized action.');
        
        }else{

           // if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {

         if ($post["claveTrabajador"]  === $logueos->get()[0]['claveTrabajador'] ) {
            echo json_encode($logueos->get()[0]);
        } else {
            $this->LogueoDefault($logueos->get()[0]['nombre_empresa_usuario_plataforma'], $post["claveTrabajador"]);
            //echo json_encode(array("error"=>'Contraseña Errónea'));
        }
        
        }
    } // Fin loginTrabajadorDashboard


     public function LogueoDefault($nombre_empresa_usuario_plataforma, $clave){
         


         $logueos = \App\contraseñas::where('nombre_empresa', $nombre_empresa_usuario_plataforma)
                                    ->where('rol', 'trabajadores');

        
       
        if($logueos->count() === 0){
        
        abort(403, 'Unauthorized action.');
        
        }else{

           // if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {

         if ($clave  === $logueos->get()[0]['clave'] ) {
            echo json_encode($logueos->get()[0]);
        } else {
            echo json_encode(array("error"=>'Contraseña Errónea'));
        }
        
        }
    } // Fin LogueoDefault




    public function loginSucursal(Request $request){


         $post = $request->json()->all();

         $logueos = \App\contraseñas::where('rut', $post["rut"]);

        
       
        if($logueos->count() == 0){
        
        abort(403, 'Unauthorized action.');
        
        }else{

           // if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {

         if ($post["claveTrabajador"]  === $logueos->get()[0]['clave'] ) {
            echo json_encode($logueos->get()[0]);
        } else {
            echo json_encode(array("error"=>'Contraseña Errónea'));
        }
        
        }


    } // Fin funcion loginSucursal
}
