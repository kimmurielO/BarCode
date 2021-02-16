<html>
<head>
	
</head>
<body>
	<h1>Modificar</h1>
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

    echo "Hola";

	if(isset($_POST["submit"])){

		$descrip = $_POST["descrip"];
		$codigoBarras = $_POST["codigoB"];
		$pru = $_POST["submit"];

		echo "$descrip </br>";
		echo "$codigoBarras </br>";
		echo "$pru";

		$db = new MiBD();

		$db->exec("UPDATE Recordar SET Descripcion='$descrip' WHERE CodigoDeBarras=$codigoBarras");

		$db->close();
		unset($_POST['submit']);
		//$url = 'almacen.php';
		//header('Location: '.$url);
	}

?>