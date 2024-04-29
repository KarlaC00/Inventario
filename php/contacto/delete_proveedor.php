<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crea la conexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Comprueba la conexion
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir el ID del proveedor a eliminar
$idProveedor = $_POST['idProveedor'];

// Consulta SQL para verificar si hay detalles de entrada asociados al proveedor
$query_check = "SELECT * FROM DetalleEntrada WHERE Entrada_IdEntrada IN (SELECT IdEntrada FROM Entrada WHERE Proveedor_IdProveedor = '$idProveedor')";

$result_check = mysqli_query($conn, $query_check);

// Si hay detalles de entrada asociados al proveedor, no se puede eliminar
if (mysqli_num_rows($result_check) > 0) {
    echo "No se puede eliminar el producto porque está asociado a movimientos en el inventario.";
} else {
    // Consulta SQL para eliminar el proveedor si no está asociado a ningún detalle de entrada
    $query_delete = "DELETE FROM Proveedor WHERE IdProveedor = '$idProveedor'";

    // Ejecutar la consulta de eliminación
    if (mysqli_query($conn, $query_delete)) {
        echo "Proveedor eliminado correctamente.";
    } else {
        echo "Error al eliminar el proveedor: " . mysqli_error($conn);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>