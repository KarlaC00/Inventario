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

// Consulta SQL para obtener todos los proveedores
$query = "SELECT * FROM Proveedor";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar los proveedores
$proveedores = array();

// Recorrer los resultados y añadirlos al array
while ($row = mysqli_fetch_assoc($result)) {
    $proveedores[] = $row;
}

// Devolver los proveedores en formato JSON
echo json_encode($proveedores);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>