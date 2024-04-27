<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT IdCategoria, Nombre, Estado FROM Categoria";

$result = $conn->query($query);

$categorias = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Codificar el resultado como JSON y enviarlo al cliente
header('Content-Type: application/json');
echo json_encode($categorias);

$conn->close();
?>
