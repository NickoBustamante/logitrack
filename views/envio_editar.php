<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: login.php'); exit; }

require_once '../config/conexion.php';
$id = $_GET['id'] ?? 0;

$error = '';

$stmt = $con->query("SELECT * FROM clientes ORDER BY nombre");
$clientes = $stmt->fetch_all(MYSQLI_ASSOC);

$result = $con->query("SELECT * FROM envios WHERE id = $id");
$envio = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';
    
    if (empty($cliente_id) || empty($descripcion)) {
        $error = 'Cliente y descripción son obligatorios';
    } else {
        $stmt = $con->prepare("UPDATE envios SET cliente_id=?, descripcion=?, estado=? WHERE id=?");
        $stmt->bind_param("issi", $cliente_id, $descripcion, $estado, $id);
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
    <title>Editar Envío | LogiTrack</title>
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
        <h1>✏️ Editar Envío</h1>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Cliente *</label>
            <select name="cliente_id" required>
                <option value="">Seleccionar cliente...</option>
                <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo ($envio['cliente_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo $c['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Descripción *</label>
            <input type="text" name="descripcion" value="<?php echo $envio['descripcion'] ?? ''; ?>" required>
            
            <label>Estado</label>
            <select name="estado">
                <option value="pendiente" <?php echo ($envio['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_ruta" <?php echo ($envio['estado'] == 'en_ruta') ? 'selected' : ''; ?>>En ruta</option>
                <option value="entregado" <?php echo ($envio['estado'] == 'entregado') ? 'selected' : ''; ?>>Entregado</option>
            </select>
            
            <button type="submit" class="btn">Actualizar Envío</button>
        </form>
        <a href="admin.php" class="btn-volver">← Volver al panel</a>
    </div>
</body>
</html>