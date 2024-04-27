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
$precioTotalProductos = getTotalProductPrice($servername, $username, $password, $database);
$precioTotalSalidas = getTotalSalidaPrice($servername, $username, $password, $database);
$precioTotalEntradas = getTotalEntradaPrice($servername, $username, $password, $database);

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT Nombre, CantidadDisponible, Precio FROM Producto ORDER BY CantidadDisponible DESC LIMIT 20";
$result = $conn->query($sql);

// Arrays para almacenar nombres de productos, cantidades disponibles y precios
$productNames = [];
$productQuantities = [];
$productPrices = [];

if ($result->num_rows > 0) {
    // Almacenar datos en arrays
    while ($row = $result->fetch_assoc()) {
        $productNames[] = $row['Nombre'];
        $productQuantities[] = $row['CantidadDisponible'];
        $productPrices[] = $row['Precio'];
    }
}

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
        <div class="container">
            <div class="title-section">
                <div class="info-item">
                    <img src="../../img/svg/producto.svg" alt="Imagen 1" class="title-svg">
                    <p>Valor neto inventario <br> $<?php echo number_format($precioTotalProductos, 2); ?></p>
                </div>
                <div class="info-item">
                    <img src="../../img/svg/venta.svg" alt="Imagen 2" class="title-svg">
                    <p>Ventas <br> $<?php echo number_format($precioTotalSalidas, 2); ?></p>
                </div>
                <div class="info-item">
                    <img src="../../img/svg/compra.svg" alt="Imagen 3" class="title-svg">
                    <p>Compras <br> $<?php echo number_format($precioTotalEntradas, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content">
        <canvas id="productoChart" width="800" height="400"></canvas>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <script>
        $(document).ready(function(){
            var ctx = document.getElementById('productoChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($productNames); ?>,
                    datasets: [{
                        label: 'Cantidad Disponible',
                        data: <?php echo json_encode($productQuantities); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Precio',
                        data: <?php echo json_encode($productPrices); ?>,
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
        });
    </script>
</body>
</html>
