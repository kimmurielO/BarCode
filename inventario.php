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
        <li><a href="./editar.php"> Editar </a></li>
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

    $resultado = $bd->query("SELECT * FROM Inventario limit $inicio,25") or die("Problemas en el select:".mysqli_error($bd));

    echo "<table>";
    echo "<tr>";
    echo "<th> Fecha </th>";
    echo "<th> Codigo de barras </th>";
    echo "<th> Cantidad inicial </th>";
    echo "<th> Marca </th>";
    echo "<th> Tipo de producto </th>";
    echo "<th> Proveedor </th>";
    echo "</tr>";
    echo "<br>";

    $impresos=0;
    while ($row = $resultado->fetchArray()) {
        $impresos++;

        $MarcaP = $bd->query("SELECT Marca FROM Recordar WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $MarcaP2 = $MarcaP-> fetchArray();
        $TipoP = $bd->query("SELECT TipoDeProducto FROM Recordar WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $TipoP2 = $TipoP-> fetchArray();

        if ($row[1] < 0){
            echo "<tr class='CantRojo'>";
        }
        else{
            echo "<tr class='CantVerde'>";
        }

        //echo "<tr>";
        echo "<td> $row[3] </td>";
        echo "<td> $row[0] </td>";
        echo "<td> $row[1] </td>";
        echo "<td> $MarcaP2[0] </td>";
        echo "<td> $TipoP2[0] </td>";
        echo "<td> $row[2] </td>";
        echo "</tr>";
    }
    echo "</table>";


    if ($inicio==0)
        echo "<div class='pagin'> Anteriores ";
    else
    {
        $anterior=$inicio-25;
        echo "<div class='pagin'> <a href=\"inventario.php?pos=$anterior\">Anteriores </a>";
    }
    if($impresos==25)
    {
        $proximo=$inicio+25;
        echo "<a href=\"inventario.php?pos=$proximo\">Siguientes</a> </div>";
    }
    else
        echo "Siguientes </div>";

?>

</body>
</html>