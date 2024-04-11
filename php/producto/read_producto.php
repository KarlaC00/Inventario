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

$query = "SELECT p.IdProducto, p.Nombre, p.Descripcion, p.Imagen, p.Precio, p.CantidadDisponible, p.Estado, s.Nombre AS Subcategoria, c.Nombre AS Categoria
          FROM Producto p
          INNER JOIN Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
          INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria";

$result = mysqli_query($conn, $query);

$productos = array();

while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = $row;
}

echo json_encode($productos);

mysqli_close($conn);
?>
