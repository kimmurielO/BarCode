<html>
<head>
	
</head>
<body>
	<h1>Modificar foto</h1>
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

        $codigoBarras = $_POST["submit"];
		$img_file = $_FILES['fotoP']['name'];
        $img_type = $_FILES['fotoP']['type'];
        $tmp_name = $_FILES['fotoP']['tmp_name'];
        $directorio_destino = "./Imagenes";
        $nombreFoto = $codigoBarras . $img_file;
        $fallo = 0;

        function guardarFoto(){
            global $fallo;

        if( $_FILES['fotoP']['size'] > 800000 ) {
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

        function eliminarFoto(){
            global $codigoBarras;
            global $directorio_destino;
            $cb = $_POST["submit"];

            $db4 = new MiBD();
            $nombreA = $db4->query("SELECT FotoP FROM Recordar WHERE CodigoDeBarras=$cb") or die("Problemas en el select:".mysqli_error($db4));

                    
            while($nombreI = $nombreA->fetchArray()){
                unlink($directorio_destino . '/' . $nombreI[0]);
            }
            $db4->close();
        }

        $db = new MiBD();
        guardarFoto();

        if ($fallo == 0){
            eliminarFoto();
            $db->exec("UPDATE Recordar SET FotoP='$nombreFoto' WHERE CodigoDeBarras=$codigoBarras") or die("Problemas en el update:".mysqli_error($db));
        }

		$db->close();
		unset($_POST['submit']);
		$url = 'almacen.php';
		header('Location: '.$url);


	}

?>