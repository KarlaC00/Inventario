<?php
$page = 'venta'; // Define la página actual
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

// Obtener el id del usuario desde la sesión
$usuario_IdUsuario = $_SESSION['IdUsuario'];

// Recibir datos del formulario
$fecha_salida = $_POST['fecha_salida'];
$cliente_IdCliente = $_POST['cliente'];
$productos = isset($_POST['idProducto']) ? $_POST['idProducto'] : [];
$cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
$precios = isset($_POST['precio']) ? $_POST['precio'] : [];

// Verificar si se han seleccionado productos
if (!empty($productos)) {
    // Query SQL para insertar una nueva salida
    $querySalida = "INSERT INTO Salida (FechaSalida, Cliente_IdCliente, Usuario_IdUsuario)
                     VALUES ('$fecha_salida', '$cliente_IdCliente', '$usuario_IdUsuario')";

    if (mysqli_query($conn, $querySalida)) {
        // Obtener el ID de la saida recién creada
        $idSalida = mysqli_insert_id($conn);

        // Procesar detalles de salida para cada producto seleccionado en el formulario
        foreach ($productos as $key => $idProducto) {
            $cantidad = $cantidades[$key];
            $precio = $precios[$key];

            // Calcular el precio total para el detalle de salida
            $precioTotal = $cantidad * $precio;

            // Query SQL para insertar un nuevo detalle de salida
            $queryDetalleSalida = "INSERT INTO DetalleSalida (Cantidad, PrecioSalida, Producto_IdProducto, Salida_IdSalida)
                            VALUES ('$cantidad', '$precioTotal', '$idProducto', '$idSalida')";

            // Ejecutar la consulta
            mysqli_query($conn, $queryDetalleSalida);

            // Actualizar la cantidad disponible del producto
            $queryUpdateCantidad = "UPDATE Producto SET CantidadDisponible = CantidadDisponible - $cantidad WHERE IdProducto = '$idProducto'";
            mysqli_query($conn, $queryUpdateCantidad);
        }

        echo "Venta agregada correctamente.";
    } else {
        echo "Error al agregar la venta: " . mysqli_error($conn);
    }
} else {
    echo "Error: No se han seleccionado productos.";
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/venta/venta.php");
exit();
?>