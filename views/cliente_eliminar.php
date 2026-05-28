<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: login.php'); exit; }

require_once '../config/conexion.php';
$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $con->query("DELETE FROM clientes WHERE id = $id");
}

header('Location: admin.php');
exit;