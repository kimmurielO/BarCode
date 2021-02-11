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
        <li><a href="./eliminar.php"> Eliminar </a></li>
        <li><a href="./insertar.php"> Agregar </a></li>
        <li><a href="./mostrarDP.php"> Mostrar </a></li>
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

$resultado = $bd->query("SELECT * FROM Almacen");

//var_dump($resultado->fetchArray());

echo "<table>";
echo "<tr>";
echo "<th> Tipo de producto </th>";
echo "<th> Codigo de barras </th>";
echo "<th> Cantidad inicial </th>";
echo "<th> Cantidad actual </th>";
echo "<th> Marca </th>";
echo "<th> Proveedor </th>";
echo "</tr>";
echo "<br>";

while ($row = $resultado->fetchArray()) {
    echo "<tr>";
    echo "<td> $row[0] </td>";
    echo "<td> $row[1] </td>";
    echo "<td> $row[2] </td>";
    echo "<td> $row[3] </td>";
    echo "<td> $row[4] </td>";
    echo "<td> $row[5] </td>";
    echo "</tr>";
}
echo "</table>";

?>

</body>
</html>