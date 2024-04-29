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

// Redirigir si el usuario no tiene permiso de escritura
if ($rolUsuario !== "Escritura") {
    header("Location: ../../pagina/producto/subcategoria.php");
    exit();
}
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
                        <a href="../../pagina/inicio/ver_usuario.php">Ver usuario</a>
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
                <form action="../../php/producto/update_subcategoria.php" method="POST" class="form-columns">
    <?php
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $conn = mysqli_connect("localhost", "root", "", "gestorinventario");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $idSubcategoria = $_GET['id'];

        $querySubcategoria = "SELECT * FROM Subcategoria WHERE IdSubcategoria = $idSubcategoria";
        $resultSubcategoria = mysqli_query($conn, $querySubcategoria);

        if (mysqli_num_rows($resultSubcategoria) > 0) {
            $rowSubcategoria = mysqli_fetch_assoc($resultSubcategoria);

            // Consulta para obtener todas las categorías
            $queryCategorias = "SELECT IdCategoria, Nombre FROM Categoria";
            $resultCategorias = mysqli_query($conn, $queryCategorias);
    ?>
            <input type="hidden" name="idSubcategoria" value="<?php echo $rowSubcategoria['IdSubcategoria']; ?>">
            <div class="input-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $rowSubcategoria['Nombre']; ?>" required>
            </div>
            <div class="input-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    <option value="1" <?php if ($rowSubcategoria['Estado'] == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($rowSubcategoria['Estado'] == 0) echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <div class="input-group">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" required>
                    <?php
                    while ($rowCategoria = mysqli_fetch_assoc($resultCategorias)) {
                        $categoriaId = $rowCategoria['IdCategoria'];
                        $categoriaNombre = $rowCategoria['Nombre'];
                        $selected = ($categoriaId == $rowSubcategoria['Categoria_IdCategoria']) ? 'selected' : '';
                        echo "<option value='$categoriaId' $selected>$categoriaNombre</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Actualizar</button>
    <?php
        } else {
            echo "<p>No se encontraron datos de subcategoría para editar.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>No se proporcionó un ID de subcategoría válido.</p>";
    }
    ?>
</form>
            </div>
        </div>
    </div>
    <script src="../../javascrip/producto/categoria_scripts.js"></script>
</body>
</html>