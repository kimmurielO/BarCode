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
    	<li><a href="./almacen.php"> Almacen </a></li>
        <li><a href="./inventario.php"> Inventario </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./eliminar.php"> Eliminar </a></li>
        <li><a href="./editar.php"> Editar </a></li>
    </ul>
</nav>

<section>

	<h2>Eliminar del inventario</h2>

	<form method="POST" action="" onsubmit="return validaciones();">
		<label for="codigoBarras"> Escanea el codigo de barras: </label>
		<input type="text" name="codigoBarras" id="codigoBarras" onfocus="myFunction(this)" required> * <br><br>
		<label for="cantE>"> Cantidad:</label>
		<input type="text" name="cantE" id="cantE" required> * <br><br>
	 	<input type="submit" name="submit" value="Eliminar">
	</form>

</section>

<p class="obligatorio">Los campos marcados son * campos obligatorios</p>

<script>

	function validaciones(){

		var valor2 = document.getElementById("cantE").value;

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

		$codigoBarras = $_POST["codigoBarras"];
		$cantidadE = - $_POST["cantE"];

		$db = new MiBD();

		$cantidad = $db->query("SELECT CantidadActual FROM Almacen WHERE CodigoDeBarras = $codigoBarras") or die("Problemas en el select:".mysqli_error($db));
		$cantidad2 = $cantidad -> fetchArray();
		$totalCant = $cantidad2[0] + $cantidadE;

		if($totalCant< 0){
			?>
				<script>alert('No hay existencias en el almacen');</script>
			<?php
		}
		else{
			$db->exec("INSERT INTO Inventario (CodigoDeBarras, CantidadInicial) VALUES ('$codigoBarras','$cantidadE');") or die("Problemas en el insert:".mysqli_error($db));
			$db->exec("UPDATE Almacen SET CantidadActual=$totalCant WHERE CodigoDeBarras=$codigoBarras;") or die("Problemas en el update:".mysqli_error($db));
		}


		$db->close();
		unset($_POST['submit']);
		$url = 'eliminar.php';
		//header('Location: '.$url);
}

?>


</body>
</html>