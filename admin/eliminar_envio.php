<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

if (!isset($_GET["id"])) {
    die("ID no especificado");
}

$id = $_GET["id"];

$sql = "DELETE FROM envios WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: envios.php");
    exit();
} else {
    echo "Error al eliminar envio";
}
?>
