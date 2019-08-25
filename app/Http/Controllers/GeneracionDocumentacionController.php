<?php

namespace App\Http\Controllers;

use App\GeneracionDocumentacion;
use Illuminate\Http\Request;

class GeneracionDocumentacionController extends Controller
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


    public function ingresarDocumento(Request $request){
        
        $GeneracionDocumentacion = new \App\GeneracionDocumentacion;
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        $generacionDocumentacion->empresa_id  = $post['empresa_id'];
        $generacionDocumentacion->empresa  = $post['empresa'];
        $generacionDocumentacion->CuerpoDocumento  = $post['CuerpoDocumento'];
        $generacionDocumentacion->variablesNoParametrizadas  = $post['variablesNoParametrizadas'];
        $generacionDocumentacion->variablesParametrizadas  = $post['variablesParametrizadas'];
        $generacionDocumentacion->save();
        return json_encode(array('estado'=> 'ok'));


    }


    public function GetDocumento(Request $request){

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]        
        $GeneracionDocumentacion =  \App\GeneracionDocumentacion::where('empresa_id', $post['empresa_id'])->get();;
        return json_encode(array('response'=> $GeneracionDocumentacion));


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
     * @param  \App\GeneracionDocumentacion  $generacionDocumentacion
     * @return \Illuminate\Http\Response
     */
    public function show(GeneracionDocumentacion $generacionDocumentacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GeneracionDocumentacion  $generacionDocumentacion
     * @return \Illuminate\Http\Response
     */
    public function edit(GeneracionDocumentacion $generacionDocumentacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GeneracionDocumentacion  $generacionDocumentacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneracionDocumentacion $generacionDocumentacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GeneracionDocumentacion  $generacionDocumentacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneracionDocumentacion $generacionDocumentacion)
    {
        //
    }
}
