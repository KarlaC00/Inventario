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
    <link rel="stylesheet" href="../../css/producto/agregar_producto_styles.css">
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
            <img src="../../img/svg/box.svg" alt="Producto" class="title-svg">
            <span>Agregar Producto</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/add_archive.svg" alt="Agregar Producto">
                    <span class="title">Agregar Producto</span>
                </div>
                <form action="../../php/producto/create_producto.php" method="POST" class="form-columns">
                    <div class="input-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="input-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="imagen">Imagen</label>
                        <input type="text" id="imagen" name="imagen" required>
                    </div>
                    <div class="input-group">
                        <label for="precio">Precio</label>
                        <input type="number" id="precio" name="precio" step="0.01" required>
                    </div>
                    <div class="input-group">
                        <label for="cantidad_disponible">Cantidad Disponible</label>
                        <input type="number" id="cantidad_disponible" name="cantidad_disponible" required>
                    </div>
                    <div class="input-group">
                        <label for="subcategoria">Subcategoría</label>
                        <input type="text" id="subcategoria" name="subcategoria" required>
                    </div>
                    <div></div>
                    <button type="submit">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>