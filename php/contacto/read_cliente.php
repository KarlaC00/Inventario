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

// Consulta SQL para obtener todos los clientes
$query = "SELECT * FROM Cliente";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar los clientes
$clientes = array();

// Recorrer los resultados y añadirlos al array
while ($row = mysqli_fetch_assoc($result)) {
    $clientes[] = $row;
}

// Devolver los clientes en formato JSON
echo json_encode($clientes);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>