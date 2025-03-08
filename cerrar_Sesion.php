<?php
// iniciar la sesion
session_start();

// cerrar la sesion
$_SESSION = [];

// redireccionar al inicio
header('Location: /');

