<?php
$page = 'inicio'; // Define la página actual
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

// Incluir los archivos con las funciones de consulta
include '../../php/inicio/precio_producto.php';
include '../../php/inicio/precio_ventas.php';
include '../../php/inicio/precio_compras.php';

// Parámetros de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "GestorInventario";

// Obtener los totales de precios
$precioTotalProductos = getTotalProductCost($servername, $username, $password, $database);
$precioTotalSalidas = getTotalSalidaPrice($servername, $username, $password, $database);
$precioTotalEntradas = getTotalEntradaPrice($servername, $username, $password, $database);

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sqlCantidad = "SELECT Nombre, CantidadDisponible FROM Producto ORDER BY CantidadDisponible DESC LIMIT 10";
$resultCantidad = $conn->query($sqlCantidad);

$sqlPrecio = "SELECT Nombre, (Precio * CantidadDisponible) AS PrecioTotal FROM Producto ORDER BY PrecioTotal DESC LIMIT 10";
$resultPrecio = $conn->query($sqlPrecio);

$productNamesCantidad = [];
$productQuantities = [];
$productNamesPrecio = [];
$productPrices = [];

if ($resultCantidad->num_rows > 0) {
    while ($row = $resultCantidad->fetch_assoc()) {
        $productNamesCantidad[] = $row['Nombre'];
        $productQuantities[] = $row['CantidadDisponible'];
    }
}

if ($resultPrecio->num_rows > 0) {
    while ($row = $resultPrecio->fetch_assoc()) {
        $productNamesPrecio[] = $row['Nombre'];
        $productPrices[] = $row['PrecioTotal'];
    }
}

// Asegurar que los arrays tengan la misma longitud
$maxProducts = max(count($productNamesCantidad), count($productNamesPrecio));
$productNamesCantidad = array_pad($productNamesCantidad, $maxProducts, '');
$productQuantities = array_pad($productQuantities, $maxProducts, 0);
$productNamesPrecio = array_pad($productNamesPrecio, $maxProducts, '');
$productPrices = array_pad($productPrices, $maxProducts, 0);

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/inicio/inicio_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.css">
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
            <img src="../../img/svg/house.svg" alt="Administrar acceso" class="title-svg">
            <span>Inicio</span>
        </div>
    </div>

    <div class="body-content">
        <div class="cards">
            <div class="info-item">
                <img src="../../img/svg/producto.svg" alt="Imagen 1" class="title-svg-1">
                <p>$<?php echo number_format($precioTotalProductos, 2); ?> <br> <span>Valor neto inventario</span></p>
            </div>
            <div class="info-item">
                <img src="../../img/svg/venta.svg" alt="Imagen 2" class="title-svg-1">
                <p>$<?php echo number_format($precioTotalSalidas, 2); ?> <br> <span> Ventas totales </span></p>
            </div>
            <div class="info-item">
                <img src="../../img/svg/compra.svg" alt="Imagen 3" class="title-svg-1">
                <p>$<?php echo number_format($precioTotalEntradas, 2); ?> <br> <span> Compras totales </span></p>
            </div>
        </div>

        <div class="grafico">
            <div class="container">
                <span>Productos con mas cantidad </span>
                <canvas id="cantidadChart" width="800" height="400"></canvas>
            </div>

            <div class="container">
                <span>Precios más altos de productos </span>
                <canvas id="totalChart" width="800" height="400"></canvas>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            var ctxCantidad = document.getElementById('cantidadChart').getContext('2d');
            var chartCantidad = new Chart(ctxCantidad, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($productNamesCantidad); ?>,
                    datasets: [{
                        label: 'Cantidad Disponible',
                        data: <?php echo json_encode($productQuantities); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctxTotal = document.getElementById('totalChart').getContext('2d');
            var totalChart = new Chart(ctxTotal, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($productNamesPrecio); ?>,
                    datasets: [{
                        label: 'Total por Producto',
                        data: <?php echo json_encode($productPrices); ?>,
                        backgroundColor: 'rgba(128, 0, 128, 0.2)',
                        borderColor: 'rgba(128, 0, 128, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value.toFixed(2);
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += '$' + context.parsed.y.toFixed(2);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>