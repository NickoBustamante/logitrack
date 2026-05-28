<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: login.php'); exit; }

require_once '../config/conexion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    
    if (empty($nombre) || empty($email)) {
        $error = 'Nombre y email son obligatorios';
    } else {
        $stmt = $con->prepare("INSERT INTO clientes (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $telefono, $direccion);
        if ($stmt->execute()) {
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Error al guardar';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Cliente | LogiTrack</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .form-container { width: 100%; max-width: 520px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.10); }
        h1 { text-align: center; font-size: 28px; margin-bottom: 24px; }
        label { font-weight: bold; display: block; margin-bottom: 6px; }
        input { width: 100%; padding: 12px; margin-bottom: 18px; border: 1px solid #ccc; border-radius: 6px; }
        .btn { width: 100%; padding: 12px; background: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #0056b3; }
        .btn-volver { display: block; text-align: center; margin-top: 16px; color: #666; text-decoration: none; }
        .error { background: #FEE2E2; color: #DC2626; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>➕ Nuevo Cliente</h1>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Nombre *</label>
            <input type="text" name="nombre" required>
            
            <label>Email *</label>
            <input type="email" name="email" required>
            
            <label>Teléfono</label>
            <input type="tel" name="telefono">
            
            <label>Dirección</label>
            <input type="text" name="direccion">
            
            <button type="submit" class="btn">Guardar Cliente</button>
        </form>
        <a href="admin.php" class="btn-volver">← Volver al panel</a>
    </div>
</body>
</html>