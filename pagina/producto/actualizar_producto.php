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
    <link rel="stylesheet" href="../../css/producto/actualizar_producto_styles.css">
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
            <span>Producto</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/archive.svg" alt="Editar Producto">
                    <span class="title">Editar Producto</span>
                </div>
                <?php
                // Verificar si se recibió un ID de producto válido
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "gestorinventario";

                    // Crear conexión
                    $conn = mysqli_connect($servername, $username, $password, $dbname);

                    // Verificar la conexión
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Obtener el ID del producto
                    $idProducto = $_GET['id'];

                    // Consulta SQL para obtener los datos del producto por su ID
                    $query = "SELECT * FROM Producto WHERE IdProducto = $idProducto";
                    $result = mysqli_query($conn, $query);

                    // Verificar si se encontraron resultados
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                ?>
                        <form action="../../php/producto/update_producto.php" method="POST" id="edit-product-form" enctype="multipart/form-data" class="form-columns">
                            <!-- Aquí van tus campos de formulario existentes -->
                            <input type="hidden" name="idProducto" value="<?php echo $row['IdProducto']; ?>">
                            <div class="input-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="precio">Precio</label>
                                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $row['Precio']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="cantidad_disponible">Cantidad Disponible</label>
                                <input type="number" id="cantidad_disponible" name="cantidad_disponible" value="<?php echo $row['CantidadDisponible']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="imagen">Imagen</label>
                                <input type="file" id="imagen" name="imagen" accept="image/*">
                            </div>
                            <div class="input-group">
                                <label for="categoria_id">Categoría</label>
                                <select id="categoria_id" name="categoria_id" required>
                                    <?php
                                    // Consultar las categorías
                                    $categoria_query = "SELECT IdCategoria, Nombre FROM Categoria";
                                    $categoria_result = mysqli_query($conn, $categoria_query);

                                    // Mostrar las opciones de categoría
                                    if (mysqli_num_rows($categoria_result) > 0) {
                                        while ($categoria_row = mysqli_fetch_assoc($categoria_result)) {
                                            $selected = ($categoria_row['IdCategoria'] == $row['Categoria_IdCategoria']) ? 'selected' : '';
                                            echo "<option value='" . $categoria_row['IdCategoria'] . "' $selected>" . $categoria_row['Nombre'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="subcategoria_id">Subcategoría</label>
                                <select id="subcategoria_id" name="subcategoria_id" required>
                                    <?php
                                    // Consultar las subcategorías
                                    $subcategoria_query = "SELECT IdSubcategoria, Nombre FROM Subcategoria";
                                    $subcategoria_result = mysqli_query($conn, $subcategoria_query);

                                    // Mostrar las opciones de subcategoría
                                    if (mysqli_num_rows($subcategoria_result) > 0) {
                                        while ($subcategoria_row = mysqli_fetch_assoc($subcategoria_result)) {
                                            $selected = ($subcategoria_row['IdSubcategoria'] == $row['Subcategoria_IdSubcategoria']) ? 'selected' : '';
                                            echo "<option value='" . $subcategoria_row['IdSubcategoria'] . "' $selected>" . $subcategoria_row['Nombre'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" rows="4" required><?php echo $row['Descripcion']; ?></textarea>
                            </div>
                            <div class="input-group">
                                <label for="estado">Estado:</label>
                                <select id="estado" name="estado" required>
                                    <option value="1" <?php if ($row['Estado'] == 1) echo 'selected'; ?>>Activo</option>
                                    <option value="0" <?php if ($row['Estado'] == 0) echo 'selected'; ?>>Inactivo</option>
                                </select>
                            </div>
                            <button type="submit">Actualizar</button>
                        </form>
                <?php
                    } else {
                        echo "<p>No se encontraron datos de producto para editar.</p>";
                    }

                    // Cerrar conexión a la base de datos
                    mysqli_close($conn);
                } else {
                    echo "<p>No se proporcionó un ID de producto válido.</p>";
                }
                ?>
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