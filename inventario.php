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
    <title>Tabla inventario</title>
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
    $bd2 = new MiBD();

    $resultado = $bd->query("SELECT * FROM Almacen limit $inicio,25");

    echo "<table>";
    echo "<tr>";
    echo "<th> Tipo de producto </th>";
    echo "<th> Codigo de barras </th>";
    echo "<th> Cantidad inicial </th>";
    echo "<th> Marca </th>";
    echo "<th> Proveedor </th>";
    echo "<th> Fecha </th>";
    echo "</tr>";
    echo "<br>";

    $impresos=0;
    while ($row = $resultado->fetchArray()) {
        $impresos++;
        /*$CantidadActual = $bd->query("SELECT CantidadActual FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $CantidadActual2 = $CantidadActual-> fetchArray();
        $CantidadAviso = $bd->query("SELECT RecordarCant FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $CantidadAviso2 =  $CantidadAviso -> fetchArray();*/

        $MarcaP = $bd->query("SELECT Marca FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $MarcaP2 = $MarcaP-> fetchArray();
        $TipoP = $bd->query("SELECT TipoDeProducto FROM Recordar WHERE CodigoDeBarras='$row[0]'");
        $TipoP2 = $TipoP-> fetchArray();

        if ($row[1] < 0){
            echo "<tr class='CantRojo'>";
        }
        else{
            echo "<tr class='CantVerde'>";
        }

        //echo "<tr>";
        echo "<td> $MarcaP2[0] </td>";
        echo "<td> $row[0] </td>";
        echo "<td> $row[1] </td>";
        echo "<td> $TipoP2[0] </td>";
        echo "<td> $row[2] </td>";
        echo "<td> $row[3] </td>";
        echo "</tr>";
    }
    echo "</table>";


    if ($inicio==0)
        echo "Anteriores ";
    else
    {
        $anterior=$inicio-25;
        echo "<a href=\"inventario.php?pos=$anterior\">Anteriores </a>";
    }
    if($impresos==25)
    {
        $proximo=$inicio+25;
        echo "<a href=\"inventario.php?pos=$proximo\">Siguientes</a>";
    }
    else
        echo "Siguientes";

?>

</body>
</html>