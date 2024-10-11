<?

ini_set('max_execution_time', 7200);

?>

<head>
	<style>
		body {
			background: black;
			color: lightgreen;
			font-size: 14px;
			font-family: "Courier New", Courier;
		}
	</style>
</head>

<? ////////////// FUNCIONES ////////////////////////
function Replace($cStr)
{


	$cStr = str_replace('¥', 'Ñ', $cStr);
	$cStr = str_replace('Ð', 'Ñ', $cStr);
	$cStr = str_replace('"', '', $cStr);
	$cStr = str_replace("'", '´', $cStr);

	return $cStr;
}

/* FUNCION PARA 3 NIVELES DE CATEGORIAS */
function GenerarArbolCategorias($cat1, $cat2, $cat3)
{

	ini_set('max_execution_time', 7200);

	$cSql = "SELECT * FROM categorias_producto WHERE codigo = '{$cat1}' LIMIT 1";

	$sRes = mysqli_query($_SESSION['link'], $cSql);
	$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	// Genera la primer categoría. Padre.
	if (!isset($row['id_categoriaproducto'])) {
		$cSql = "INSERT INTO categorias_producto SET id_padre = 0, nombre = '{$cat1}', codigo = '{$cat1}'";
		$sRes2 = mysqli_query($_SESSION['link'], $cSql);
		$id_categoria = mysqli_insert_id($_SESSION['link']);
	} else {
		$id_categoria = $row['id_categoriaproducto'];
	}

	// Genera la segunda.
	$cSql = "SELECT * FROM categorias_producto WHERE codigo = '{$cat2}' LIMIT 1";

	$sRes = mysqli_query($_SESSION['link'], $cSql);
	$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	// Genera la segunda categoría. Hija. id_padre = Padre.
	if (!isset($row['id_categoriaproducto'])) {
		$cSql = "INSERT INTO categorias_producto SET id_padre = '{$id_categoria}', nombre = '{$cat2}', codigo = '{$cat2}'";
		$sRes2 = mysqli_query($_SESSION['link'], $cSql);
		$id_categoria_hija = mysqli_insert_id($_SESSION['link']);
	} else {
		$id_categoria_hija = $row['id_categoriaproducto'];
	}

	// Genera la tercera.
	$cSql = "SELECT * FROM categorias_producto WHERE codigo = '{$cat3}' LIMIT 1";

	$sRes = mysqli_query($_SESSION['link'], $cSql);
	$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	// Genera la segunda categoría. Nieta. id_padre = Hija.
	if (!isset($row['id_categoriaproducto'])) {
		$cSql = "INSERT INTO categorias_producto SET id_padre = '{$id_categoria_hija}', nombre = '{$cat3}', codigo = '{$cat3}'";
		$sRes2 = mysqli_query($_SESSION['link'], $cSql);
		$id_categoria_nieta = mysqli_insert_id($_SESSION['link']);
	} else {
		$id_categoria_nieta = $row['id_categoriaproducto'];
	}

	$aCat[0] = $id_categoria;
	$aCat[1] = $id_categoria_hija;
	$aCat[2] = $id_categoria_nieta;


	return json_encode($aCat, JSON_HEX_QUOT);
}

/* FUNCION PARA 2 NIVELES DE CATEGORIAS */
function CrearCategoriaySubCategoria($cat, $subcat){

	if($cat == ''){
		return '""';
	}
	// CATEGORIA
	$cSql = "SELECT * FROM categorias_producto WHERE codigo = '{$cat}' LIMIT 1";
	$sRes = mysqli_query($_SESSION['link'], $cSql);
	$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	if (!isset($row['id_categoriaproducto'])) {
		$cSql = "INSERT INTO categorias_producto SET nombre = '{$cat}', codigo = '{$cat}', id_padre = 0";
		$sRes = mysqli_query($_SESSION['link'], $cSql);
		$id_cat = mysqli_insert_id($_SESSION['link']);
	} else {
		$id_cat = $row['id_categoriaproducto'];
	}

	// SUB-CATEGORIA
	if ($subcat == '') {
		//return '["' . $id_cat . '"]'; // Generalmente es así incluyendo corchetes, le saco los corchetes para poder concatenar otras categorias 3, 4.
		return '"' . $id_cat . '"';
	}

	$cSql = "SELECT * FROM categorias_producto WHERE codigo = '{$subcat}' AND id_padre = '{$id_cat}' LIMIT 1";
	$sRes = mysqli_query($_SESSION['link'], $cSql);
	$rowsub = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	if (!isset($rowsub['id_categoriaproducto'])) {
		$cSql = "INSERT INTO categorias_producto SET nombre = '{$subcat}', codigo = '{$subcat}', id_padre = {$id_cat}";
		$sRes = mysqli_query($_SESSION['link'], $cSql);
		$id_subcat = mysqli_insert_id($_SESSION['link']);
	} else {
		$id_subcat = $rowsub['id_categoriaproducto'];
	}

	//$return = '["' . $id_cat . '","' . $id_subcat . '"]'; // Idem que arriba.
	$return = '"' . $id_cat . '","' . $id_subcat . '"';

	return $return;
}

function CrearMarca($nombre)
{

	$cSql = "SELECT * FROM marcas_producto WHERE codigo = '{$nombre}' LIMIT 1";

	$sRes = mysqli_query($_SESSION['link'], $cSql);

	if (!$sRes) {
		echo mysqli_error($_SESSION['link']);
		echo "ERROR: " . $cSql;
		die;
	}

	$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

	if (!isset($row['id_marcaproducto'])) {

		$cSql = "INSERT INTO marcas_producto SET nombre = '{$nombre}', codigo = '{$nombre}', publicado = 1";
		$sRes2 = mysqli_query($_SESSION['link'], $cSql);

		return mysqli_insert_id($_SESSION['link']);
	} else {

		return $row['id_marcaproducto'];
	}
}


function CrearVariante($aData, $id_producto){
	//echo "<pre>"; print_r($aData); die;
	/*****************
	 Array
		(
			[0] => 2597
			[1] => CAMPERA TRUCKER BLANCA
			[2] => M
			[3] => UNIDAD
			[4] => 21
			[5] => PESOS
			[6] => KAKTUS
			[7] => 
			[8] => INDUSTRIAL
			[9] => CAMPERAS
			[10] => 
			[11] => 
			[12] => 2
			[13] => 200
			[14] => 20
			[15] => 2000
			[16] => 20000
			[17] => 200000
			[18] => 2000000
			[19] => 20000000
			[20] => 15
			[21] => 8
		)
	 ****************/

	// busco el id del producto
	$codigo_producto = $aData[0];
	$nombre = $aData[1];

	$cSql = "SELECT * FROM productos WHERE id_producto = '{$id_producto}' LIMIT 1";

	$sRes = mysqli_query($_SESSION['link'], $cSql);
	if (!$sRes) {
		echo $cSql;
		echo mysqli_error($_SESSION['link']) . "<br>";
		echo mysqli_errno($_SESSION['link']) . "<br>";
	} else {
		$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

		// si existe el producto guardo el id, de lo contrario lo inserto
		if (isset($row['id_producto'])) {
			$id_producto = $row['id_producto'];
		} else {
			echo "No se encontro el producto para la variante.";
			die;
		}
	}

	$color = $aData[7];

	$id_color = 0;

	if($color != ''){
	
		// busco si existe el color en la tabla colores
		$cSql = "SELECT * FROM colores WHERE nombre ='{$color}'";
		$sRes = mysqli_query($_SESSION['link'], $cSql);
		if (!$sRes) {
			echo $cSql;
			echo mysqli_error($_SESSION['link']) . "<br>";
			echo mysqli_errno($_SESSION['link']) . "<br>";
		} else {
			$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);
			// si existe el color guardo el id, de lo contrario lo inserto
			if (isset($row['id_color'])) {
				$id_color = $row['id_color'];
			} else {

				$cSql = "INSERT INTO colores SET nombre = '{$color}', color = '#999999'";
				$sRes2 = mysqli_query($_SESSION['link'], $cSql);
				if (!$sRes2) {
					echo $cSql;
					echo mysqli_error($_SESSION['link']) . "<br>";
					echo mysqli_errno($_SESSION['link']) . "<br>";
				}
				$id_color = mysqli_insert_id($_SESSION['link']);
			}
		}
	
	} 
	// Talle
	$talle = $aData[2];

	$iva = 1 + ($aData[4] / 100);
	
	$precio = $aData[14] * $iva;
	$precio = number_format($precio, 2, '.', '');

	$precio1 = $aData[13] * $iva;
	$precio1 = number_format($precio1, 2, '.', '');

	$precio2 = $aData[14] * $iva;
	$precio2 = number_format($precio2, 2, '.', '');

	$precio3 = $aData[15] * $iva;
	$precio3 = number_format($precio3, 2, '.', '');

	$precio4 = $aData[16] * $iva;
	$precio4 = number_format($precio4, 2, '.', '');

	$precio5 = $aData[17] * $iva;
	$precio5 = number_format($precio5, 2, '.', '');

	$precio6 = $aData[18] * $iva;
	$precio6 = number_format($precio6, 2, '.', '');

	$precio7 = $aData[19] * $iva;
	$precio7 = number_format($precio7, 2, '.', '');

	$stock = $aData[12];

	//averiguo si existe la variante con estos datos
	// si existe retorno el id de la variante.
	// de lo contrario primero inserto los datos y luego retorno el id
	$cSql = "SELECT * FROM productos_variantes WHERE id_producto = '{$id_producto}'
			AND codigo ='{$codigo_producto}' 
			AND id_color = '{$id_color}'
			AND talle = '{$talle}'";

	$sRes = mysqli_query($_SESSION['link'], $cSql);
	if (!$sRes) {
		echo $cSql;
		echo mysqli_error($_SESSION['link']) . "<br>";
		echo mysqli_errno($_SESSION['link']) . "<br>";
	} else {
		$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);
		// si existe el color guardo el id, de lo contrario lo inserto
		if (isset($row['id_productovariante'])) {

			$id_variante = $row['id_productovariante'];

			$cSql = "UPDATE productos_variantes SET 
					id_producto = '{$id_producto}',
					nombre = '{$nombre}',
					codigo = '{$codigo_producto}',
					id_color ='{$id_color}',
					talle = '{$talle}',
					precio = '{$precio}',
					precio1 = '{$precio1}',
					precio2 = '{$precio2}',
					precio3 = '{$precio3}',
					precio4 = '{$precio4}',
					precio5 = '{$precio5}',
					precio6 = '{$precio6}',
					precio7 = '{$precio7}',
					stock = '{$stock}' WHERE id_productovariante = '{$id_variante}' LIMIT 1";
			
			$sRes2 = mysqli_query($_SESSION['link'], $cSql);
			if (!$sRes2) {
				echo $cSql;
				echo mysqli_error($_SESSION['link']) . "<br>";
				echo mysqli_errno($_SESSION['link']) . "<br>";
			}

			
		} else {
			
			$cSql = "INSERT INTO productos_variantes SET 
				id_producto = '{$id_producto}',
				nombre = '{$nombre}',
				codigo = '{$codigo_producto}',
				id_color = '{$id_color}',
				talle = '{$talle}',
				precio = '{$precio}',
				precio1 = '{$precio1}',
				precio2 = '{$precio2}',
				precio3 = '{$precio3}',
				precio4 = '{$precio4}',
				precio5 = '{$precio5}',
				precio6 = '{$precio6}',
				precio7 = '{$precio7}',
				stock = '{$stock}'";

			$sRes2 = mysqli_query($_SESSION['link'], $cSql);
			if (!$sRes2) {
				echo $cSql;
				echo mysqli_error($_SESSION['link']) . "<br>";
				echo mysqli_errno($_SESSION['link']) . "<br>";
			}
			$id_variante = mysqli_insert_id($_SESSION['link']);
			return $id_variante;
		
		} // fin else
	} // fin else

}  // Fin de la función CrearVariante


function ImportarProductos($cFilename){

	ini_set('max_execution_time', 7200);

	if (!file_exists($cFilename)) {
		die('No se encontro el archivo de productos.');
	}

	// Borrar el log.
	unlink('log-sql.txt');

	$cSeparador = ";";
	$n = 0;
	$k = 0;

	/**** NUEVO MÉTODO XLSX ******************************/
	require_once '../../../vendor/simplexlsx/src/SimpleXLSX.php';
	if ($xlsx = SimpleXLSX::parse($cFilename)) {
		$aFile = $xlsx->rows();
	} else {
		echo SimpleXLSX::parseError();
		die(' - Error al cargar el archivo, contacte con soporte.');
	}
	/**** FIN NUEVO MÉTODO XLSX ******************************/

	if (is_array(@$aFile) && count(@$aFile)) {

		$n = 0;

		$id = 0;
		$nom = '';

		$cUltCategoria = "";
		///borrar variantes limit 5000
		
		$cSql = "DELETE FROM productos_variantes LIMIT 5000";
		if (!mysqli_query($_SESSION['link'], $cSql)) {

			echo $cSql;
			echo "<br />" . mysqli_error($_SESSION['link']);
			die;
		}
		
		$aProductoUnico = array();

		$encripta = implode("(usetheforce)", $aFile[1]); // Depende de cada archivo, acá saltea la primera fila, por eso el índice es 1.
		//codigo(usetheforce)pepe(usetheforce)nombre(usetheforce)...

		$encripta = md5($encripta);

		//echo $encripta; die; //Descomentar esta linea para capturar un nuevo encabezado encriptado.
		$encabezado_encriptado = '63f2e2cd36036a5db5a727432eaa351e'; // Encabezado encriptado del archivo.

		if($encripta != $encabezado_encriptado){
			echo "<span style='color:cyan;'>NO SE PROCESÓ EL ARCHIVO:</span> - El encabezado del archivo no es el esperado, el encabezado y cantidad de columnas debe ser el mismo que se encuentra en el siguiente modelo: <a href='./modelo.xlsx' style='color:white;'>DESCARGAR MODELO</a>.<br>";
			echo "<br>Estructura encontrada en este archivo:<br>";
			echo "<pre>";
			print_r($aFile[1]); // Debemos poner el mismo índice.
			echo "</pre>";
			die;
		}

		if(isset($_GET['ws'])){			
			echo "<pre>";
			print_r($aFile[1]); 
			die;
		}

		foreach ($aFile as $aData) {

			if ($aData[0] == '') {
				continue;
			}

			if ($aData[0] == 'Código') {
				continue;
			}

			if(isset($_GET['ws'])){			
				echo "<pre>";
				print_r($aData);
				die;
			}
			
			/**** Estructura KAKTUS
			 *        (
            [0] => Código
			[1] => ARTICULO PRINCIPAL (ES UN SOLO ARTÍCULO Y TIENE SUB ARTÍCULOS DEPENDIENDO SIEMPRE DE TALLES Y/O COLORES)
			[2] => TALLE
			[3] => U. M.
			[4] => IVA
			[5] => MONEDA
			[6] => Marca
			[7] => Envase (COLOR)
			[8] => CAT 1
			[9] => CAT 2
			[10] => CAT 2
			[11] => CAT 3
			[12] => STOCK
			[13] => LISTA A
			[14] => LISTA B (APARECE EN WEB)
			[15] => LISTA C
			[16] => LISTA D
			[17] => LISTA E
			[18] => LISTA F
			[19] => LISTA G
			[20] => PRECIO OFERTA
			[21] => ORDEN
        	)
			************/			

			$codigo		= trim($aData[0]);
			$nombre		= trim(addslashes($aData[1]));

			$n++;

			if(isset($aProductoUnico[$nombre])){
				CrearVariante($aData, $aProductoUnico[$nombre]);
				continue;
			}

			$aProductoUnico[$nombre] = 0; // Aca debe guardar el ID_PRODUCTO luego de añadirlo.

			$url		= trim(UrlMorph($nombre . "-" . $codigo));
			
			$marca		= $aData[6];
			$id_marca 	= CrearMarca(Replace($marca));

			// CATEGORIAS - El cliente solicitó comentar las categorias.
			//$categorias1 = CrearCategoriaySubCategoria($aData[8], $aData[9]);
			//$categorias2 = CrearCategoriaySubCategoria($aData[10], $aData[11]);
			
			//$categorias = '['.$categorias1.','.$categorias2.']';

			// PRECIOS
			$iva 		= $aData[4];

			$precio 	= $aData[19] * (1+($iva/100));
			$precio 	= number_format($precio, 2, '.', '');

			$precio1 	= $aData[13] * (1+($iva/100));
			$precio1 	= number_format($precio1, 2, '.', '');		

			$precio2 	= $aData[14] * (1+($iva/100));
			$precio2 	= number_format($precio2, 2, '.', '');

			$precio3 	= $aData[15] * (1+($iva/100));
			$precio3 	= number_format($precio3, 2, '.', '');

			$precio4 	= $aData[16] * (1+($iva/100));
			$precio4 	= number_format($precio4, 2, '.', '');

			$precio5 	= $aData[17] * (1+($iva/100));
			$precio5 	= number_format($precio5, 2, '.', '');

			$precio6 	= $aData[18] * (1+($iva/100));
			$precio6 	= number_format($precio6, 2, '.', '');

			$precio7 	= $aData[19] * (1+($iva/100));
			$precio7 	= number_format($precio7, 2, '.', '');
			
			$stock		= $aData[12];
			$talle		= $aData[2];
			$color		= $aData[7];

			$actualiza = time();
			
			$cSql = "SELECT * FROM productos WHERE codigo = '$codigo' LIMIT 1";
			$sRes = mysqli_query($_SESSION['link'], $cSql);
			
			$row = mysqli_fetch_array($sRes, MYSQLI_ASSOC);

			//echo "<pre>"; print_r($row); die;

			if (mysqli_num_rows($sRes)) {

				$id_producto = $row['id_producto'];

				$aProductoUnico[$nombre] = $id_producto;

				//"categorias	= '$categorias', " . // El cliente pide comentar las categorias.

				$cSql = "UPDATE productos SET ".
					"precio = '$precio', ".
					"precio1 = '$precio1', ". 
					"precio2 = '$precio2', ".
					"precio3 = '$precio3', ".
					"precio4 = '$precio4', ".
					"precio5 = '$precio5', ".
					"precio6 = '$precio6', ".
					"precio7 = '$precio7', ".
					"actualiza = '$actualiza', ".
					"stock = '$stock' WHERE id_producto = '$id_producto' LIMIT 1";

				if (mysqli_query($_SESSION['link'], $cSql)) {
					$aProductoUnico[$nombre] = $id_producto;
					
				} else {
					echo $cSql;
					echo "<br />" . mysqli_error($_SESSION['link']);
					die;
				}

				$texto = $cSql . "\n";
				file_put_contents('log-sql.txt', $texto, FILE_APPEND | LOCK_EX);

			} else {

				// Al insertar, empezamos a usar el código como el id_producto, deberá ser siempre numérico.
				$id_producto = $codigo;

				// "categorias	= '$categorias', " . // El cliente solicitó comentar las categorias.
				
				$cSql = "INSERT INTO productos SET " .
					"id_producto = '$id_producto',".
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
					"actualiza = '$actualiza', ".
					"id_marca = '$id_marca', " .
					"stock = '$stock'," .
					"publicado = 1;";

				$texto = $cSql . "\n";
				file_put_contents('log-sql.txt', $texto, FILE_APPEND | LOCK_EX);

				if (mysqli_query($_SESSION['link'], $cSql)) {
					$aProductoUnico[$nombre] = mysqli_insert_id($_SESSION['link']);
					
				} else {
					echo $cSql;
					echo "<br />" . mysqli_error($_SESSION['link']);
					die;
				}

			}

			// Creo la primer variante. Con los datos del primer registro.
			CrearVariante($aData, $aProductoUnico[$nombre]);


			// Fin if importador.
		} // endforeach.
	}


	if ($n) {
		return $n;
	} else {
		return false;
	}
}
// Fin de funcion ImportarProductos()

//////////////////////////// FIN FUNCIONES /////////////////////////////////////////////
?>
<?
define('ADMIN', false);
$ini = time();

// ********** DETECCIÓN DE LA INSTANCIA **************************************
// Obtiene el directorio de la instancia.
$estepath = $_SERVER['SCRIPT_FILENAME'];
$posIni = strpos($estepath, "webfiles/");
$posFin = strpos($estepath, "/actualizar");
$_SESSION['instancia']  = substr($estepath, ($posIni + 9), ($posFin - $posIni - 9));
// ********** FIN DE LA DETECCIÓN DE INSTANCIA *******************************

// Link a conexión.
$_SESSION['link'] = null;

include('../../../config.php');
//include('/home/transpar/public_html/funciones/funciones.php');
include('../../../funciones/funciones.php');

// Conecta directamente a MySQL.
//$sMY = mysql_connect(APP_DATABASE_HOST, APP_DATABASE_USER, APP_DATABASE_PASS);
//mysql_select_db(APP_DATABASE_NAME);

$_SESSION['link'] = mysqli_connect(APP_DATABASE_HOST, APP_DATABASE_USER, APP_DATABASE_PASS, APP_DATABASE_NAME);

// Archivos de datos.
$cFilename = $_SESSION['instancia'].".xlsx";

$productos = ImportarProductos($cFilename);

// LOG
$fichero = 'log.txt';
$texto = date("d-m-Y H:i:s")." -> <span style='color:cyan'>¡PROCESADO!</span> -> Se actualizaron <span style='color:cyan'>".$productos."</span> artículos.\n";
file_put_contents($fichero, $texto, FILE_APPEND | LOCK_EX);

echo $texto;
die();




?>