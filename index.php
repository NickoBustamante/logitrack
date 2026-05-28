<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack | Gestión logística inteligente</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        .landing-topbar {
            background: rgba(31, 41, 55, 0.96);
            color: white;
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }

        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .landing-topbar .logo {
            font-size: 30px;
            font-weight: bold;
        }

        .landing-topbar nav {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .landing-topbar nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .landing-topbar nav a:hover {
            text-decoration: underline;
        }

        .nav-btn {
            background: #007BFF;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .nav-btn:hover {
            background: #0056b3;
            text-decoration: none !important;
        }

        .hero {
            background: linear-gradient(135deg, #e9eef5 0%, #dce5ef 100%);
            padding: 90px 40px 70px;
        }

        .hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 50px;
            align-items: center;
        }

        .hero-text {
            animation: fadeInUp 0.8s ease;
        }

        .hero-kicker {
            display: inline-block;
            background: rgba(0, 123, 255, 0.12);
            color: #0056b3;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .hero-text h1 {
            font-size: 58px;
            margin-bottom: 20px;
            line-height: 1.05;
            text-align: left;
            letter-spacing: -1px;
        }

        .hero-text p {
            font-size: 19px;
            color: #4b5563;
            line-height: 1.8;
            margin-bottom: 26px;
            max-width: 680px;
        }

        .hero-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
        }

        .hero-tag {
            background: white;
            padding: 10px 16px;
            border-radius: 999px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            font-size: 14px;
            color: #333;
        }

        .hero-actions .btn {
            margin-right: 12px;
        }

        .hero-visual {
            position: relative;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            animation: fadeIn 1s ease;
        }

        .hero-visual::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.20);
            pointer-events: none;
        }

        .hero-visual img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            min-height: 460px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .hero-placeholder {
            min-height: 460px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            background: #ffffff;
            font-size: 18px;
            line-height: 1.6;
        }

        .floating-card {
            position: absolute;
            right: 20px;
            bottom: 20px;
            z-index: 2;
            background: rgba(255,255,255,0.96);
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            min-width: 240px;
        }

        .floating-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .floating-card p {
            margin: 6px 0;
            color: #374151;
            font-size: 14px;
        }

        .section {
            padding: 80px 40px;
            background: #f8fafc;
        }

        .section.alt {
            background: #eef3f8;
        }

        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 40px;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            text-align: center;
            color: #6b7280;
            max-width: 800px;
            margin: 0 auto 40px;
            line-height: 1.8;
            font-size: 18px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover,
        .module-card:hover,
        .tech-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.12);
        }

        .feature-card h3 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 22px;
        }

        .feature-card p {
            margin: 0;
            color: #6b7280;
            line-height: 1.7;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .module-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border-top: 5px solid #007BFF;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .module-card h3 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .module-card ul {
            padding-left: 20px;
            margin: 0;
            color: #4b5563;
            line-height: 1.9;
        }

        .tech-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        .tech-item {
            background: white;
            border-radius: 12px;
            text-align: center;
            padding: 18px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cta-box {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .cta-box h2 {
            margin-top: 0;
            font-size: 38px;
        }

        .cta-box p {
            color: #d1d5db;
            max-width: 700px;
            margin: 0 auto 24px;
            line-height: 1.8;
            font-size: 18px;
        }

        .footer {
            background: #111827;
            color: #d1d5db;
            padding: 42px 20px;
            font-size: 14px;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer strong {
            color: white;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.15);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @media (max-width: 1100px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .tech-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .hero-inner,
            .modules-grid {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 42px;
            }

            .topbar-inner {
                flex-direction: column;
                gap: 14px;
                text-align: center;
            }

            .landing-topbar nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            .floating-card {
                position: static;
                margin: 16px;
            }
        }

        @media (max-width: 700px) {
            .features-grid,
            .tech-grid {
                grid-template-columns: 1fr;
            }

            .hero,
            .section {
                padding: 50px 20px;
            }

            .landing-topbar {
                padding: 18px 0;
            }

            .hero-actions .btn {
                display: block;
                margin: 0 0 12px 0;
                text-align: center;
            }

            .cta-box {
                padding: 34px 22px;
            }
        }
    </style>
</head>
<body>

<header class="landing-topbar">
    <div class="topbar-inner">
        <div class="logo">LogiTrack</div>
        <nav>
            <a href="#funcionalidades">Funcionalidades</a>
            <a href="#modulos">Módulos</a>
            <a href="#tecnologias">Tecnologías</a>
            <a class="nav-btn" href="views/login.php">Acceso</a>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="hero-inner">
        <div class="hero-text">
            <span class="hero-kicker">Plataforma de gestión logística</span>
            <h1>Gestión logística clara,<br>
            visual y <span class="highlight">centralizada</span>
            </h1>
            <p>
                LogiTrack es una aplicación web desarrollada bajo una arquitectura MVC,
                orientada a la gestión de clientes y envíos en entornos logísticos.
                Permite centralizar la información, mejorar la organización y facilitar
                la toma de decisiones mediante indicadores visuales.
            </p>

            <div class="hero-tags">
                <span class="hero-tag">Gestión de clientes</span>
                <span class="hero-tag">Control de envíos</span>
                <span class="hero-tag">Panel con métricas</span>
                <span class="hero-tag">Acceso seguro</span>
            </div>

            <div class="hero-actions">
                <a class="btn" href="views/login.php">Acceder al sistema</a>
                <a class="btn btn-secundario" href="views/login.php">Ir al panel de gestión</a>
            </div>
        </div>

        <div class="hero-visual">
            <img src="assets/img/logistica.jpg" alt="Gestión logística LogiTrack"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="hero-placeholder" style="display:none;">
                Añade una imagen en <strong>assets/img/logistica.jpg</strong><br><br>
                Puede ser una fotografía o ilustración relacionada con logística,
                transporte, almacenes o distribución.
            </div>

            <div class="floating-card">
                <h3>Vista general del sistema</h3>
                <p><strong>Módulos:</strong> clientes, envíos y dashboard</p>
                <p><strong>Arquitectura:</strong> MVC</p>
                <p><strong>Base de datos:</strong> MySQL relacional</p>
            </div>
        </div>
    </div>
</section>

<section id="funcionalidades" class="section">
    <div class="section-inner">
        <h2 class="section-title">Qué ofrece LogiTrack</h2>
        <p class="section-subtitle">
            La aplicación está planteada como una solución web de gestión interna para pequeñas
            organizaciones logísticas o empresas que necesiten centralizar la información básica
            de clientes y envíos en una única plataforma.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <h3>Acceso seguro</h3>
                <p>El sistema incorpora autenticación de usuarios para controlar el acceso al panel de gestión.</p>
            </div>

            <div class="feature-card">
                <h3>Gestión de clientes</h3>
                <p>Permite registrar, consultar, editar y eliminar clientes de forma ordenada y sencilla.</p>
            </div>

            <div class="feature-card">
                <h3>Control de envíos</h3>
                <p>Facilita la creación y el seguimiento de envíos vinculados a clientes dentro de la base de datos.</p>
            </div>

            <div class="feature-card">
                <h3>Indicadores visuales</h3>
                <p>El dashboard ofrece una visión rápida del volumen de clientes, envíos y estados del sistema.</p>
            </div>
        </div>
    </div>
</section>

<section id="modulos" class="section alt">
    <div class="section-inner">
        <h2 class="section-title">Módulos principales</h2>
        <p class="section-subtitle">
            LogiTrack se estructura en distintos apartados funcionales que simulan el comportamiento
            de una herramienta de gestión profesional dentro de un entorno logístico.
        </p>

        <div class="modules-grid">
            <div class="module-card">
                <h3>Panel de control</h3>
                <ul>
                    <li>Resumen visual del sistema</li>
                    <li>Total de clientes registrados</li>
                    <li>Total de envíos creados</li>
                    <li>Envíos pendientes, en tránsito y entregados</li>
                </ul>
            </div>

            <div class="module-card">
                <h3>Gestión de clientes</h3>
                <ul>
                    <li>Alta de nuevos clientes</li>
                    <li>Consulta de datos registrados</li>
                    <li>Edición de información</li>
                    <li>Eliminación de registros</li>
                </ul>
            </div>

            <div class="module-card">
                <h3>Gestión de envíos</h3>
                <ul>
                    <li>Registro de nuevos envíos</li>
                    <li>Asociación con clientes</li>
                    <li>Consulta y modificación de estado</li>
                    <li>Seguimiento básico del proceso</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="tecnologias" class="section">
    <div class="section-inner">
        <h2 class="section-title">Tecnologías utilizadas</h2>
        <p class="section-subtitle">
            El proyecto ha sido desarrollado con tecnologías vistas durante el ciclo formativo,
            combinando herramientas de frontend, backend y base de datos para construir una
            aplicación web funcional y estructurada.
        </p>

        <div class="tech-grid">
            <div class="tech-item">HTML5</div>
            <div class="tech-item">CSS3</div>
            <div class="tech-item">PHP</div>
            <div class="tech-item">MySQL</div>
            <div class="tech-item">XAMPP</div>
            <div class="tech-item">phpMyAdmin</div>
            <div class="tech-item">VS Code</div>
            <div class="tech-item">MVC</div>
            <div class="tech-item">CRUD</div>
            <div class="tech-item">Sesiones</div>
        </div>
    </div>
</section>

<section class="section alt section-cta">
    <div class="section-inner">
        <div class="cta-box">
            <h2>Una solución web para la gestión logística</h2>
            <p>
                LogiTrack ha sido diseñado como un proyecto de desarrollo web con enfoque práctico,
                integrando autenticación, base de datos relacional, módulos CRUD y un panel visual
                para la gestión de clientes y envíos en un entorno logístico.
            </p>
            <a class="btn" href="views/login.php">Entrar en la aplicación</a>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-inner">
        <div><strong>LogiTrack</strong> · Proyecto de Desarrollo de Aplicaciones Web</div>
        <div>Pedro Nicolás Bustamante · DAW · ILERNA</div>
    </div>
</footer>

</body>
</html>