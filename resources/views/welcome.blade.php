<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a la Aplicación</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f6f9;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2rem;
            color: #04638b;
        }
        .btn-login {
            margin-top: 20px;
            padding: 12px 30px;
            background-color: #04638b;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-login:hover {
            background-color: #004c69;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenid@!</h1>
        <p>Estoy feliz de que estés aquí. Haz clic en el botón de abajo.</p>
        <a href="{{ route('login') }}" class="btn-login">Iniciar sesión</a>
    </div>
</body>
</html>
