<html>
<head>
	
</head>
<body>
	<h1>Modificar descripcion</h1>
</body>
</html>

<?php

    class MiBD extends SQLite3
    {
        function __construct()
        {
            $this->open('test.db');
        }
    }

	if(isset($_POST["submit"])){

		$marcaP = $_POST["marca"];
		$codigoBarras = $_POST["submit"];

		echo "$descrip </br>";
		echo "$codigoBarras </br>";
		echo "$pru";

		$db = new MiBD();

		$db->exec("UPDATE Recordar SET Marca='$marcaP' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db));

		$db->close();
		unset($_POST['submit']);
		$url = 'almacen.php';
		header('Location: '.$url);

	}

?>