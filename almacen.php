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
    echo "<th> Descripción </th>";
    echo "<th> Foto </th>";
    echo "<th> Cantidad actual </th>";
    echo "<th> Recordatorio </th>";
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
        else{
            echo "<tr class='CantVerde'>";
        }

        //echo "<tr>";
        $srcI="./Imagenes/$row[2]";
        echo "<td> $row[0] </td>";

        echo "<td> $row[1] </br></br>";
        echo "<form method='POST' action='modificar.php'>";
        echo "<label for='codigoB'></label> <input type='text' name='codigoB' value='$row[0]' hidden>";
        echo "<label for='descrip'>Nueva descripción:</label> <input type='text' name='descrip' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "<td> <img src='$srcI' alt='Inserta foto' width='200' height='200'> </td>";

        echo "<td> $row[3] </td>";
        echo "<td> $row[4] </td>";
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