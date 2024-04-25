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

// Recibir el ID del producto a eliminar
$idProducto = $_POST['idProducto'];

// Query SQL para eliminar el producto
$query = "DELETE FROM Producto WHERE IdProducto = '$idProducto'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Producto eliminado correctamente.";
} else {
    echo "Error al eliminar el producto: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>