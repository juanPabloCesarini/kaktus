<?php
    include("kaktus.php");


   foreach($productos as $prod){
        $articuloId= $prod->articuloId;
        $codigo =$prod->codigo;
        $nombre =$prod->denominacion;
        $url		= trim(UrlMorph($nombre . "-" . $codigo));
    $preciosProductos=[];
    foreach($precios as $precio){
        if ($articuloId == $precio->articuloId){
            
            $preciosProductos[$precio->listaPrecioId]=round($precio->precioVentaIva,2);

         
        }
        
  
   }
   $precio = $preciosProductos[7];
   $precio1 = $preciosProductos[1];
   $precio2 = $preciosProductos[2];
   $precio3 = $preciosProductos[3];
   $precio4 = $preciosProductos[4];
   $precio5 = $preciosProductos[5];
   $precio6 = $preciosProductos[6];
   $precio7 = $preciosProductos[7];
   $cSql = "SELECT * FROM productos WHERE codigo = '$codigo' LIMIT 1";
   $sRes = mysqli_query($_SESSION['link'], $cSql);
   $row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

   if (mysqli_num_rows($sRes)) {
        $cSql = "UPDATE productos SET ".
                    "nombre = '$nombre', ".
                    "precio = '$precio', ".
                    "precio1 = '$precio1', ". 
                    "precio2 = '$precio2', ".
                    "precio3 = '$precio3', ".
                    "precio4 = '$precio4', ".
                    "precio5 = '$precio5', ".
                    "precio6 = '$precio6', ".
                    "precio7 = '$precio7', ".
                    "url = '$url', ".
                    "WHERE codigo = '$codigo' LIMIT 1";

                    
    }else{
        $cSql = "INSERT INTO productos SET " .
					
					"codigo = '$codigo', " .
					"nombre = '$nombre', " .
					"url 	= '$url', " .
					"precio = '$precio', " .
					"precio1 = '$precio1', " .
					"precio2 = '$precio2', " .
					"precio3 = '$precio3', " .
					"precio4 = '$precio4', " .
					"precio5 = '$precio5', " .
					"precio6 = '$precio6', " .
					"precio7 = '$precio7', " .
					"publicado = 1;";
    }
   /* echo "id art: " .$prod->articuloId."<br>";
   echo "codigo: " .$prod->codigo."<br>";
   echo "nombre: " .$prod->denominacion."<br>";
   echo "LISTA DE PRECIOS:<br>";
   echo "Lista1: " .$preciosProductos[1]."<br>";
   echo "Lista2: " .$preciosProductos[2]."<br>";
   echo "Lista3: " .$preciosProductos[3]."<br>";
   echo "Lista4: " .$preciosProductos[4]."<br>";
   echo "Lista5: " .$preciosProductos[5]."<br>";
   echo "Lista6: " .$preciosProductos[6]."<br>";
   echo "Lista7: " .$preciosProductos[7]."<br>"; */
}



?>

