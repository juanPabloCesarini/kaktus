<?php
    include("kaktus.php");

//echo $productos;

   foreach($productos as $prod){
        echo $prod->articuloId."<br>";
            echo $prod->codigo."<br>";
            echo $prod->denominacion."<br>";
    foreach($precios as $precio){
        if ($prod->articuloId == $precio->articuloId){
            
            
            echo $precio->listaPrecioId."<br>";
            echo $precio->precioVentaIva."<br>";
            echo "-----------------<br>";
        }
    
  
   }
}



?>

