<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

if (!isset($_GET["id"])) {
    die("ID de envio no especificado");
}

$id = $_GET["id"];

$clientes = $conexion->query("SELECT id, nombre FROM clientes");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST["cliente_id"];
    $origen = $_POST["origen"];
    $destino = $_POST["destino"];
    $fecha_envio = $_POST["fecha_envio"];
    $estado = $_POST["estado"];

    $sql = "UPDATE envios 
            SET cliente_id=?, origen=?, destino=?, fecha_envio=?, estado=? 
            WHERE id=?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("issssi", $cliente_id, $origen, $destino, $fecha_envio, $estado, $id);

    if ($stmt->execute()) {
        header("Location: envios.php");
        exit();
    }
}

$sql = "SELECT * FROM envios WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Envio no encontrado");
}

$envio = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar envío - LogiTrack</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eef2f7 0%, #dbe6f3 100%);
            margin: 0;
            padding: 0;
        }

        .form-page {
            max-width: 520px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .page-kicker {
            display: inline-block;
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0 0 10px;
            font-size: 38px;
            color: #111827;
            letter-spacing: -1px;
        }

        .page-subtitle {
            text-align: center;
            color: #6b7280;
            margin: 0 auto;
            font-size: 16px;
            line-height: 1.7;
            max-width: 460px;
        }

        .form-container {
            max-width: 100%;
            margin: 0;
            background: white;
            padding: 34px 30px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            text-align: left;
        }

        .form-container h2 {
            display: none;
        }

        label {
            display: block;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.20);
        }

        .botones {
            margin-top: 10px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.22);
        }

        .volver-wrap {
            text-align: center;
            margin-top: 18px;
        }

        .volver-wrap .btn {
            margin: 0;
        }

        @media (max-width: 768px) {
            .form-page {
                margin: 30px auto;
            }

            .page-header h1 {
                font-size: 30px;
            }

            .form-container {
                padding: 26px 22px;
            }
        }
    </style>
</head>
<body>

<div class="form-page">
    <div class="page-header">
        <div class="page-kicker">Gestión de envíos</div>
        <h1>Editar envío</h1>
        <p class="page-subtitle">Actualiza la información del envío seleccionado y mantén el seguimiento logístico al día.</p>
    </div>

    <div class="form-container">
        <form method="POST">
            <label>Cliente:</label>
            <select name="cliente_id" required>
                <?php while ($cliente = $clientes->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($cliente['id'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php if ($cliente['id'] == $envio['cliente_id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Origen:</label>
            <input type="text" name="origen" value="<?php echo htmlspecialchars($envio['origen'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Destino:</label>
            <input type="text" name="destino" value="<?php echo htmlspecialchars($envio['destino'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Fecha:</label>
            <input type="date" name="fecha_envio" value="<?php echo htmlspecialchars($envio['fecha_envio'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Estado:</label>
            <select name="estado" required>
                <option value="Pendiente" <?php if ($envio['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="En tránsito" <?php if ($envio['estado'] == 'En tránsito') echo 'selected'; ?>>En tránsito</option>
                <option value="Entregado" <?php if ($envio['estado'] == 'Entregado') echo 'selected'; ?>>Entregado</option>
                <option value="Incidencia" <?php if ($envio['estado'] == 'Incidencia') echo 'selected'; ?>>⚠️ Incidencia</option>
            </select>

            <div class="botones">
                <button type="submit">Actualizar envío</button>
            </div>
        </form>

        <div class="volver-wrap">
            <a class="btn btn-secundario" href="envios.php">Volver</a>
        </div>
    </div>
</div>

</body>
</html>