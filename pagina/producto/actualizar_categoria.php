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
    <link rel="stylesheet" href="../../css/producto/actualizar_categoria_style.css">
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
            <img src="../../img/svg/box.svg" alt="Categoría" class="title-svg">
            <span>Categoría</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/archive.svg" alt="Editar Categoría">
                    <span class="title">Editar Categoría</span>
                </div>
                <?php
                // Verificar si se recibió un ID de categoría válido
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $conn = mysqli_connect("localhost", "root", "", "gestorinventario");

                    // Verificar la conexión
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $idCategoria = $_GET['id'];

                    // Consulta SQL para obtener los datos de la categoría por su ID
                    $query = "SELECT * FROM Categoria WHERE IdCategoria = $idCategoria";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                ?>
                        <form action="../../php/producto/update_categoria.php" method="POST" class="form-columns">
                            <input type="hidden" name="idCategoria" value="<?php echo $row['IdCategoria']; ?>">
                            <div class="input-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="estado">Estado</label>
                                <select id="estado" name="estado" required>
                                    <option value="1" <?php if ($row['Estado'] == 1) echo 'selected'; ?>>Activo</option>
                                    <option value="0" <?php if ($row['Estado'] == 0) echo 'selected'; ?>>Inactivo</option>
                                </select>

                            </div>
                            <button type="submit">Actualizar</button>
                        </form>
                <?php
                    } else {
                        echo "<p>No se encontraron datos de categoría para editar.</p>";
                    }

                    mysqli_close($conn);
                } else {
                    echo "<p>No se proporcionó un ID de categoría válido.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="../../javascrip/producto/categoria_scripts.js"></script>
</body>
</html>