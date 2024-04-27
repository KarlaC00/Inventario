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

// Consulta SQL para buscar subcategorías por nombre
// Consulta SQL para buscar subcategorías por nombre
$query = "SELECT s.IdSubcategoria, s.Nombre AS SubcategoriaNombre, s.Estado, c.Nombre AS CategoriaNombre
          FROM Subcategoria s
          INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria
          WHERE s.Nombre LIKE '%$searchTerm%'";

$result = $conn->query($query);

$subcategorias = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subcategorias[] = $row;
    }
}
// Devolver los resultados como JSON
echo json_encode($subcategorias);


$conn->close();
?>

