<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
	<title>Insertar producto inventario</title>
	<link rel="stylesheet" href="./estilo.css">
</head>
<body>

<header><h1 style={position:relative}>Inventario</h1></header>

<nav> 
    <ul>
    	<li><a href="./almacen.php"> Almacen </a></li>
        <li><a href="./inventario.php"> Inventario </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./eliminar.php"> Eliminar </a></li>
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
	<input type="hidden" name="MAX_FILE_SIZE" value="800000" />
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
				//alert ("Es numérico");
				//return true;
			} else {
         		alert ("La cantidad debe ser numérica");
         		return false;
    		}

		}

		// Verificación del codigo de barras
		var valor2 = document.getElementById("codigoBarras").value;

		// Longitud entre 8 y 13 dígitos
		if(valor2.length > 13 || valor2.length < 8){
			alert ("Codigo de barras incorrecto");
			return false;
		}

		// Que los valores del codigo de barras sean numericos
		var reGuion = /-/g;
		var rePunto = /\./g;

		if (!valor2.match(valoresAceptados)){
			valor2 = valor2.replace(reGuion, '');
			valor2 = valor2.replace(rePunto, '');
			if (!valor2.match(valoresAceptados)){
				alert ("Codigo de barras incorrecto");
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

		$tipoProd = $_POST["fname"];
		$codigoBarras = $_POST["codigoBarras"];
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

		$contador = 0;
		$nombreFoto = $codigoBarras . $img_file;

		$db3 = new MiBD();
	    $codigosF = $db3->query("SELECT CodigoDeBarras FROM Recordar");
		while($codigosI = $codigosF->fetchArray()){
			if ($codigosI[0] == $codigoBarras){
				$contador++;
			}
	    }

	    function guardarFoto(){

			if( $_FILES['fotoP']['size'] > 800000 ) {
	  			echo "<script type='text/javascript'>alert('No se pueden subir archivos con pesos mayores a 800kB');</script>";
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
					// No hemos insertado la foto
		        }
	    	}

	    }

	    function eliminarFoto(){
	    	global $codigoBarras;
	    	global $directorio_destino;
	    	$cb = $_POST["codigoBarras"];

	    	$db4 = new MiBD();
	    	$nombreA = $db4->query("SELECT FotoP FROM Recordar WHERE CodigoDeBarras=$cb");

	        		
			while($nombreI = $nombreA->fetchArray()){
				unlink($directorio_destino . '/' . $nombreI[0]);
	        }
	        $db4->close();
	    }

	    // En este caso, no hemos insertado anteriormente este codigo de barras
	    if($contador == 0){

	    	guardarFoto();

			$db2 = new MiBD();
			$db2->exec("INSERT INTO Recordar (CodigoDeBarras, Descripcion, FotoP, Marca, TipoDeProducto) VALUES ('$codigoBarras','$descripcionP','$nombreFoto', '$marca', '$tipoProd');");
			$db2->close();

	    } else{

	    	if($nombreFoto != $codigoBarras){
				eliminarFoto();
				guardarFoto();

				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET FotoP='$nombreFoto' WHERE CodigoDeBarras=$codigoBarras");
				$db2->close();
			}
			/* Si no inserta foto, el campo FotoP se rellena con el codigo de barras, cuand
			queramos mostrar la foto comparamos y si es igual no mostramos nada. */

			if($marca != NULL || $marca != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET Marca='$marca' WHERE CodigoDeBarras=$codigoBarras");
				$db2->close();
			}

			if($descripcionP != NULL || $descripcionP != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET Descripcion='$descripcionP' WHERE CodigoDeBarras=$codigoBarras");
				$db2->close();
			}

			if($tipoProd != NULL || $descrip != ""){
				// Actualizo el campo en la BBDD
		    	$db2 = new MiBD();
				$db2->query("UPDATE Recordar SET TipoDeProducto='$tipoProd' WHERE CodigoDeBarras=$codigoBarras");
				$db2->close();
			}

	    }

		$db3->close();

	    /* No hemos contemplado que algo que tiene que ver con la imagen haya fallado (a excepción del numero de bytes del documento) o bien no se ha insertado ninguna */
	    	
	    $db5 = new MiBD();
		
	    if ($contador == 0){
	    	$db5->exec("UPDATE Recordar SET CantidadActual=$cantidadI WHERE CodigoDeBarras=$codigoBarras;");
	    }
	    else {
			$cantidadPas = $db5->query("SELECT CantidadActual FROM Recordar WHERE CodigoDeBarras=$codigoBarras;");
			$cantidadPas2 = $cantidadPas -> fetchArray();
			$totalCant = $cantidadPas2[0] + $cantidadI;

			$db5->exec("UPDATE Recordar SET CantidadActual=$totalCant WHERE CodigoDeBarras=$codigoBarras;");
	    }

		$db5->close();

		$db = new MiBD();

		//$db->exec("INSERT INTO Almacen (TipoDeProducto, CodigoDeBarras, CantidadInicial, Marca, Proveedor) VALUES ('$tipoProd', '$codigoBarras' ,'$cantidadI', '$marca', '$proveedor');");
		$db->exec("INSERT INTO Almacen (CodigoDeBarras, CantidadInicial, Proveedor) VALUES ('$codigoBarras' ,'$cantidadI', '$proveedor');");

		$db->close();
		unset($_POST['submit']);
		$url = 'insertar.php';
		header('Location: '.$url);

	}

?>

</body>
</html>