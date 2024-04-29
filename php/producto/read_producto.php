<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los productos con sus detalles
$query = "SELECT p.IdProducto, p.Nombre, p.Descripcion, p.Imagen, p.Precio, p.CantidadDisponible, p.Estado, s.Nombre AS Subcategoria, c.Nombre AS Categoria
          FROM Producto p
          INNER JOIN Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
          INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar los productos
$productos = array();

// Recorrer los resultados y convertir el campo de imagen a una cadena base64
while ($row = mysqli_fetch_assoc($result)) {
    $row['Imagen'] = base64_encode($row['Imagen']); // Codificar la imagen como base64
    $productos[] = $row;
}

// Devolver los productos en formato JSON
echo json_encode($productos);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>