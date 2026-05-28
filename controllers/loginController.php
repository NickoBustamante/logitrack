<?php
require_once "../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario["password"])) {
            session_start();
            session_regenerate_id(true);

            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            header("Location: ../admin/dashboard.php");
            exit();
        }
    }

    header("Location: ../views/login.php?error=1");
    exit();
} else {
    header("Location: ../views/login.php");
    exit();
}
?>