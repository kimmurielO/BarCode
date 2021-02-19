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
    <title>Editar</title>
    <link rel="stylesheet" href="./estilo2.css">
</head>
<body>

<header><h1 style={position:relative}>Editar</h1></header>

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
    echo "<th> Descripción </th>";
    echo "<th> Foto </th>";
    echo "<th> Mínimo </th>";
    echo "<th> Máximo </th>";
    echo "<th> Marca</th>";
    echo "<th> Tipo de producto  </th>";
    echo "</tr>";
    echo "<br>";

    $impresos=0;
    while ($row = $resultado->fetchArray()) {
        $impresos++;

        echo "<tr style='background-color: Gainsboro'>";

        $srcI="./Imagenes/$row[2]";

        echo "<td> $row[0] </td>";

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
        echo "<form method='POST' action='modificarRecordatorioMIN.php'>";
        echo "<label for='recordaMin'>Nuevo min:</label> <input type='text' name='recordaMin' required><br><br>";
        echo "<button type='submit' name='submit' value='$row[0]'> Modificar</button>";
        echo "</form> </td>";

        echo "<td> $row[4] <br><br>";
        echo "<form method='POST' action='modificarRecordatorioMAX.php'>";
        echo "<label for='recordaMax'>Nuevo max:</label> <input type='text' name='recordaMax' required><br><br>";
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