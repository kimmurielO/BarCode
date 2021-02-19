<html>
<head>
	
</head>
<body>
	<h1>Modificar recordatorio máximo</h1>
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

		$recordatorioN = $_POST["recordaMax"];
		$codigoBarras = $_POST["submit"];

		$db = new MiBD();

		$db->exec("UPDATE Recordar SET Maximo='$recordatorioN' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db));

		$db->close();
		unset($_POST['submit']);
		$url = 'almacen.php';
		header('Location: '.$url);

	}

?>