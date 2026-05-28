<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";

if ($busqueda !== "") {
    $sql = "SELECT * FROM clientes 
            WHERE nombre LIKE ? 
               OR empresa LIKE ? 
               OR telefono LIKE ? 
               OR email LIKE ? 
               OR direccion LIKE ?";
    $stmt = $conexion->prepare($sql);
    $param = "%" . $busqueda . "%";
    $stmt->bind_param("sssss", $param, $param, $param, $param, $param);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT * FROM clientes ORDER BY id DESC";
    $resultado = $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - LogiTrack</title>
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

        .clientes-wrapper {
            max-width: 1280px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        .clientes-hero {
            text-align: center;
            margin-bottom: 28px;
        }

        .clientes-kicker {
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

        .clientes-hero h1 {
            margin: 0 0 10px;
            font-size: 44px;
            color: #111827;
            letter-spacing: -1px;
        }

        .clientes-hero p {
            margin: 0 auto;
            max-width: 760px;
            color: #6b7280;
            font-size: 17px;
            line-height: 1.7;
        }

        .clientes-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .clientes-topbar .info-box {
            background: white;
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            color: #4b5563;
            font-size: 15px;
        }

        .clientes-topbar .info-box strong {
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

        .cliente-nombre {
            font-weight: 600;
            color: #111827;
        }

        .cliente-empresa {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
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

        .sin-clientes {
            text-align: center;
            padding: 26px;
            color: #6b7280;
        }

        @media (max-width: 1100px) {
            .tabla-contenedor {
                overflow-x: auto;
            }

            table {
                min-width: 980px;
            }
        }

        @media (max-width: 900px) {
            .clientes-hero h1 {
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

<div class="clientes-wrapper">
    <div class="clientes-hero">
        <div class="clientes-kicker">Gestión de clientes</div>
        <h1>Administración de clientes</h1>
        <p>
            Consulta, edita y organiza la información de los clientes registrados en el sistema.
            Desde este módulo puedes mantener actualizada la base de datos comercial de LogiTrack.
        </p>
    </div>

    <div class="clientes-topbar">
        <div class="info-box">
            Total de clientes
            <strong><?php echo htmlspecialchars((string)$resultado->num_rows, ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>

        <div class="acciones-top">
            <a class="btn btn-secundario" href="dashboard.php">Volver al dashboard</a>
            <a class="btn" href="crear_cliente.php">Añadir cliente</a>
        </div>
    </div>

    <div class="buscador-box">
        <form method="GET" class="buscador-form">
            <input 
                type="text" 
                name="busqueda" 
                placeholder="Buscar por nombre, empresa, teléfono, email o dirección"
                value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
            >
            <button type="submit">Buscar</button>
            <?php if ($busqueda !== "") { ?>
                <a class="btn btn-secundario" href="clientes.php">Limpiar</a>
            <?php } ?>
        </form>
    </div>

    <div class="tabla-contenedor">
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Empresa</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>

            <?php if ($resultado->num_rows > 0) { ?>
                <?php while ($fila = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="cliente-nombre"><?php echo htmlspecialchars((string)$fila["nombre"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <span class="cliente-empresa">
                                <?php echo htmlspecialchars((string)$fila["empresa"], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars((string)$fila["telefono"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string)$fila["email"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars((string)$fila["direccion"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="acciones-celda">
                            <a class="accion-link editar" href="editar_cliente.php?id=<?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?>">
                                <span style="font-size:14px;">✏️</span>
                                <span>Editar</span>
                            </a>
                            <a class="accion-link eliminar" href="eliminar_cliente.php?id=<?php echo htmlspecialchars((string)$fila["id"], ENT_QUOTES, 'UTF-8'); ?>" onclick="return confirm('¿Eliminar cliente?')">
                                <span style="font-size:14px;">🗑️</span>
                                <span>Eliminar</span>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" class="sin-clientes">No hay clientes registrados.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>