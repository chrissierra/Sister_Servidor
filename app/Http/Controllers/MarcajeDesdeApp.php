<?php

namespace App\Http\Controllers;
use Illuminate\Http\File;

use Illuminate\Http\Request;
// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class MarcajeDesdeApp extends Controller
{

		public $mes, $anio, $dia, $fecha;
	    function __construct(){

        date_default_timezone_set('America/Santiago');
    	$this->mes = date('m')*1;
    	$this->anio = date('Y');
    	$this->dia = date('d');
    	$this->timeUnix = time();
        //$this->dia_e = (date('d') *1) . 'e';
        //$this->dia_s = (date('d') *1) . 's';
        $this->fecha = date('d/m/Y');
    } // Fin función __construct
    //

      public function mkdirs($dir, $mode = 0777, $recursive = true) {
		  if( is_null($dir) || $dir === "" ){
		    return FALSE;
		  }
		  if( is_dir($dir) || $dir === "/" ){
		    return TRUE;
		  }
		  if( mkdirs(dirname($dir), $mode, $recursive) ){
		    return mkdir($dir, $mode);
		  }
		  return FALSE;
		}

    public function guardarImagenesProcesoBiometricoEnMarcaje(Request $request){
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
        // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $request->image);
        $image = str_replace(' ', '+', $image);
        $imageName = '179614936.png';
        $valorRetonar = 'https://sister.cl/ServidorLaravel/storage/app/'. $this->anio . '/' . $this->mes .'/' .$this->dia . '/' . $this->timeUnix . '/' .$imageName;
        $path1 =  storage_path('app/' . $this->anio . '/' . $this->mes .'/' .$this->dia . '/' . $this->timeUnix . '/');
		
		//Storage::disk('local')->makeDirectory('hola/');

        //Storage::makeDirectory($path);
        
        //Image::make(base64_decode($image))->save(storage_path($path) . $imageName);
       $image = Image::make(base64_decode($image));
        //\File::put(storage_path($path) . $imageName, base64_decode($image));
    	//$arrayResponse = array('Labase64' => 'hola');
    	//return json_encode($path);

		//Lets create path for post_id if it doesn't exist yet e.g `public\blogpost\23`
		//if(!\File::exists($path)) \File::makeDirectory($path, 775);
        if (!file_exists($path1 )) {
			    mkdir($path1 , 0777, true);
			}
		//Lets save the image
		$image->save($path1 . '/' . $imageName);

		return json_encode($valorRetonar);
    }



  
}
