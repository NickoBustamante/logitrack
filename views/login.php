<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LogiTrack</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #eef2f7 0%, #dbe6f3 100%);
            margin: 0;
            padding: 0;
        }

        .logo-icon {
            text-align: center;
            font-size: 38px;
            margin-bottom: 10px;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
            margin: 90px auto;
            background: white;
            padding: 40px 32px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            animation: fadeInUp 0.6s ease;
        }

        .titulo-app {
            text-align: center;
            margin-bottom: 8px;
            color: #1f2937;
            font-size: 42px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .subtitulo {
            text-align: center;
            color: #9ca3af;
            margin-bottom: 28px;
            font-size: 14px;
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 28px;
            font-size: 36px;
            font-weight: 700;
            color: #111827;
        }

        .error-msg {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.20);
        }

        input::placeholder {
            color: #9ca3af;
        }

        button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.22);
        }

        .info-login {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 18px;
        }

        @media (max-width: 768px) {
            .login-container {
                width: 92%;
                margin: 40px auto;
                padding: 28px 22px;
            }

            .titulo-app {
                font-size: 34px;
            }

            h2 {
                font-size: 30px;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo-icon">📦</div>
    <div class="titulo-app">LogiTrack</div>
    <div class="subtitulo">Acceso al panel de gestión</div>

    <h2>Iniciar sesión</h2>

    <?php if (isset($_GET["error"])) { ?>
        <div class="error-msg">Credenciales incorrectas. Inténtalo de nuevo.</div>
    <?php } ?>

    <form method="POST" action="../controllers/loginController.php">
        <label>Email:</label>
        <input type="email" name="email" placeholder="admin@logitrack.com" required>

        <label>Contraseña:</label>
        <input type="password" name="password" placeholder="Introduce tu contraseña" required>

        <button type="submit">Entrar</button>
    </form>

    <div class="info-login">Acceso restringido a usuarios autorizados.</div>
</div>

</body>
</html>