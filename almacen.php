<?php
    if (isset($_REQUEST['pos']))
        $inicio=$_REQUEST['pos'];
    else
        $inicio=0;
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>Tabla almacen</title>
    <link rel="stylesheet" href="./estilo2.css">
</head>
<body>

<header><h1 style={position:relative}>Almacen</h1></header>

<nav> 
    <ul>
        <li><a href="./almacen.php"> Almacen </a></li>
        <li><a href="./inventario.php"> Inventario </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./eliminar.php"> Eliminar </a></li>
    </ul>
</nav>

<?php

    class MiBD extends SQLite3
    {
        function __construct()
        {
            $this->open('test.db');
        }
    }

    $bd = new MiBD();

    $resultado = $bd->query("SELECT * FROM Recordar limit $inicio,25");

    echo "<table>";
    echo "<tr>";
    echo "<th> Código de barras</th>";
    echo "<th style='width:100px'> Cantidad actual </th>";
    echo "<th> Descripción </th>";
    echo "<th> Foto </th>";
    echo "<th> Recordatorio </th>";
    echo "<th> Marca</th>";
    echo "<th> Tipo de producto  </th>";
    echo "</tr>";
    echo "<br>";

    $impresos=0;
    while ($row = $resultado->fetchArray()) {
        $impresos++;
        $CantidadActual = $bd->query("SELECT CantidadActual FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $CantidadActual2 = $CantidadActual-> fetchArray();
        $CantidadAviso = $bd->query("SELECT RecordarCant FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $CantidadAviso2 =  $CantidadAviso -> fetchArray();

        if ($CantidadActual2[0] < $CantidadAviso2[0]){
            echo "<tr class='CantRojo'>";
        }
        elseif(($CantidadActual2[0] < 10+$CantidadAviso2[0]) && ($CantidadActual2[0] > $CantidadAviso2[0])){
            echo "<tr class='CantNaranja'>";
        }
        else{
            echo "<tr class='CantVerde'>";
        }

        $srcI="./Imagenes/$row[2]";

        echo "<td> $row[0] </td>";
        echo "<td style='width:100px'> $row[3] </td>";

        echo "<td> $row[1] </br></br>";
        echo "<form method='POST' action='modificarDescrip.php'>";
        echo "<label for='descrip'>Nueva descripción:</label> <input type='text' name='descrip' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        /*echo "<td> <img src='$srcI' alt='Inserta foto' width='150' height='150'><br><br>";*/
        echo "<td> <img  onclick='javascript:this.width=450;this.height=450' ondblclick='javascript:this.width=150;this.height=150' src='$srcI' alt='Inserta foto' width='150' height='150'><br><br>";
        echo "<form enctype='multipart/form-data' method='POST' action='modificarFoto.php'>";
        echo "<label for='fotoP'> Nueva foto:</label><input type='hidden' name='MAX_FILE_SIZE' value='1000000' required/><input type='file' name='fotoP' id='fotoP'><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "<td> $row[4] <br><br>";
        echo "<form method='POST' action='modificarRecordatorio.php'>";
        echo "<label for='recorda'>Nuevo nº:</label> <input type='text' name='recorda' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "<td> $row[5] <br><br>";
        echo "<form method='POST' action='modificarMarca.php'>";
        echo "<label for='marca'>Nueva marca:</label> <input type='text' name='marca' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "<td> $row[6] <br><br>";
        echo "<form method='POST' action='modificarTipo.php'>";
        echo "<label for='tipoP'>Nuevo tipo:</label> <input type='text' name='tipoP' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "</tr>";
    }
    echo "</table>";


    if ($inicio==0)
        echo "Anteriores ";
    else
    {
        $anterior=$inicio-25;
        echo "<a href=\"almacen.php?pos=$anterior\">Anteriores </a>";
    }
    if($impresos==25)
    {
        $proximo=$inicio+25;
        echo "<a href=\"almacen.php?pos=$proximo\">Siguientes</a>";
    }
    else
        echo "Siguientes";

?>

</body>
</html>