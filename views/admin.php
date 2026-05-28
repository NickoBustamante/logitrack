<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/conexion.php';

$stmt = $con->query("SELECT * FROM usuarios WHERE id = " . $_SESSION['id']);
$usuario = $stmt->fetch_assoc();

$stmt = $con->query("SELECT COUNT(*) as total FROM clientes");
$total_clientes = $stmt->fetch_assoc()['total'];

$stmt = $con->query("SELECT COUNT(*) as total FROM envios");
$total_envios = $stmt->fetch_assoc()['total'];

$stmt = $con->query("SELECT COUNT(*) as total FROM envios WHERE estado = 'pendiente'");
$envios_pendientes = $stmt->fetch_assoc()['total'];

$stmt = $con->query("SELECT COUNT(*) as total FROM envios WHERE estado = 'entregado'");
$envios_entregados = $stmt->fetch_assoc()['total'];

$stmt = $con->query("SELECT * FROM clientes ORDER BY id DESC LIMIT 20");
$clientes = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = $con->query("SELECT e.*, c.nombre as cliente_nombre FROM envios e LEFT JOIN clientes c ON e.cliente_id = c.id ORDER BY e.id DESC LIMIT 20");
$envios = $stmt->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | LogiTrack</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        
        .topbar { background: #1f2937; color: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
        .topbar .logo { font-size: 24px; font-weight: bold; }
        .topbar .menu { display: flex; gap: 20px; align-items: center; }
        .topbar .menu a { color: white; text-decoration: none; font-weight: 500; }
        .topbar .menu a:hover { text-decoration: underline; }
        
        .page-title { text-align: center; margin: 35px 0 25px; font-size: 42px; }
        
        .container { width: 90%; max-width: 1100px; margin: 0 auto; padding-bottom: 40px; }
        
        .cards { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin: 30px 0; }
        .card { background: white; padding: 20px; border-radius: 10px; width: 200px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card h2 { margin: 10px 0; font-size: 24px; }
        .card p { margin: 0; color: gray; }
        
        .acciones-top { text-align: center; margin-bottom: 25px; }
        
        .btn { display: inline-block; padding: 10px 18px; border-radius: 6px; text-decoration: none; background: #007BFF; color: white; font-weight: 500; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; transform: translateY(-2px); }
        .btn-secundario { background: #6c757d; }
        .btn-secundario:hover { background: #545b62; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #a71d2a; }
        
        .tabla-contenedor { background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); overflow: hidden; padding: 20px; }
        
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f1f5f9; font-weight: bold; }
        tr:hover { background: #f9f9f9; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-pendiente { background: #FEF3C7; color: #92400E; }
        .badge-entregado { background: #D1FAE5; color: #065F46; }
        
        .accion-link { text-decoration: none; margin-right: 10px; font-weight: bold; }
        .editar { color: #007BFF; }
        .eliminar { color: red; }

        .tabs { display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; }
        .tab { padding: 12px 24px; cursor: pointer; font-weight: 500; color: #6b7280; }
        .tab.active { border-bottom: 2px solid #007BFF; color: #007BFF; }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="logo">LogiTrack</div>
        <div class="menu">
            <a href="../index.php">🏠 Inicio</a>
            <a href="#clientes">Clientes</a>
            <a href="#envios">Envíos</a>
            <span>👤 <?php echo $usuario['nombre']; ?></span>
            <a href="logout.php" class="btn btn-danger" style="padding: 6px 12px; font-size: 14px;">Salir</a>
        </div>
    </header>

    <h1 class="page-title">Panel de Administración</h1>
    
    <div class="container">
        <div class="cards">
            <div class="card">
                <p>Clientes</p>
                <h2><?php echo $total_clientes; ?></h2>
            </div>
            <div class="card">
                <p>Total Envíos</p>
                <h2><?php echo $total_envios; ?></h2>
            </div>
            <div class="card">
                <p>Pendientes</p>
                <h2><?php echo $envios_pendientes; ?></h2>
            </div>
            <div class="card">
                <p>Entregados</p>
                <h2><?php echo $envios_entregados; ?></h2>
            </div>
        </div>

        <div class="acciones-top">
            <a href="cliente_nuevo.php" class="btn">➕ Nuevo Cliente</a>
            <a href="envio_nuevo.php" class="btn btn-secundario">➕ Nuevo Envío</a>
        </div>

        <div class="tabs">
            <div class="tab active" onclick="switchTab('clientes')">📋 Clientes</div>
            <div class="tab" onclick="switchTab('envios')">📦 Envíos</div>
        </div>

        <div id="tab-clientes" class="tab-content active">
            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                        <tr><td colspan="6" style="text-align:center;color:gray;">No hay clientes registrados</td></tr>
                        <?php else: foreach($clientes as $c): ?>
                        <tr>
                            <td>#<?php echo $c['id']; ?></td>
                            <td><?php echo $c['nombre']; ?></td>
                            <td><?php echo $c['email']; ?></td>
                            <td><?php echo $c['telefono']; ?></td>
                            <td><?php echo $c['direccion']; ?></td>
                            <td>
                                <a href="cliente_editar.php?id=<?php echo $c['id']; ?>" class="accion-link editar">Editar</a>
                                <a href="cliente_eliminar.php?id=<?php echo $c['id']; ?>" class="accion-link eliminar" onclick="return confirm('¿Eliminar cliente?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-envios" class="tab-content">
            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($envios)): ?>
                        <tr><td colspan="6" style="text-align:center;color:gray;">No hay envíos registrados</td></tr>
                        <?php else: foreach($envios as $e): ?>
                        <tr>
                            <td>#<?php echo $e['id']; ?></td>
                            <td><?php echo $e['cliente_nombre']; ?></td>
                            <td><?php echo $e['descripcion']; ?></td>
                            <td><?php echo $e['fecha']; ?></td>
                            <td><span class="badge badge-<?php echo $e['estado']; ?>"><?php echo ucfirst($e['estado']); ?></span></td>
                            <td>
                                <a href="envio_editar.php?id=<?php echo $e['id']; ?>" class="accion-link editar">Editar</a>
                                <a href="envio_eliminar.php?id=<?php echo $e['id']; ?>" class="accion-link eliminar" onclick="return confirm('¿Eliminar envío?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        }
    </script>
</body>
</html>