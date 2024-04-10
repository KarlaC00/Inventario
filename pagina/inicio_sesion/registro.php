<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../../css/inicio_sesion/registro_styles.css">
</head>
<body>
    <div class="container">
        <img src="../../Img/svg/registro.svg" alt="Registro" style="width: 60px; height: 60px;">
        <h1 class="title">Registro de Usuario</h1>
        <form action="../../Php/inicio_sesion/registrar_usuario.php" method="POST">
            <div class="input-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="input-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="input-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <div class="input-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="input-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="input-group">
                <label for="telefono">Número Telefónico</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            <div class="input-group">
                <label for="tipo_identificacion">Tipo de Identificación</label>
                <select id="tipo_identificacion" name="tipo_identificacion" required>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="RCN">Registro Civil de Nacimiento</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="RUT">Registro Único Tributario</option>
                    <option value="TIN">Tarjeta de Identificación Tributaria</option>
                    <option value="NIT">Número de Identificación Tributaria</option>
                    <option value="NUIP">Número Único de Identificación Personal</option>
                    <option value="EPS">Entidad Promotora de Salud</option>
                    <option value="ARP">Administradora de Riesgos Laborales</option>
                    <option value="PPE">Permiso Provisional Especial</option>
                    <option value="PEP">Permiso Especial de Permanencia</option>
                    <option value="TSE">Tarjeta de Servicios Electrónicos</option>
                    <option value="TP">Tarjeta Profesional</option>
                    <option value="CREMIL">Carné Militar</option>
                    <option value="RUNT">Registro Único Nacional de Tránsito</option>
                </select>
            </div>
            <div class="input-group">
                <label for="numero_identificacion">Número de Identificación</label>
                <input type="text" id="numero_identificacion" name="numero_identificacion" required>
            </div>
            <div class="input-group">
                <label for="nivel_acceso">Nivel de Acceso</label>
                <select id="nivel_acceso" name="nivel_acceso" required>
                    <option value="1">Escritura</option>
                    <option value="2">Lectura</option>
                </select>
            </div>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="../../index.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>