<?php
// funcion para conectar a la base de datos
function conectarDB() : mysqli{
    $db = mysqli_connect('localhost:3306', 'root', 'root', 'bienesraices_crud');

    // validar la conexion
    if(!$db) {
        echo "Error no se pudo conectar";
        exit;
    }
    return $db;
}