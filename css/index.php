<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="container">
        <img src="./img/svg/gestor.svg" alt="Gestor" style="width: 100px; height: 100px;">
        <h1 class="title">Gestión de Inventario</h1>
        <h2 class="subtitle">Inicio de Sesión</h2>
        <form action="./php/inicio_sesion/login.php" method="POST">
            <div class="input-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="input-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="input-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="./pagina/inicio_sesion/registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>