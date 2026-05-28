<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: login.php'); exit; }

require_once '../config/conexion.php';

$error = '';
$stmt = $con->query("SELECT * FROM clientes ORDER BY nombre");
$clientes = $stmt->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';
    $fecha = date('Y-m-d');
    
    if (empty($cliente_id) || empty($descripcion)) {
        $error = 'Cliente y descripción son obligatorios';
    } else {
        $stmt = $con->prepare("INSERT INTO envios (cliente_id, descripcion, estado, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $cliente_id, $descripcion, $estado, $fecha);
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
    <title>Nuevo Envío | LogiTrack</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .form-container { width: 100%; max-width: 520px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,0.10); }
        h1 { text-align: center; font-size: 28px; margin-bottom: 24px; }
        label { font-weight: bold; display: block; margin-bottom: 6px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 18px; border: 1px solid #ccc; border-radius: 6px; }
        .btn { width: 100%; padding: 12px; background: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #0056b3; }
        .btn-volver { display: block; text-align: center; margin-top: 16px; color: #666; text-decoration: none; }
        .error { background: #FEE2E2; color: #DC2626; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>📦 Nuevo Envío</h1>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Cliente *</label>
            <select name="cliente_id" required>
                <option value="">Seleccionar cliente...</option>
                <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Descripción *</label>
            <input type="text" name="descripcion" required>
            
            <label>Estado</label>
            <select name="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en_ruta">En ruta</option>
                <option value="entregado">Entregado</option>
            </select>
            
            <button type="submit" class="btn">Guardar Envío</button>
        </form>
        <a href="admin.php" class="btn-volver">← Volver al panel</a>
    </div>
</body>
</html>