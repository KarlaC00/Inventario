<?php
$page = 'compra'; // Define la página actual
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirigir a la página de inicio de sesión si no se ha iniciado sesión
    header("Location: ../../index.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Obtener el ID del usuario que ha iniciado sesión
$usuario_IdUsuario = $_SESSION['loggedin'];

// Recibir datos del formulario
$fecha_entrada = $_POST['fecha_entrada'];
$proveedor_IdProveedor = $_POST['proveedor'];
$productos = isset($_POST['idProducto']) ? $_POST['idProducto'] : [];
$cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
$precios = isset($_POST['precio']) ? $_POST['precio'] : [];

// Verificar si se han seleccionado productos
if (!empty($productos)) {
    // Query SQL para insertar una nueva entrada
    $queryEntrada = "INSERT INTO Entrada (FechaEntrada, Proveedor_IdProveedor, Usuario_IdUsuario)
                     VALUES ('$fecha_entrada', '$proveedor_IdProveedor', '$usuario_IdUsuario')";

    if (mysqli_query($conn, $queryEntrada)) {
        // Obtener el ID de la entrada recién creada
        $idEntrada = mysqli_insert_id($conn);

        // Procesar detalles de entrada para cada producto seleccionado en el formulario
        foreach ($productos as $key => $idProducto) {
            $cantidad = $cantidades[$key];
            $precio = $precios[$key];

            // Calcular el precio total para el detalle de entrada
            $precioTotal = $cantidad * $precio;

            // Query SQL para insertar un nuevo detalle de entrada
            $queryDetalleEntrada = "INSERT INTO DetalleEntrada (Cantidad, PrecioEntrada, Producto_IdProducto, Entrada_IdEntrada)
                            VALUES ('$cantidad', '$precioTotal', '$idProducto', '$idEntrada')";

            // Ejecutar la consulta
            mysqli_query($conn, $queryDetalleEntrada);

            // Actualizar la cantidad disponible del producto
            $queryUpdateCantidad = "UPDATE Producto SET CantidadDisponible = CantidadDisponible + $cantidad WHERE IdProducto = '$idProducto'";
            mysqli_query($conn, $queryUpdateCantidad);
        }

        echo "Compra agregada correctamente.";
    } else {
        echo "Error al agregar la compra: " . mysqli_error($conn);
    }
} else {
    echo "Error: No se han seleccionado productos.";
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/compra/compra.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>