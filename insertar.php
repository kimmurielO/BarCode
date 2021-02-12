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
        <li><a href="./eliminar.php"> Eliminar </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./mostrarDP.php"> Mostrar </a></li>
    </ul>
</nav>

<section>



<h2>Agregar al inventario</h2>

<form enctype="multipart/form-data" method="POST" action="" onsubmit="return validaciones();">
	<label for="codigoBarras"> Escanea el codigo de barras: </label>
	<input type="text" name="codigoBarras" id="codigoBarras" required><br><br>
	<label for="fname">Tipo de producto:</label>
	<input type="text" name="fname" required><br><br>
	<label for="cantI>"> Cantidad:</label>
	<input type="text" name="cantI" id="cantI" required><br><br>
	<label for="marca>"> Marca:</label>
	<input type="text" name="marca" id="marca" required><br><br>
	<label for="proveedor"> Proveedor:</label>
	<input type="text" name="proveedor" id="proveedor" required><br><br>
	<label for="fotoP"> Foto:</label>
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
	<input type="file" name="fotoP" id="fotoP"><br><br>
	<label for="descrip"> Descripción del producto:</label>
	<textarea name="descrip" placeholder="Escribe una breve descripción"></textarea><br><br>
 	<input type="submit" name="submit" value="Agregar">
</form>


</section>

<script>

	function validaciones(){

		var valor2 = document.getElementById("cantI").value;

		if (valor2 != ""){

			var valoresAceptados = /^[0-9]+$/;
			if (valor2.match(valoresAceptados)){
				//alert ("Es numérico");
				return true;
			} else {
         		alert ("La cantidad debe ser numérica");
         		return false;
    		}

		}
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
		$cantidadI = $_POST["cantI"];
		$marca = $_POST["marca"];
		$proveedor = $_POST["proveedor"];
		
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
	    	$nombreA = $db4->query("SELECT FotoP FROM Recordar WHERE CodigoDeBarras=$cb") or die('Consulta fallida: ' . mysqli_error($db4));

	        		
			while($nombreI = $nombreA->fetchArray()){
				unlink($directorio_destino . '/' . $nombreI[0]);
	        }
	        $db4->close();
	    }

	    // En este caso, no hemos insertado anteriormente este codigo de barras
	    if($contador == 0){

	    	guardarFoto();

			$db2 = new MiBD();
			$db2->exec("INSERT INTO Recordar (CodigoDeBarras, FotoP) VALUES ('$codigoBarras', '$nombreFoto');");
			$db2->close();

	    } else{

			eliminarFoto();
			guardarFoto();

			// Actualizo el campo en la BBDD
	    	$db2 = new MiBD();
			$db2->exec("UPDATE Recordar SET FotoP='$nombreFoto' WHERE CodigoDeBarras=$codigoBarras");

	        $db2->close();
	    }

		$db3->close();

	    /* No hemos contemplado que algo que tiene que ver con la imagen haya fallado (a excepción del numero de bytes del documento) o bien no se ha insertado ninguna */
	    	
	    $db = new MiBD();

		$db->exec("CREATE TABLE IF NOT EXISTS `Almacen` (`Nombre` varchar(35) NOT NULL);");

		$cantidad = $db->query("SELECT SUM(CantidadInicial) FROM Almacen WHERE CodigoDeBarras = $codigoBarras");
		$cantidad2 = $cantidad -> fetchArray();
		$totalCant = $cantidad2[0] + $cantidadI;

		if($cantidad2[0] < 0){
			echo "<script type='text/javascript'>alert('No hay suficientes existencias en almacen');</script>";
		}
		else{
			$db->exec("INSERT INTO Almacen (TipoDeProducto, CodigoDeBarras, Cantidad, CantidadInicial, Marca, Proveedor) VALUES ('$tipoProd', '$codigoBarras', '$totalCant' ,'$cantidadI', '$marca', '$proveedor');");
		}

		$db->close();
		unset($_POST['submit']);
		$url = 'insertar.php';
		header('Location: '.$url);

	}

?>

</body>
</html>