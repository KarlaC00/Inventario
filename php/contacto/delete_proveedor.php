<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir el ID del proveedor a eliminar
$idProveedor = $_POST['idProveedor'];

// Query SQL para eliminar el proveedor
$query = "DELETE FROM Proveedor WHERE IdProveedor = '$idProveedor'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Proveedor eliminado correctamente.";
} else {
    echo "Error al eliminar el proveedor: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>