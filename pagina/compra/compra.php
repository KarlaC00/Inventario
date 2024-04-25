<?php
$page = 'compra'; // Define la página actual
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
    <link rel="stylesheet" href="../../css/compra/compra_styles.css">
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
            <img src="../../img/svg/buy.svg" alt="Compra" class="title-svg">
            <span>Compra</span>
        </div>
        <div class="body-content">
            <div id="search-container">
                <input type="text" id="search-input" placeholder="Buscar entradas...">
                <button id="search-button">Buscar</button>
            </div>
            <div id="action-buttons">
                <button id="add-button">Agregar entrada</button>
            </div>
            <table id="compra-table">
                <thead>
                    <tr>
                        <th>Id Entrada</th>
                        <th>Fecha Entrada</th>
                        <th>Proveedor</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                        <th>Precio Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="compra-table-body"></tbody>
            </table>
        </div>
    </div>
    <script src="../../javascrip/compra/compra_scripts.js"></script>
</body>

</html>