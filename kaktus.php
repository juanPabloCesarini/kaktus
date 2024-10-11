<?

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://kaktuscolon2024.ddns.net:6000/nodoapi/api/Clientes/GetAll',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$clientes = curl_exec($curl);

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://kaktuscolon2024.ddns.net:6000/nodoapi/api/Articulos/GetAll',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$productos = curl_exec($curl);


curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://kaktuscolon2024.ddns.net:6000/nodoapi/api/ArticulosPrecios/GetAll',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$precios = curl_exec($curl);


curl_close($curl);

 echo "Productos:<br>";
//echo $productos;
$productos = json_decode($productos);
//echo "<pre>"; print_r($productos);
echo "Precios:</br>";
$precios = json_decode($precios);
//echo "<pre>";print_r($precios);
/*
echo "<br /><br />";
echo "Clientes:<br>";
echo $clientes;  */

?>