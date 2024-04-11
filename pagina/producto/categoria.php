<?php
$page = 'producto'; // Define la página actual
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
    <link rel="stylesheet" href="../../css/producto/producto_styles.css">
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
                    <!-- Puedes mantener la visualización del rol del usuario si lo deseas -->
                </div>
                <div class="dropdown-menu-container">
                    <div class="dropdown-toggle">
                        <img src="../../img/svg/option.svg" alt="Opciones">
                        <span>Opciones</span>
                    </div>
                    <div class="menu-content">
                        <a href="../../logout.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="title-section">
            <img src="../../img/svg/box.svg" alt="Productos" class="title-svg">
            <span>Productos</span>
        </div>
        <div class="body-content">
            <form id="formulario_categoria">
                <label for="nombreCategoria">Nombre de la Categoría:</label><br>
                <input type="text" id="nombreCategoria" name="nombreCategoria" required><br><br>
                
                <input type="submit" value="Guardar Categoría">
            </form>

            <h2>Crear Subcategoría</h2>
            <form id="formulario_subcategoria">
                <label for="nombreSubcategoria">Nombre de la Subcategoría:</label><br>
                <input type="text" id="nombreSubcategoria" name="nombreSubcategoria" required><br><br>
                
                <label for="categoriaPadre">Seleccione la Categoría Padre:</label><br>
                <select id="categoriaPadre" name="categoriaPadre" required>
                    <!-- Opciones de categorías se cargarán dinámicamente -->
                </select><br><br>
                
                <input type="submit" value="Guardar Subcategoría">
        </form>
        <button id="verCategorias">Volver</button> <!-- Botón para redirigir a la página de categorías -->
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../javascrip/producto/categoria.js"></script>
    </body>
</html>
