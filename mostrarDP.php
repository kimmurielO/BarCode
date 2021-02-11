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