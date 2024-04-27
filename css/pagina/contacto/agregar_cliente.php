<?php
$page = 'contacto'; // Define la página actual
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirigir a la página de inicio de sesión si no se ha iniciado sesión
    header("Location: ../../index.php");
    exit();
}

// Definir los roles según el nivel de acceso
$roles = [
    1 => "Escritura",
    2 => "Lectura"
];

// Obtener el nivel de acceso del usuario desde la sesión
$nivelAcceso = isset($_SESSION['nivelAcceso_IdnivelAcceso']) ? $_SESSION['nivelAcceso_IdnivelAcceso'] : null;

// Determinar el rol del usuario
$rolUsuario = isset($roles[$nivelAcceso]) ? $roles[$nivelAcceso] : "Desconocido";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/contacto/agregar_cliente_styles.css"> <!-- Cambiar el nombre del archivo CSS -->
</head>

<body>
    <?php include '../../sidebar.php'; ?>
    <div class="content">
        <div class="header">
            <div class="user-info">
                <div class="user-icon">
                    <img src="../../img/svg/icon.svg" alt="Usuario">
                </div>
                <div class="user-details">
                    <span style="font-size: 16px; font-weight: bold; margin-bottom: 2px;"><?php echo $_SESSION['usuario']; ?></span>
                    <span style="font-size: 12px;"><?php echo $rolUsuario; ?></span>
                </div>
                <div class="dropdown-menu-container">
                    <div class="dropdown-toggle">
                        <img src="../../img/svg/option.svg" alt="Opciones">
                        <span>Opciones</span>
                    </div>
                    <div class="menu-content">
                        <a href="../../logout.php">Cerrar sesión</a>
                        <a href="#">Ver usuario</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="title-section">
            <img src="../../img/svg/contact.svg" alt="Cliente" class="title-svg"> <!-- Cambiar el título y el icono si es necesario -->
            <span>Cliente</span> <!-- Cambiar el título -->
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/add_user.svg" alt="Agregar Cliente"> <!-- Cambiar el icono si es necesario -->
                    <span class="title">Agregar Cliente</span> <!-- Cambiar el título -->
                </div>
                <form action="../../php/contacto/create_cliente.php" method="POST" class="form-columns"> <!-- Cambiar la acción y el ID del formulario -->
                    <!-- Aquí van tus campos de formulario existentes -->
                    <div class="input-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="input-group">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" required>
                    </div>
                    <div class="input-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" required>
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
                    <div></div>
                    <button type="submit">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>