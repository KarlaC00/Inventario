<?php
$page = 'venta'; // Define la página actual
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

// Obtener el IdSalida de la URL
$salidaId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$salidaId) {
    echo "No se proporcionó un ID de salida válido.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obtener los detalles de salida asociados con el IdSalida
$query = "
    SELECT 
        ds.Producto_IdProducto, 
        ds.Cantidad,
        p.Nombre as ProductoNombre,
        p.Descripcion as ProductoDescripcion,
        p.Imagen,
        sc.Nombre as SubcategoriaNombre,
        c.Nombre as CategoriaNombre,
        p.Precio
    FROM DetalleSalida ds
    JOIN Producto p ON ds.Producto_IdProducto = p.IdProducto
    JOIN Subcategoria sc ON p.Subcategoria_IdSubcategoria = sc.IdSubcategoria
    JOIN Categoria c ON sc.Categoria_IdCategoria = c.IdCategoria
    WHERE ds.Salida_IdSalida = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $salidaId);
$stmt->execute();
$result = $stmt->get_result();

// Arreglo para almacenar la información
$productos = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Convertir el campo de imagen a base64
    $row['Imagen'] = base64_encode($row['Imagen']);
    $productos[] = $row;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/venta/venta_styles.css">
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
            <img src="../../img/svg/sell.svg" alt="Venta" class="title-svg">
            <span>Venta</span>
        </div>
        <div class="body-content">
            <div id="search-container">
                <input type="text" id="search-input" placeholder="Buscar salidas...">
                <button id="search-button">Buscar</button>
            </div>
            <div id="action-buttons">
                <button id="add-button">Agregar salida</button>
            </div>
            <table id="venta-table">
                <thead>
                    <tr>
                        <th>Id Producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody id="venta-table-body">
                    <!-- Aquí se insertarán los datos mediante JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Redirigir a la página para agregar una nueva salida
        document.getElementById('add-button').addEventListener('click', function() {
            window.location.href = '../../pagina/venta/agregar_venta.php';
        });

        // Obtener el ID de salida para usar como filtro básico
        const salidaId = new URLSearchParams(window.location.search).get('id'); // Obtener de la URL

        // Función para buscar detalles de ventas usando un término de búsqueda y el filtro de `IdSalida`
        function searchVentas() {
            const searchTerm = document.getElementById('search-input').value.trim(); // Obtener el término de búsqueda

            let queryUrl = `../../php/venta/search_detalles_venta.php?id=${salidaId}`; // Base del URL con `IdSalida`

            if (searchTerm !== '') {
                queryUrl += `&search=${encodeURIComponent(searchTerm)}`; // Agregar el término de búsqueda
            }

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const data = JSON.parse(xhr.responseText); // Analizar la respuesta JSON
                        displayDetallesVenta(data); // Mostrar los resultados en la tabla
                    } else {
                        console.error('Error al buscar detalles de venta:', xhr.status);
                    }
                }
            };

            xhr.open('GET', queryUrl, true); // Solicitud GET
            xhr.send(); // Enviar la solicitud
        }

        // Función para mostrar los detalles de la venta en la tabla
        function displayDetallesVenta(detallesVenta) {
            const ventaTableBody = document.getElementById('venta-table-body');
            ventaTableBody.innerHTML = ''; // Limpiar cualquier contenido previo

            if (detallesVenta.length === 0) {
                const emptyRow = '<tr><td colspan="9">No se encontraron resultados.</td></tr>';
                ventaTableBody.innerHTML = emptyRow; // Mensaje cuando no hay resultados
            } else {
                // Mostrar todos los resultados
                detallesVenta.forEach(function(producto) {
                    const precioTotal = producto.Cantidad * producto.Precio; // Calcular el precio total

                    // Construir la fila de la tabla con los datos del producto
                    const row = `<tr>
                <td>${producto.Producto_IdProducto}</td>
                <td>${producto.ProductoNombre}</td>
                <td>${producto.ProductoDescripcion}</td>
                <td><img src="data:image/jpeg;base64,${producto.Imagen}" alt="Imagen de Producto" style="width: 100px;"></td>
                <td>${producto.CategoriaNombre}</td>
                <td>${producto.SubcategoriaNombre}</td>
                <td>${producto.Cantidad}</td>
                <td>${producto.Precio}</td>
                <td>${precioTotal}</td>
            </tr>`;

                    ventaTableBody.innerHTML += row; // Agregar la fila a la tabla
                });
            }
        }

        // Escucha eventos de búsqueda
        document.getElementById('search-button').addEventListener('click', searchVentas); // Buscar al hacer clic
        document.getElementById('search-input').addEventListener('keypress', function(event) {
            if (event.keyCode === 13) { // Buscar al presionar Enter
                searchVentas();
            }
        });

        // Al cargar la página, mostrar todos los detalles para el `IdSalida`
        document.addEventListener("DOMContentLoaded", function() {
            searchVentas(); // Cargar los detalles iniciales usando el `IdSalida`
        });
    </script>
</body>

</html>