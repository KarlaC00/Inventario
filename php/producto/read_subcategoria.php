<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT s.IdSubcategoria, s.Nombre AS SubcategoriaNombre, s.Estado, c.Nombre AS CategoriaNombre
          FROM Subcategoria s
          INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria";

$result = $conn->query($query);

$subcategorias = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subcategorias[] = $row;
    }
}

// Codificar el resultado como JSON y enviarlo al cliente
header('Content-Type: application/json');
echo json_encode($subcategorias);

$conn->close();
?>
