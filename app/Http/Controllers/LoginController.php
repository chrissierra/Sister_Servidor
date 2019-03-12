<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        
        $logueos = \App\clientes_rrhh::where('rut_empresa', $post["rut_empresa"]);

        
       
        if($logueos->count() === 0){
        
        abort(403, 'Unauthorized action.');
        
        }else{

            if (password_verify($post["clave"], $logueos->get()[0]['password'] )) {
            echo json_encode(array("rut_empresa"=>$logueos->get()[0]["rut_empresa"],"id"=>$logueos->get()[0]["id"], "nombre_empresa"=>$logueos->get()[0]["nombre_empresa"],"nombre_rep"=>$logueos->get()[0]["nombre_rep"]));
        } else {
            echo json_encode(array("error"=>'Contraseña Errónea'));
        }
        
        }
       
      //echo json_encode($logueos[0]["Id"]);
        
        //
         //var_dump($request);
       // $post = $request->json()->all();
        //$array = array(
         //"clave"=>$post["clave"]
        //);
        //echo json_encode($array);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
