<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
	<title>Eliminar producto de inventario</title>
	<link rel="stylesheet" href="./estilo.css">
</head>
<body>

<header><h1 style={position:relative}>Inventario</h1></header>

<nav> 
    <ul>
        <li><a href="./mostrarDP.php"> Mostrar </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./eliminar.php"> Eliminar </a></li>
    </ul>
</nav>

<section>



<h2>Eliminar del inventario</h2>

<form method="POST" action="" onsubmit="return validaciones();">
	<label for="codigoBarras"> Escanea el codigo de barras: </label>
	<input type="text" name="codigoBarras" id="codigoBarras" onfocus="myFunction(this)" required><br><br>
	<label for="cantE>"> Cantidad:</label>
	<input type="text" name="cantE" id="cantE" required><br><br>
 	<input type="submit" name="submit" value="Eliminar">
</form>


</section>

<script>

	function validaciones(){

		var valor2 = document.getElementById("cantE").value;

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

		$codigoBarras = $_POST["codigoBarras"];
		$cantidadE = - $_POST["cantE"];

		$db = new MiBD();

		$db->exec("CREATE TABLE IF NOT EXISTS `Almacen` (`Nombre` varchar(35) NOT NULL);");


		$cantidad = $db->query("SELECT CantidadActual FROM Recordar WHERE CodigoDeBarras = $codigoBarras");
		$cantidad2 = $cantidad -> fetchArray();
		$totalCant = $cantidad2[0] + $cantidadE;

		if($cantidad2[0] < 0){
			alert("No hay suficientes existencias en almacen");
		}
		else{
			$db->exec("INSERT INTO Almacen (CodigoDeBarras, CantidadInicial) VALUES ('$codigoBarras','$cantidadE');");
			$db->exec("UPDATE Recordar SET CantidadActual=$totalCant WHERE CodigoDeBarras=$codigoBarras;");
		}


		$db->close();
		unset($_POST['submit']);
		$url = 'eliminar.php';
		header('Location: '.$url);
}

?>

</body>
</html>