<html>
<head>
	
</head>
<body>
	<h1>Modificar recordatorio</h1>
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

		$recordatorioN = $_POST["recorda"];
		$codigoBarras = $_POST["submit"];

		$db = new MiBD();

		$db->exec("UPDATE Recordar SET RecordarCant='$recordatorioN' WHERE CodigoDeBarras=$codigoBarras");

		$db->close();
		unset($_POST['submit']);
		$url = 'almacen.php';
		header('Location: '.$url);

	}

?>