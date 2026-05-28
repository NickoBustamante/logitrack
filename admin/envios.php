<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";

if ($busqueda !== "") {
    $sql = "SELECT envios.*, clientes.nombre 
            FROM envios
            LEFT JOIN clientes ON envios.cliente_id = clientes.id
            WHERE clientes.nombre LIKE ?
               OR envios.referencia LIKE ?
               OR envios.origen LIKE ?
               OR envios.destino LIKE ?
               OR envios.fecha_envio LIKE ?
               OR envios.estado LIKE ?
            ORDER BY envios.fecha_envio DESC, envios.id DESC";
    $stmt = $conexion->prepare($sql);
    $param = "%" . $busqueda . "%";
    $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT envios.*, clientes.nombre 
            FROM envios 
            LEFT JOIN clientes ON envios.cliente_id = clientes.id
            ORDER BY envios.fecha_envio DESC, envios.id DESC";
    $resultado = $conexion->query($sql);
}

$totalEnvios = $resultado->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envíos - LogiTrack</title>
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

        .envios-wrapper {
            max-width: 1280px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        .envios-hero {
            text-align: center;
            margin-bottom: 28px;
        }

        .envios-kicker {
            display: inline-block;
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .envios-hero h1 {
            margin: 0 0 10px;
            font-size: 44px;
            color: #111827;
            letter-spacing: -1px;
        }

        .envios-hero p {
            margin: 0 auto;
            max-width: 760px;
            color: #6b7280;
            font-size: 17px;
            line-height: 1.7;
        }

        .envios-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .envios-topbar .info-box {
            background: white;
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            color: #4b5563;
            font-size: 15px;
        }

        .envios-topbar .info-box strong {
            color: #111827;
            font-size: 24px;
            display: block;
            margin-top: 4px;
        }

        .acciones-top {
            margin-bottom: 0;
        }

        .buscador-box {
            margin-bottom: 22px;
        }

        .buscador-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            background: white;
            padding: 18px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .buscador-form input {
            flex: 1;
            min-width: 260px;
            margin-bottom: 0;
        }

        .buscador-form button {
            width: auto;
            min-width: 120px;
            margin: 0;
            padding: 12px 22px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .buscador-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);
        }

        .buscador-form .btn {
            margin: 0;
        }

        .tabla-contenedor {
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            padding: 0;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8fafc;
            color: #111827;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 16px 14px;
        }

        td {
            padding: 16px 14px;
            color: #374151;
            border-top: 1px solid #eef2f7;
            vertical-align: middle;
        }

        tr {
            transition: all 0.2s ease;
        }

        tr:hover {
            background: #f9fbff;
        }

        .envio-referencia {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        .envio-cliente {
            font-weight: 600;
            color: #111827;
        }

        .envio-ruta {
            font-weight: 500;
        }

        .estado-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .estado-badge:hover {
            transform: scale(1.05);
        }

        .estado-pendiente {
            background: #fef3c7;
            color: #b45309;
        }

        .estado-entregado {
            background: #dcfce7;
            color: #166534;
        }

        .estado-transito {
            background: #dbeafe;
            color: #1e40af;
        }

        .estado-incidencia {
            background: #fee2e2;
            color: #991b1b;
        }

        .acciones-celda {
            white-space: nowrap;
        }

        .accion-link {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            margin-right: 8px;
        }

        .accion-link.editar {
            background: #eff6ff;
            color: #2563eb;
        }

        .accion-link.editar:hover {
            background: #dbeafe;
            transform: translateY(-1px);
        }

        .accion-link.eliminar {
            background: #fef2f2;
            color: #dc2626;
        }

        .accion-link.eliminar:hover {
            background: #fee2e2;
            transform: translateY(-1px);
        }

        .sin-envios {
            text-align: center;
            padding: 26px;
            color: #6b7280;
        }

        @media (max-width: 1100px) {
            .tabla-contenedor {
                overflow-x: auto;
            }

            table {
                min-width: 1180px;
            }
        }

        @media (max-width: 900px) {
            .envios-hero h1 {
                font-size: 34px;
            }
        }

        @media (max-width: 768px) {
            .buscador-form {
                flex-direction: column;
                align-items: stretch;
            }

            .buscador-form button,
            .buscador-form .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="logo">LogiTrack</div>
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="clientes.php">Clientes</a>
        <a href="envios.php">Envíos</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</div>

<div class="envios-wrapper">
    <div class="envios-hero">
        <div class="envios-kicker">Gestión de envíos</div>
        <h1>Administración de envíos</h1>
        <p>
            Gestiona el registro, la consulta y el seguimiento de los envíos asociados a clientes.
            Desde este módulo puedes controlar el estado actual de la operativa logística.
        </p>
    </div>

    <div class="envios-topbar">
        <div class="info-box">
            Total de envíos
            <strong><?php echo htmlspecialchars((string)$totalEnvios, ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>

        <div class="acciones-top">
            <a class="btn btn-secundario" href="dashboard.php">Volver al dashboard</a>
            <a class="btn" href="crear_envio.php">Nuevo envío</a>
        </div>
    </div>

    <div class="buscador-box">
        <form method="GET" class="buscador-form">
            <input 
                type="text" 
                name="busqueda" 
                placeholder="Buscar por referencia, cliente, origen, destino, fecha o estado"
                value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
            >
            <button type="submit">Buscar</button>
            <?php if ($busqueda !== "") { ?>
                <a class="btn btn-secundario" href="envios.php">Limpiar</a>
            <?php } ?>
        </form>
    </div>

    <div class="tabla-contenedor">
        <table>
            <tr>
                <th>ID</th>
                <th>Referencia</th>
                <th>Cliente</th>
                <th>📍 Origen</th>
                <th>🎯 Destino</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php if ($resultado->num_rows > 0) { ?>
                <?php while ($fila = $resultado->fetch_assoc()) { ?>
                    <?php
                        $estado = $fila["estado"];
                        $claseEstado = "estado-pendiente";

                        if ($estado === "Entregado") {
                            $claseEstado = "estado-entregado";
                        } elseif ($estado === "En tránsito") {
                            $claseEstado = "estado-transito";
                        } elseif ($estado === "Incidencia") {
                            $claseEstado = "estado-incidencia";
                        }

                        $referencia = isset($fila["referencia"]) ? trim((string)$fila["referencia"]) : "";
                        $fechaFormateada = !empty($fila["fecha_envio"]) ? date("d/m/Y", strtotime($fila["fecha_envio"])) : "—";
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <span class="envio-referencia" title="Referencia del envío">
                                <?php echo $referencia !== "" ? htmlspecialchars($referencia, ENT_QUOTES, 'UTF-8') : "—"; ?>
                            </span>
                        </td>
                        <td class="envio-cliente"><?php echo htmlspecialchars((string)$fila["nombre"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="envio-ruta"><?php echo htmlspecialchars((string)$fila["origen"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="envio-ruta"><?php echo htmlspecialchars((string)$fila["destino"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($fechaFormateada, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <span class="estado-badge <?php echo htmlspecialchars($claseEstado, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td class="acciones-celda">
                            <a class="accion-link editar" href="editar_envio.php?id=<?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?>">
                                <span style="font-size:14px;">✏️</span>
                                <span>Editar</span>
                            </a>
                            <a class="accion-link eliminar" href="eliminar_envio.php?id=<?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?>" onclick="return confirm('¿Eliminar envío?')">
                                <span style="font-size:14px;">🗑️</span>
                                <span>Eliminar</span>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8" class="sin-envios">No hay envíos registrados.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>