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

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Consultar las categorías disponibles
$query_categorias = "SELECT * FROM Categoria";
$result_categorias = mysqli_query($conn, $query_categorias);
$categorias = mysqli_fetch_all($result_categorias, MYSQLI_ASSOC);
mysqli_free_result($result_categorias);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/producto/agregar_categoria_styles.css">
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
            <img src="../../img/svg/box.svg" alt="Producto" class="title-svg">
            <span>Agregar Categoria</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/add_archive.svg" alt="Agregar Producto">
                    <span class="title">Agregar Categoria</span>
                </div>
                <form action="../../php/producto/create_categoria.php" method="POST" class="form-columns">
                    <input type="hidden" name="idCategoria" value="idCategoria">
                    <div class="input-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required><br><br>
                    </div>
                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>