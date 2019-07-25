<?php

namespace App\Http\Controllers;

use App\contraste_fotografico_validacion;
use Illuminate\Http\Request;

class ContrasteFotograficoValidacion extends Controller
{



    /*    public function UpdateContrasteFotograficoValidacion(Request $request)
    {

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]  

        var_dump($post); 

        echo "Que pasa...";     

       // $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id'])->get();

       // $get_validaciones->updata(['validado'=> $post['validado'] ]);

       // return response()->json(
        //            ['response' => 'Ok' ]
       // );

    }*/


    public function getContrasteFotograficoValidacion(Request $request, contraste_fotografico_validacion $contraste_fotografico_validacion)
    {

        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]        

        $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id'])->get();

        return response()->json($get_validaciones);

    }

    public function UpdateContrasteFotograficoValidacion(Request $request, contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]       

        $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id']);
        
        $get_validaciones->update(['validado'=> $post['validado'] ]);

        /*

                $contraste_fotografico_validacion::updateOrCreate(['trabajador_id' => $post['trabajador_id']],
                            ['trabajador_id' => $post['id'], 'empresa_id' =>  $empresa[0]['id'], 'validado'=> 'false']
                );

                \App\contraste_fotografico_validacion::updateOrCreate(['trabajador_id' => $post['id']],
                            ['trabajador_id' => $post['id'], 'empresa_id' =>  $empresa[0]['id'], 'validado'=> 'false']
                );

        */

        return response()->json(
                  ['response' => 'Ok' ]
        );
    

    }


        public function InsertContrasteFotograficoValidacion(Request $request, contraste_fotografico_validacion $contraste_fotografico_validacion)
    {
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]       
        /*
        $get_validaciones = $contraste_fotografico_validacion::where('trabajador_id', $post['trabajador_id']);
        
        $get_validaciones->update(['validado'=> $post['validado'] ]);
        */

                $empresa =  \App\clientes_rrhh::where('nombre_empresa', $post['nombre_empresa_usuario_plataforma'])->get();

        

                $contraste_fotografico_validacion::updateOrCreate(['trabajador_id' => $post['trabajador_id']],
                            ['trabajador_id' => $post['id'], 'empresa_id' =>  $empresa[0]['id'], 'validado'=> $post['validado']]
                );
        

        return response()->json(
                  ['response' => 'Ok' ]
        );
    

    }


}
