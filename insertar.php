<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
	<title>Agregar producto inventario</title>
	<link rel="stylesheet" href="./estilo.css">
</head>
<body>

<header><h1 style={position:relative}>Agregar</h1></header>

<nav> 
    <ul>
    	<li><a href="./almacen.php"> Almacen </a></li>
        <li><a href="./inventario.php"> Inventario </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./eliminar.php"> Eliminar </a></li>
        <li><a href="./editar.php"> Editar </a></li>
    </ul>
</nav>

<section>

<h2>Agregar al inventario</h2>

<form enctype="multipart/form-data" method="POST" action="" onsubmit="return validaciones();">
	<label for="codigoBarras"> Escanea el codigo de barras: </label>
	<input type="text" name="codigoBarras" id="codigoBarras" required> * <br><br>
	<label for="fname">Tipo de producto:</label>
	<input type="text" name="fname"> <br><br>
	<label for="cantI>"> Cantidad:</label>
	<input type="text" name="cantI" id="cantI" required> * <br><br>
	<label for="marca>"> Marca:</label>
	<input type="text" name="marca" id="marca"> <br><br>
	<label for="proveedor"> Proveedor:</label>
	<input type="text" name="proveedor" id="proveedor" required> * <br><br>
	<label for="fotoP"> Foto:</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="400000" />
	<input type="file" name="fotoP" id="fotoP"><br><br>
	<label for="descrip"> Descripción del producto:</label>
	<textarea name="descrip" id="descrip" placeholder="Breve descripcion del producto"></textarea><br><br>
 	<input type="submit" name="submit" value="Agregar">
</form>

</section>

<p class="obligatorio">Los campos marcados son * campos obligatorios</p>

<script>

	function validaciones(){

		var valor1 = document.getElementById("cantI").value;

		if (valor1 != ""){

			var valoresAceptados = /^[0-9]+$/;
			if (valor1.match(valoresAceptados)){
				// Valores son todos numeros
			} else {
         		alert ("La cantidad debe ser numérica");
         		return false;
    		}

		}

		// Verificación del codigo de barras
		var valor2 = document.getElementById("codigoBarras").value;

		// Que los valores del codigo de barras sean numericos
		var reGuion = /-/g;
		var rePunto = /\./g;

		if (!valor2.match(valoresAceptados)){
			valor3 = valor2.replace(reGuion, '');
			valor3 = valor3.replace(rePunto, '');

			// Longitud entre 8 y 13 dígitos (cuando se introdujo con otros caracteres)
			if(valor3.length > 13 || valor3.length < 8){
				alert ("Longitud codigo de barras incorrecto");
				return false;
			}
			// Que los valores sean solo numeros
			if (!valor3.match(valoresAceptados)){
				alert ("Codigo de barras incorrecto");
				return false;
			}
		} else{
			// Longitud entre 8 y 13 dígitos (cuando es numerico desde el inicio)
			if(valor2.length > 13 || valor2.length < 8){
				alert ("Longitud codigo de barras incorrecto");
				return false;
			}
		}
		return true;
	}

</script>

<?php

	class MiBD extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('test.db');
	    }
	}

	if(isset($_POST["submit"])){

		// Variables de la peticion POST
		$tipoProd = $_POST["fname"];
		$codigoBarras = $_POST["codigoBarras"];
		// Codigo de barras sin puntos y guiones
		$codigoBarras = str_replace('-', '', $codigoBarras);
		$codigoBarras = str_replace('.', '', $codigoBarras);

		$cantidadI = $_POST["cantI"];
		$marca = $_POST["marca"];
		$proveedor = $_POST["proveedor"];
		$descripcionP = $_POST["descrip"];

		$img_file = $_FILES['fotoP']['name'];
        $img_type = $_FILES['fotoP']['type'];
		$tmp_name = $_FILES['fotoP']['tmp_name'];
		$directorio_destino = "./Imagenes";

		// Variables de control
		$contador = 0;
		$nombreFoto = $codigoBarras . $img_file;
		$fallo = 0;

		// Conexion a la BBDD
		$db3 = new MiBD();
	    $codigosF = $db3->query("SELECT CodigoDeBarras FROM Recordar") or die("Problemas en el select:".mysqli_error($db3));
		while($codigosI = $codigosF->fetchArray()){
			if ($codigosI[0] == $codigoBarras){
				$contador++;
			}
	    }

	    /*
	    	Funcion que guarda una foto en la carpeta Imagenes.
	    */
	    function guardarFoto(){
	    	global $fallo;

			if( $_FILES['fotoP']['size'] > 800000 ) {
	  			echo "<script type='text/javascript'>alert('No se pueden subir archivos con pesos mayores a 800kB');</script>";
	  			$fallo = 1;
			} else {
				global $img_type;
				global $tmp_name;
				global $directorio_destino;
				global $nombreFoto;

		    	if (((strpos($img_type, "gif") || strpos($img_type, "jpeg") ||
		 			strpos($img_type, "jpg")) || strpos($img_type, "png")))
		    	{
					if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $nombreFoto))
					{
						// Hemos insertado la foto
					}
					else{
						$fallo = 1;
					}
					// No hemos insertado la foto
		        }
	    	}

	    }

	    /*
	    	Funcion que elimina una foto de la carpeta Imagenes.
	    */
	    function eliminarFoto(){
	    	global $codigoBarras;
	    	global $directorio_destino;
	    	$cb = $_POST["codigoBarras"];

	    	$db4 = new MiBD();
	    	$nombreA = $db4->query("SELECT FotoP FROM Recordar WHERE CodigoDeBarras=$cb") or die("Problemas en el select:".mysqli_error($db4));

	        		
			while($nombreI = $nombreA->fetchArray()){
				unlink($directorio_destino . '/' . $nombreI[0]);
	        }
	        $db4->close();
	    }

	    /*
	    	En este caso, no hemos insertado anteriormente este codigo de barras
			- Primero guardamos la foto, si no da error entonces eliminamos la anterior. Actualizamos el nombre en la BBDD.
			- Si da error al guardar la foto, entonces nos quedamos con la foto antigua.
	    */
	    if($contador == 0){

	    	guardarFoto();

			$db2 = new MiBD();

			if ($nombreFoto != $codigoBarras){
				$db2->exec("INSERT INTO Recordar (CodigoDeBarras, Descripcion, FotoP, Marca, TipoDeProducto) VALUES ('$codigoBarras','$descripcionP','$nombreFoto', '$marca', '$tipoProd');") or die("Problemas en el insert:".mysqli_error($db2));
			}
			else{
				$db2->exec("INSERT INTO Recordar (CodigoDeBarras, Descripcion, FotoP, Marca, TipoDeProducto) VALUES ('$codigoBarras','$descripcionP','Sinfoto.png', '$marca', '$tipoProd');") or die("Problemas en el insert:".mysqli_error($db2));
			}
			$db2->close();

	    } else{
	    /*
	    	En este caso, hemos insertado anteriormente este codigo de barras
	    	- Si le habiamos insertado foto, encontes guardamos foto nueva. Si no hay error eliminamos la antigua y actulizamos BBDD.
	    	  Pero si se ha producido fallo, mantenemos la antigua.
			- Si no le hemos insertado foto, entonces nombreFoto será el codigo de barras y debemos dejar la foto Sinfoto.png en la BBDD.
	    */
	    	if($nombreFoto != $codigoBarras){
				guardarFoto();

				// Actualizo el campo en la BBDD
				echo "<script type='text/javascript'>alert($fallo);</script>";
				if($fallo == 0){
					eliminarFoto();
		    		$db2 = new MiBD();
					$db2->query("UPDATE Recordar SET FotoP='$nombreFoto' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db2));
					$db2->close();
				}
			}
			/* Si no inserta foto, el campo FotoP se rellena con el codigo de barras, cuand
			queramos mostrar la foto comparamos y si es igual no mostramos nada. */

			if($marca != NULL || $marca != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET Marca='$marca' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db2));
				$db2->close();
			}

			if($descripcionP != NULL || $descripcionP != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET Descripcion='$descripcionP' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db2));
				$db2->close();
			}

			if($tipoProd != NULL || $descrip != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET TipoDeProducto='$tipoProd' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db2));
				$db2->close();
			}

	    }

		$db3->close();

	    /* No hemos contemplado que algo que tiene que ver con la imagen haya fallado (a excepción del numero de bytes del documento) o bien no se ha insertado ninguna */
	    	
	    $db5 = new MiBD();
		
	    if ($contador == 0){
	    	$db5->exec("UPDATE Almacen SET CantidadActual=$cantidadI WHERE CodigoDeBarras=$codigoBarras;") or die("Problemas en el update:".mysqli_error($db5));
	    }
	    else {
			$cantidadPas = $db5->query("SELECT CantidadActual FROM Almacen WHERE CodigoDeBarras=$codigoBarras;") or die("Problemas en el select:".mysqli_error($db5));
			$cantidadPas2 = $cantidadPas -> fetchArray();
			$totalCant = $cantidadPas2[0] + $cantidadI;

			$db5->exec("UPDATE Almacen SET CantidadActual=$totalCant WHERE CodigoDeBarras=$codigoBarras;") or die("Problemas en el update:".mysqli_error($db5));
	    }

		$db5->close();

		$db = new MiBD();

		$db->exec("INSERT INTO Inventario (CodigoDeBarras, CantidadInicial, Proveedor) VALUES ('$codigoBarras' ,'$cantidadI', '$proveedor');") or die("Problemas en el insert:".mysqli_error($db));

		$db->close();
		unset($_POST['submit']);
		$url = 'insertar.php';
		//header('Location: '.$url);

	}

?>

</body>
</html>