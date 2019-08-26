<?php

namespace App\Http\Controllers;

use App\DocumentosGenerados;
use Illuminate\Http\Request;

class DocumentosGeneradosController extends Controller
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
        
        $generacionDocumentacion = new \App\DocumentosGenerados;
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        
        $generacionDocumentacion->trabajador_id  = $post['trabajador_id'];
        $generacionDocumentacion->empresa_id  = $post['empresa_id'];        
        $generacionDocumentacion->titulo  = $post['titulo'];
        $generacionDocumentacion->CuerpoDocumento  = $post['CuerpoDocumento'];
        $generacionDocumentacion->variablesNoParametrizadas  = $post['variablesNoParametrizadas'];
        $generacionDocumentacion->variablesParametrizadas  = $post['variablesParametrizadas'];
        $generacionDocumentacion->fecha_emision  = $post['fecha_emision'];
        $generacionDocumentacion->firmas  = $post['firmas'];
        $generacionDocumentacion->ciudad  = $post['ciudad'];
        $generacionDocumentacion->dia  = $post['dia'];
        $generacionDocumentacion->mes  = $post['mes'];
        $generacionDocumentacion->anio  = $post['anio'];
        
        $generacionDocumentacion->save();
        return json_encode(array('estado'=> 'ok'));


    }

    /* */



    public function GetDocumento(Request $request){


        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]       
        $GeneracionDocumentacion =  \App\DocumentosGenerados::where('empresa_id', $post['empresa_id'])->get();;
        return json_encode(array('response'=> $GeneracionDocumentacion, 'image64' => $base64));


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
     * @param  \App\DocumentosGenerados  $documentosGenerados
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentosGenerados $documentosGenerados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentosGenerados  $documentosGenerados
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentosGenerados $documentosGenerados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentosGenerados  $documentosGenerados
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentosGenerados $documentosGenerados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentosGenerados  $documentosGenerados
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentosGenerados $documentosGenerados)
    {
        //
    }
}
