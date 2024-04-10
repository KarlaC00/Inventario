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

// Recibir el ID del cliente a eliminar
$idCliente = $_POST['idCliente'];

// Query SQL para eliminar el cliente
$query = "DELETE FROM Cliente WHERE IdCliente = '$idCliente'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Cliente eliminado correctamente.";
} else {
    echo "Error al eliminar el cliente: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>