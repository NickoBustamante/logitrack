<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once "../config/conexion.php";

$totalClientes = $conexion->query("SELECT COUNT(*) as total FROM clientes")->fetch_assoc()["total"];
$totalEnvios = $conexion->query("SELECT COUNT(*) as total FROM envios")->fetch_assoc()["total"];
$pendientes = $conexion->query("SELECT COUNT(*) as total FROM envios WHERE estado='Pendiente'")->fetch_assoc()["total"];
$entregados = $conexion->query("SELECT COUNT(*) as total FROM envios WHERE estado='Entregado'")->fetch_assoc()["total"];
$enTransito = $conexion->query("SELECT COUNT(*) as total FROM envios WHERE estado='En tránsito'")->fetch_assoc()["total"];
$incidencias = $conexion->query("SELECT COUNT(*) as total FROM envios WHERE estado='Incidencia'")->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LogiTrack</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eef2f7 0%, #dbe6f3 100%);
            margin: 0;
            padding: 0;
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        .dashboard-hero {
            text-align: center;
            margin-bottom: 35px;
            animation: fadeInUp 0.7s ease;
        }

        .dashboard-kicker {
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

        .dashboard-hero h1 {
            margin: 0 0 12px;
            font-size: 46px;
            color: #111827;
            letter-spacing: -1px;
        }

        .dashboard-hero p {
            margin: 0 auto;
            max-width: 700px;
            color: #6b7280;
            font-size: 16px;
            line-height: 1.8;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 22px;
            margin-bottom: 34px;
        }

        .stat-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(14px);
            border-radius: 18px;
            padding: 32px 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.4);
            text-align: center;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            animation: fadeInUp 0.8s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 16px 34px rgba(0,0,0,0.12);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .stat-title {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
            margin: 0;
        }

        .stat-clientes .stat-value {
            color: #2563eb;
        }

        .stat-envios .stat-value {
            color: #7c3aed;
        }

        .stat-pendientes .stat-value {
            color: #d97706;
        }

        .stat-transito .stat-value {
            color: #3b82f6;
        }

        .stat-entregados .stat-value {
            color: #16a34a;
        }

        .stat-incidencias .stat-value {
            color: #dc2626;
        }

        .dashboard-panels {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 24px;
        }

        .panel {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.35);
            animation: fadeInUp 0.9s ease;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .panel:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 34px rgba(0,0,0,0.12);
        }

        .panel h2 {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 28px;
            color: #111827;
        }

        .panel p {
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .quick-actions-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .quick-actions-cards a {
            display: block;
            padding: 16px;
            border-radius: 14px;
            background: rgba(255,255,255,0.9);
            text-decoration: none;
            color: #111827;
            font-weight: 600;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.5);
            transition: all 0.2s ease;
        }

        .quick-actions-cards a:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 26px rgba(0,0,0,0.12);
        }

        .user-card {
            background: linear-gradient(135deg, #1f2937, #0f172a);
            color: white;
        }

        .user-card h2 {
            color: white;
        }

        .user-data {
            display: grid;
            gap: 14px;
            margin-bottom: 22px;
        }

        .user-item {
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .user-label {
            font-size: 13px;
            color: #cbd5e1;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-value {
            font-size: 18px;
            font-weight: 600;
            color: white;
        }

        .dashboard-note {
            margin-top: 14px;
            font-size: 14px;
            color: #cbd5e1;
            line-height: 1.6;
        }

        .chart-panel {
            margin-top: 24px;
        }

        .chart-wrap {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.35);
            animation: fadeInUp 1s ease;
        }

        .chart-wrap h2 {
            margin: 0 0 10px;
            font-size: 28px;
            color: #111827;
        }

        .chart-wrap p {
            margin: 0 0 22px;
            color: #6b7280;
            line-height: 1.7;
        }

        .chart-canvas-box {
            position: relative;
            width: 100%;
            height: 320px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .dashboard-panels {
                grid-template-columns: 1fr;
            }

            .quick-actions-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .dashboard-hero h1 {
                font-size: 34px;
            }

            .dashboard-hero p {
                font-size: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .panel,
            .chart-wrap {
                padding: 22px;
            }

            .quick-actions-cards {
                grid-template-columns: 1fr;
            }

            .chart-canvas-box {
                height: 260px;
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

<div class="dashboard-wrapper">
    <div class="dashboard-hero">
        <div class="dashboard-kicker">Panel de control</div>
        <h1>Visión general del sistema</h1>
        <p>
            Control rápido de clientes, envíos y estado general de la operativa logística.
        </p>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-clientes">
            <div class="stat-icon">👥</div>
            <div class="stat-title">Total de clientes</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$totalClientes, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="stat-card stat-envios">
            <div class="stat-icon">📦</div>
            <div class="stat-title">Total de envíos</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$totalEnvios, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="stat-card stat-pendientes">
            <div class="stat-icon">⏳</div>
            <div class="stat-title">Pendientes</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$pendientes, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="stat-card stat-transito">
            <div class="stat-icon">🚚</div>
            <div class="stat-title">En tránsito</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$enTransito, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="stat-card stat-entregados">
            <div class="stat-icon">✅</div>
            <div class="stat-title">Entregados</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$entregados, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="stat-card stat-incidencias">
            <div class="stat-icon">⚠️</div>
            <div class="stat-title">Incidencias</div>
            <p class="stat-value"><?php echo htmlspecialchars((string)$incidencias, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>

    <div class="dashboard-panels">
        <div class="panel">
            <h2>Acciones rápidas</h2>
            <p>
                Desde este panel puedes acceder de forma directa a los principales módulos del sistema
                para gestionar clientes, registrar envíos y mantener el control general de la operativa.
            </p>

            <div class="quick-actions-cards">
                <a href="clientes.php">👥 Gestionar clientes</a>
                <a href="envios.php">📦 Gestionar envíos</a>
                <a href="crear_cliente.php">➕ Nuevo cliente</a>
                <a href="crear_envio.php">🚚 Nuevo envío</a>
            </div>
        </div>

        <div class="panel user-card">
            <h2>Sesión actual</h2>

            <div class="user-data">
                <div class="user-item">
                    <div class="user-label">Usuario</div>
                    <div class="user-value"><?php echo htmlspecialchars($_SESSION["usuario"], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>

                <div class="user-item">
                    <div class="user-label">Rol</div>
                    <div class="user-value"><?php echo htmlspecialchars($_SESSION["rol"], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>

            <a class="btn btn-danger" href="logout.php">Cerrar sesión</a>

            <div class="dashboard-note">
                Acceso autorizado al panel interno de LogiTrack. Desde aquí puedes gestionar
                la información principal del sistema.
            </div>
        </div>
    </div>

    <div class="chart-panel">
        <div class="chart-wrap">
            <h2>Distribución de envíos por estado</h2>
            <p>
                Resumen visual del estado actual de los envíos registrados en el sistema.
            </p>

            <div class="chart-canvas-box">
                <canvas id="graficaEnvios"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const dataEnvios = [
        <?php echo htmlspecialchars((string)$pendientes, ENT_QUOTES, 'UTF-8'); ?>,
        <?php echo htmlspecialchars((string)$enTransito, ENT_QUOTES, 'UTF-8'); ?>,
        <?php echo htmlspecialchars((string)$entregados, ENT_QUOTES, 'UTF-8'); ?>,
        <?php echo htmlspecialchars((string)$incidencias, ENT_QUOTES, 'UTF-8'); ?>
    ];

    const total = dataEnvios.reduce((a, b) => a + b, 0);
    const ctx = document.getElementById('graficaEnvios');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: total === 0
                ? ['Sin datos']
                : ['Pendiente', 'En tránsito', 'Entregado', 'Incidencia'],
            datasets: [{
                data: total === 0 ? [1] : dataEnvios,
                backgroundColor: total === 0
                    ? ['#e5e7eb']
                    : ['#f59e0b', '#3b82f6', '#22c55e', '#dc2626'],
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 18,
                        color: '#374151',
                        font: {
                            family: 'Inter',
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleFont: {
                        family: 'Inter',
                        size: 13,
                        weight: '700'
                    },
                    bodyFont: {
                        family: 'Inter',
                        size: 13
                    },
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            if (total === 0) {
                                return 'Sin datos';
                            }
                            const value = context.raw;
                            const percentage = ((value / total) * 100).toFixed(1) + '%';
                            return context.label + ': ' + value + ' (' + percentage + ')';
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>