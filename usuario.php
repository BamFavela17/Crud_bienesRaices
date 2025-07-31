<?php

// importar la conexion
require 'includes/config/database.php';
$db = conectarDB();

//crear email y password
$email = "correo@correo.com";
$password = "12345"; 

    //hashear el password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        //query para insertar en la base de datos
        $query = "INSERT INTO usuarios (email, password) VALUES ('$email', '$passwordHash'); ";

        echo $query;

        //agregarlo a la base de datos
        //mysqli_query($db, $query);
