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


while ($row = $resultado->fetchArray()) {
    //var_dump($row);

    // Esto es tipo de producto?
    $nombre = $row[0];
    //var_dump($row[0]);
    echo "<tr>";
    print_r($nombre[0]);
    echo "</tr>";
}


$filas = sqlite_num_fields($resultado);

echo "Number of rows: $filas";

?>