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

$searchTerm = $_GET['search']; // Obtener el término de búsqueda desde la URL

// Consulta SQL para buscar categorías por nombre
$query = "SELECT IdCategoria, Nombre, Estado FROM Categoria WHERE Nombre LIKE '%$searchTerm%'";

$result = $conn->query($query);

$categorias = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

echo json_encode($categorias);

$conn->close();
?>
