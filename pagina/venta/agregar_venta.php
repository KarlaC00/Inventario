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

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GestorInventario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query SQL para obtener los nombres de los clientes
$queryClientes = "SELECT IdCliente, Nombre FROM Cliente WHERE Estado = 1";

// Ejecutar la consulta de clientes
$resultClientes = mysqli_query($conn, $queryClientes);

// Inicializar un array para almacenar los nombres de los clientes
$clientes = [];

// Obtener los nombres de los clientes del resultado de la consulta
if (mysqli_num_rows($resultClientes) > 0) {
    while ($row = mysqli_fetch_assoc($resultClientes)) {
        $clientes[$row['IdCliente']] = $row['Nombre'];
    }
}

// Query SQL para obtener los productos con sus categorías y subcategorías
$queryProductos = "SELECT p.IdProducto, p.Nombre, p.Descripcion, p.Imagen, p.Precio, p.CantidadDisponible, c.Nombre AS NombreCategoria, s.Nombre AS NombreSubcategoria
                    FROM Producto p
                    INNER JOIN Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
                    INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria
                    WHERE p.Estado = 1";

// Ejecutar la consulta de productos
$resultProductos = mysqli_query($conn, $queryProductos);

// Inicializar un array para almacenar los productos
$productos = [];

// Obtener los productos del resultado de la consulta
if (mysqli_num_rows($resultProductos) > 0) {
    while ($row = mysqli_fetch_assoc($resultProductos)) {
        // Convertir el campo de imagen a una cadena base64
        $row['Imagen'] = base64_encode($row['Imagen']);
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/venta/agregar_venta_styles.css">
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
            <div class="container">
                <div class="add-entry">
                    <img src="../../img/svg/add.svg" alt="Agregar Venta">
                    <span class="title">Nueva Venta</span>
                </div>
                <form action="../../php/venta/create_venta.php" method="POST" class="form-columns" onsubmit="return validarFormulario()">
                    <div class="input-salida">
                        <div class="input-group">
                            <label for="cliente">Cliente</label>
                            <select id="cliente" name="cliente" required>
                                <?php
                                // Iterar sobre los nombres de los clientes para generar las opciones del select
                                foreach ($clientes as $idCliente => $nombreCliente) {
                                    echo "<option value='$idCliente'>$nombreCliente</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="fecha_salida">Fecha de Salida</label>
                            <input type="date" id="fecha_salida" name="fecha_salida" required>
                        </div>
                        <button type="submit">Agregar Venta</button>
                    </div>
                    <table id="product-table">
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
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                        </tbody>
                    </table>
                    <div class="add-product-dropdown">
                        <div class="dropdown-toggle" onclick="toggleMenuContent()">
                            <img src="../../img/svg/add.svg" alt="Agregar Producto">
                            <span>Agregar Producto</span>
                        </div>
                        <div class="menu-content" id="product-menu-content">
                            <?php
                            // Iterar sobre los productos para generar los elementos del menú desplegable
                            foreach ($productos as $producto) {
                                // Convertir el producto a JSON
                                $producto_json = json_encode($producto);
                                echo "<a href='#' data-product-json='$producto_json'>{$producto['Nombre']}</a>";
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Función para validar si se ha seleccionado al menos un producto antes de enviar el formulario
        function validarFormulario() {
            var tablaProductos = document.getElementById("product-table-body");
            var filasProductos = tablaProductos.getElementsByTagName("tr");

            // Verificar si hay al menos una fila en la tabla de productos
            if (filasProductos.length === 0) {
                alert("Debes seleccionar al menos un producto antes de agregar la venta.");
                return false; // Evitar el envío del formulario si no hay productos seleccionados
            }

            // Si se han seleccionado productos, el formulario se enviará normalmente
            return true;
        }

        function toggleMenuContent() {
            var menuContent = document.getElementById("product-menu-content");
            menuContent.style.display = (menuContent.style.display === "block") ? "none" : "block";
        }

        // Escuchar los clics en los elementos del menú desplegable
        document.querySelectorAll('#product-menu-content a').forEach(item => {
            item.addEventListener('click', event => {
                const productJson = item.getAttribute('data-product-json');
                const productInfo = JSON.parse(productJson);
                addProductToTable(productInfo);
                // Eliminar el elemento del menú desplegable
                item.remove();
            });
        });

        // Función para agregar un producto a la tabla de productos
        function addProductToTable(productInfo) {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td><input type="hidden" name="idProducto[]" value="${productInfo.IdProducto}">${productInfo.IdProducto}</td>
            <td>${productInfo.Nombre}</td>
            <td>${productInfo.Descripcion}</td>
            <td><img src="data:image/jpeg;base64,${productInfo.Imagen}" alt="Imagen del producto" style="width: 100px;"></td>
            <td>${productInfo.NombreCategoria}</td>
            <td>${productInfo.NombreSubcategoria}</td>
            <td><input type="number" name="cantidad[]" value="1" min="1" max="${productInfo.CantidadDisponible}" class="quantity-input"></td>
            <td><input type="hidden" name="precio[]" value="${productInfo.Precio}">${productInfo.Precio}</td>
            <td>${productInfo.Precio}</td>
            <td><button class="remove-product-button"><img src="../../img/svg/delete.svg" alt="Eliminar"></button></td>
        `;
            const tbody = document.getElementById('product-table-body');
            tbody.appendChild(newRow);
            calculateTotalPrice(newRow);
            newRow.querySelector('.quantity-input').addEventListener('change', function() {
                calculateTotalPrice(newRow);
            });
            newRow.querySelector('.remove-product-button').addEventListener('click', function() {
                newRow.remove();
                // Obtener el ID del producto eliminado
                const productId = newRow.querySelector('td:first-child').textContent;
                // Obtener el objeto del producto eliminado
                const productInfo = getProductById(productId);
                // Agregar el producto nuevamente al menú desplegable
                addProductToMenu(productInfo);
            });
        }

        // Función para agregar un producto al menú desplegable
        function addProductToMenu(productInfo) {
            const menuContent = document.getElementById('product-menu-content');
            const productLink = document.createElement('a');
            productLink.href = '#';
            productLink.setAttribute('data-product-json', JSON.stringify(productInfo));
            productLink.textContent = productInfo.Nombre;
            menuContent.appendChild(productLink);
            productLink.addEventListener('click', function() {
                const productJson = productLink.getAttribute('data-product-json');
                const productInfo = JSON.parse(productJson);
                addProductToTable(productInfo);
                // Eliminar el elemento del menú desplegable después de agregarlo a la tabla
                productLink.remove();
            });
        }

        // Función para calcular el precio total de un producto basado en su cantidad
        function calculateTotalPrice(row) {
            const quantityInput = row.querySelector('.quantity-input');
            const priceCell = row.querySelectorAll('td')[7];
            const price = parseFloat(priceCell.textContent);
            const quantity = parseInt(quantityInput.value);
            const totalPrice = price * quantity;
            row.querySelectorAll('td')[8].textContent = totalPrice;
        }

        // Función para obtener el objeto de producto por su ID
        function getProductById(productId) {
            return productos.find(product => product.IdProducto === productId);
        }
    </script>
</body>

</html>