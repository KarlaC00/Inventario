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
            <span>Agregar Producto</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/add_archive.svg" alt="Agregar Producto">
                    <span class="title">Agregar Producto</span>
                </div>
                <form action="../../php/producto/create_producto.php" method="POST" enctype="multipart/form-data" class="form-columns">
                    <div class="input-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
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
                        <label for="imagen">Imagen</label>
                        <input type="file" id="imagen" name="imagen" required accept="image/*">
                    </div>
                    <div class="input-group">
                        <label for="categoria_id">Categoría</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?php echo $categoria['IdCategoria']; ?>"><?php echo $categoria['Nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group" id="subcategoria_group">
                        <label for="subcategoria_id">Subcategoría</label>
                        <select id="subcategoria_id" name="subcategoria_id" required>
                            <!-- Las opciones de subcategoría se cargarán dinámicamente mediante JavaScript -->
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <button type="submit">Agregar</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Función para cargar las subcategorías correspondientes a la categoría seleccionada
        document.getElementById('categoria_id').addEventListener('change', function() {
            var categoriaId = this.value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../../php/producto/get_subcategorias.php?categoria_id=' + categoriaId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var subcategorias = JSON.parse(xhr.responseText);
                    var subcategoriaSelect = document.getElementById('subcategoria_id');
                    subcategoriaSelect.innerHTML = ''; // Limpiar las opciones existentes
                    subcategorias.forEach(function(subcategoria) {
                        var option = document.createElement('option');
                        option.text = subcategoria.Nombre;
                        option.value = subcategoria.IdSubcategoria;
                        subcategoriaSelect.add(option);
                    });

                    // Seleccionar una subcategoría por defecto si hay al menos una disponible
                    if (subcategorias.length > 0) {
                        subcategoriaSelect.selectedIndex = 0;
                    }
                }
            };
            xhr.send();
        });

        // Llamar manualmente al evento change para que se carguen las subcategorías al cargar la página
        document.getElementById('categoria_id').dispatchEvent(new Event('change'));
    </script>
</body>

</html>