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

    $resultado = $bd->query("SELECT * FROM Recordar limit $inicio,25") or
        die("Problemas en el select:".mysqli_error($bd));

    echo "<table>";
    echo "<tr>";
    echo "<th> Código de barras</th>";
    echo "<th style='width:100px'> Cantidad actual </th>";
    echo "<th> Foto </th>";
    echo "<th> Proveedor </th>";
    echo "<th> Marca</th>";
    echo "<th> Tipo de producto  </th>";
    echo "</tr>";
    echo "<br>";

    $impresos=0;
    while ($row = $resultado->fetchArray()) {
        $impresos++;
        $CantidadActual = $bd->query("SELECT CantidadActual FROM Almacen WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $CantidadActual2 = $CantidadActual-> fetchArray();
        $CantidadAvisoMin = $bd->query("SELECT Minimo FROM Recordar WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $CantidadAvisoMin2 =  $CantidadAvisoMin -> fetchArray();
        $CantidadAvisoMax = $bd->query("SELECT Maximo FROM Recordar WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $CantidadAvisoMax2 =  $CantidadAvisoMax -> fetchArray();

        $Proveedor = $bd->query("SELECT Proveedor FROM Inventario WHERE CodigoDeBarras='$row[0]'") or die("Problemas en el select:".mysqli_error($bd));
        $Proveedor2 =  $Proveedor -> fetchArray();

        if ($CantidadActual2[0] <= $CantidadAvisoMin2[0]){
            echo "<tr class='CantRojo'>";
        }
        elseif(($CantidadActual2[0] >= $CantidadAvisoMax2[0])){
            echo "<tr class='CantVerde'>";
        }
        else{
            echo "<tr class='CantNaranja'>";
        }

        $srcI="./Imagenes/$row[2]";

        echo "<td> $row[0] </td>";
        echo "<td style='width:100px'> $row[3] </td>";
        echo "<td> <img  onclick='javascript:this.width=450;this.height=450' ondblclick='javascript:this.width=150;this.height=150' src='$srcI' alt='Inserta foto' width='150' height='150'></td>";
        echo "<td> $Proveedor2[0] </td>";
        echo "<td> $row[5] </td>";
        echo "<td> $row[6] </td>";

        echo "</tr>";
    }
    echo "</table>";


    if ($inicio==0)
        echo "<div class='pagin'> Anteriores ";
    else
    {
        $anterior=$inicio-25;
        echo "<div class='pagin'> <a href=\"almacen.php?pos=$anterior\">Anteriores </a>";
    }
    if($impresos==25)
    {
        $proximo=$inicio+25;
        echo "<a href=\"almacen.php?pos=$proximo\">Siguientes</a> </div>";
    }
    else
        echo "Siguientes </div>";

?>

</body>
</html>