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
	<input type="text" name="codigoBarras" id="codigoBarras" onfocus="myFunction(this)" required><br><br>
	<label for="fname">Tipo de producto:</label>
	<input type="text" name="fname" required><br><br>
	<label for="cantI>"> Cantidad:</label>
	<input type="text" name="cantI" id="cantI" required><br><br>
	<label for="marca>"> Marca:</label>
	<input type="text" name="marca" id="marca" required><br><br>
	<label for="proveedor"> Proveedor:</label>
	<input type="text" name="proveedor" id="proveedor" required><br><br>
	<label for="fotoP"> Foto:</label>
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
				alert ("Es numérico");
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

		$nombreFoto = $codigoBarras . $img_file;

    	if (((strpos($img_type, "gif") || strpos($img_type, "jpeg") ||
 			strpos($img_type, "jpg")) || strpos($img_type, "png")))
    	{
        	if ( strpos($img_type, "jpeg") || strpos($img_type, "jpg") || strpos($img_type, "png") )
        	{
            	if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $nombreFoto))
            	{
            		// Si llegamos aqui hemos insertado la imagen

            		$db2 = new MiBD();

            		echo $nombreFoto;

            		$db2->exec("INSERT INTO Recordar (CodigoDeBarras, FotoP) VALUES ('$codigoBarras', '$nombreFoto');");

            		$db2->close();
       	     	}
        	}
    	}

    	/* Si llegamos hasta aquí es que algo ha fallado algo que tiene que ver con la imagen o bien no se ha insertado ninguna */
    	
    	$db = new MiBD();

		$db->exec("CREATE TABLE IF NOT EXISTS `Almacen` (`Nombre` varchar(35) NOT NULL);");


		$cantidad = $db->query("SELECT SUM(CantidadInicial) FROM Almacen WHERE CodigoDeBarras = $codigoBarras");
		$cantidad2 = $cantidad -> fetchArray();
		$totalCant = $cantidad2[0] + $cantidadI;

		if($cantidad2[0] < 0){
			alert("No hay suficientes existencias en almacen");
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