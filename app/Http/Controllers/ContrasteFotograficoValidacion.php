<?php

namespace App\Http\Controllers;

use App\contraste_fotografico_validacion;
use Illuminate\Http\Request;

class ContrasteFotograficoValidacion extends Controller
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


        public function UpdateContrasteFotograficoValidacion(Request $request)
    {

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]  

        var_dump($post);      

       // $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id'])->get();

       // $get_validaciones->updata(['validado'=> $post['validado'] ]);

       // return response()->json(
        //            ['response' => 'Ok' ]
       // );

    }


    public function getContrasteFotograficoValidacion(Request $request, contraste_fotografico_validacion $contraste_fotografico_validacion)
    {

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]        

        $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id'])->get();

        return response()->json($get_validaciones);

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\contraste_fotografico_validacion  $contraste_fotografico_validacion
     * @return \Illuminate\Http\Response
     */
    public function show(contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\contraste_fotografico_validacion  $contraste_fotografico_validacion
     * @return \Illuminate\Http\Response
     */
    public function edit(contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\contraste_fotografico_validacion  $contraste_fotografico_validacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\contraste_fotografico_validacion  $contraste_fotografico_validacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        //
    }
}
