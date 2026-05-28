<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

if (!isset($_GET["id"])) {
    die("ID de cliente no especificado");
}

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $empresa = $_POST["empresa"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $direccion = $_POST["direccion"];

    $sql = "UPDATE clientes SET nombre=?, empresa=?, telefono=?, email=?, direccion=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $empresa, $telefono, $email, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error al actualizar cliente";
    }
}

$sql = "SELECT * FROM clientes WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Cliente no encontrado");
}

$cliente = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente - LogiTrack</title>
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }

        input:focus {
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
        <div class="page-kicker">Gestión de clientes</div>
        <h1>Editar cliente</h1>
        <p class="page-subtitle">Actualiza los datos del cliente seleccionado y mantén la información comercial correctamente registrada.</p>
    </div>

    <div class="form-container">
        <form method="POST" action="">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente["nombre"], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label>Empresa:</label>
            <input type="text" name="empresa" value="<?php echo htmlspecialchars($cliente["empresa"], ENT_QUOTES, 'UTF-8'); ?>">

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente["telefono"], ENT_QUOTES, 'UTF-8'); ?>">

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($cliente["email"], ENT_QUOTES, 'UTF-8'); ?>">

            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente["direccion"], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="botones">
                <button type="submit">Actualizar cliente</button>
            </div>
        </form>

        <div class="volver-wrap">
            <a class="btn btn-secundario" href="clientes.php">Volver</a>
        </div>
    </div>
</div>

</body>
</html>