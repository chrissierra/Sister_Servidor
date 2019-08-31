<?php

namespace App\Http\Controllers;

use App\DocumentosGenerados;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
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

    public function armarDocumento(Request $request){
            ob_end_clean();
            $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
           /* \Fpdf::AddPage();
            \Fpdf::SetFont('arial', '', 12);
            \Fpdf::WriteHTML(utf8_decode($post['cuerpoDocumento']));
            \Fpdf::Output('F', "peo.pdf", true); */
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($post['cuerpoDocumento']);
            //$pdf->save("documento_".$post['rutEmpresa'].".pdf", true);
            $pdf->Output("documento_".$post['rutEmpresa'].".pdf",\Mpdf\Output\Destination::FILE);
            return json_encode(array('response'=> 'ok'));
            //return response(Fpdf::Output("I"), 200)->header('Content-Type', 'text/pdf');
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
        $generacionDocumentacion->tipocarta  = $post['tipocarta'];
        $generacionDocumentacion->nombre  = $post['nombre'];
        $generacionDocumentacion->apellido  = $post['apellido'];
        $generacionDocumentacion->rut  = $post['rut'];        
        $generacionDocumentacion->save();
        return json_encode(array('estado'=> 'ok'));


    }

    /* */



    public function GetDocumentoPorTrabajador(Request $request){
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]  
        $path = '/usr/share/nginx/html/clientes_rrhh/'.$post['rut_empresa'].'/registro/'.$post['rut_empresa'] .'.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); 
             
        $GeneracionDocumentacion =  \App\DocumentosGenerados::where('trabajador_id', $post['trabajador_id'])->get();;
        return json_encode(array('response'=> $GeneracionDocumentacion, 'image64' => $base64));


    }


        public function GetDocumentosGeneradorPorEmpresa(Request $request){


        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]       
        $path = '/usr/share/nginx/html/clientes_rrhh/'.$post['rut_empresa'].'/registro/'.$post['rut_empresa'] .'.jpg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); 
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
