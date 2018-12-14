header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// En versiones de PHP anteriores a la 4.1.0, debería utilizarse $HTTP_POST_FILES en lugar
//var_dump($_FILES);
// de $_FILES.
include './resize/lib/ImageResize.php';
include './resize/lib/ImageResizeException.php';
//$image_resize = new \Gumlet\ImageResize();
$uri = "/usr/share/nginx/html/trabajadores/". $_GET["rut"]. "/acontrastar/desconocido.jpg";
$uri1 = "/usr/share/nginx/html/trabajadores/". $_GET["rut"]. "/acontrastar/desconocido1.jpg";
$fichero_subido = $uri;
//$ficheroRegistroMovimiento = "/usr/share/nginx/html/registromovimientos/". $_GET["rut"]. "/". time() . ".jpg";
//mkdir("/usr/share/nginx/html/registromovimientos/". $_GET["rut"] . "/".time(), 0700);
$ficheroRegistroMovimiento = "/usr/share/nginx/html/registromovimientos/". $_GET["rut"]."/".time(). "/". $_GET["rut"] . ".jpg";
$url = "https://sister.cl/registromovimientos/". $_GET["rut"]."/".time(). "/". $_GET["rut"] . ".jpg";

mkdir("/usr/share/nginx/html/registromovimientos/". $_GET["rut"] . "/".time(), 0777, true);

//echo '<pre>';
if (copy($_FILES['photo']['tmp_name'], $fichero_subido)) {
    // echo "El fichero es válido y se subió con éxito.\n";
//      $image_r = new ImageResize($fichero_subido);
//      $image_r->scale(5);
//      $image_r->save($uri);
//$img = resize_image($fichero_subido, 400, 300);
imagejpeg(imagescale($fichero_subido,300,400, NEAREST_NEIGHBOUR ), $fichero_subido);
} else {
   // echo "¡Posible ataque de subida de ficheros!\n";
}


if (move_uploaded_file($_FILES['photo']['tmp_name'], $ficheroRegistroMovimiento)) {
  //  echo "El fichero es válido y se subió con éxito.\n";
} else {
  //  echo "¡Posible ataque de subida de ficheros!\n";
}

//echo 'Más información de depuración:'.  $_GET["rut"]. " -> RUT ";
//print_r($_FILES);

//print "</pre>";


//You need to use either PHP's ImageMagick or GD functions to work with images.

//With GD, for example, it's as simple as...

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}


$array_respuesta = array("urlImagen" => $url);
echo json_encode($array_respuesta);

?>