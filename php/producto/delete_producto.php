<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir el ID del producto a eliminar
$idProducto = $_POST['idProducto'];

// Verificar si el producto tiene entradas en DetalleEntrada
$query_check_entrada = "SELECT * FROM DetalleEntrada WHERE Producto_IdProducto = '$idProducto'";
$result_check_entrada = mysqli_query($conn, $query_check_entrada);

// Verificar si el producto tiene salidas en DetalleSalida
$query_check_salida = "SELECT * FROM DetalleSalida WHERE Producto_IdProducto = '$idProducto'";
$result_check_salida = mysqli_query($conn, $query_check_salida);

if (mysqli_num_rows($result_check_entrada) > 0 || mysqli_num_rows($result_check_salida) > 0) {
    // Si existen registros en DetalleEntrada o DetalleSalida para este producto, mostrar un mensaje de error
    echo "No se puede eliminar el producto porque está asociado a movimientos en el inventario.";
} else {
    // Si no hay entradas ni salidas asociadas, proceder con la eliminación del producto
    $query_delete = "DELETE FROM Producto WHERE IdProducto = '$idProducto'";
    
    if (mysqli_query($conn, $query_delete)) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto: " . mysqli_error($conn);
    }
}


// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>