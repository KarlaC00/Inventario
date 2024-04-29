<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crea la conexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Comprueba la conexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir el ID del cliente a eliminar
$idCliente = $_POST['idCliente'];

// Consulta para verificar si existen detalles de salida asociados al cliente
$query_check = "SELECT COUNT(*) as total FROM DetalleSalida WHERE Salida_IdSalida IN (SELECT IdSalida FROM Salida WHERE Cliente_IdCliente = '$idCliente')";
$result_check = mysqli_query($conn, $query_check);
$row_check = mysqli_fetch_assoc($result_check);

// Si hay detalles de salida asociados, no permitir la eliminación y mostrar un mensaje
if ($row_check['total'] > 0) {
    echo "No se puede eliminar el producto porque está asociado a movimientos en el inventario.";
} else {
    // Query SQL para eliminar el cliente
    $query_delete = "DELETE FROM Cliente WHERE IdCliente = '$idCliente'";

    // Ejecutar la consulta
    if (mysqli_query($conn, $query_delete)) {
        echo "Cliente eliminado correctamente.";
    } else {
        echo "Error al eliminar el cliente: " . mysqli_error($conn);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>