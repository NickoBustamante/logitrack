<?php
$host = "127.0.0.1";
$usuario = "TU_USUARIO";
$contrasena = "TU_CONTRASEÑA";
$basedatos = "logitrack";
$puerto = 3307;

$conexion = new mysqli($host, $usuario, $contrasena, $basedatos, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>