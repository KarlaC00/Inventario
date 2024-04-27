<?php
// Datos de conexión
$servername = "localhost"; // Cambia según sea necesario
$username = "root"; // Cambia según sea necesario
$password = ""; // Cambia según sea necesario
$dbname = "gestorinventario"; // Cambia según sea necesario

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el IdEntrada
$entradaId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$entradaId) {
    echo json_encode(["error" => "ID de entrada no válido"]);
    exit();
}

// Verifica si se ha proporcionado un término de búsqueda
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Consulta SQL que busca solo detalles de compra asociados al `IdEntrada`
$query = "
    SELECT 
        d.IdDetalleEntrada,
        d.Producto_IdProducto,
        p.Nombre AS ProductoNombre,
        p.Descripcion AS ProductoDescripcion,
        p.Imagen,
        s.Nombre AS SubcategoriaNombre,
        c.Nombre AS CategoriaNombre,
        d.Cantidad,
        p.Precio,
        d.Cantidad * p.Precio AS PrecioTotal
    FROM DetalleEntrada d
    INNER JOIN Producto p ON d.Producto_IdProducto = p.IdProducto
    INNER JOIN Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
    INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria
    WHERE 
        d.Entrada_IdEntrada = ? 
        AND (p.Nombre LIKE ? OR p.Descripcion LIKE ? OR s.Nombre LIKE ? OR c.Nombre LIKE ?)
";

// Consulta preparada para evitar inyecciones SQL y con el filtro de `IdEntrada`
$stmt = $conn->prepare($query);
$searchPattern = '%' . $searchTerm . '%';
$stmt->bind_param('issss', $entradaId, $searchPattern, $searchPattern, $searchPattern, $searchPattern); // Vincular parámetros
$stmt->execute();

$result = $stmt->get_result();

// Recorrer resultados y almacenarlos en un array
$detalles = [];
while ($row = $result->fetch_assoc()) {
    // Convertir la imagen a base64 para la respuesta JSON
    $row['Imagen'] = base64_encode($row['Imagen']);
    $detalles[] = $row;
}

// Devolver resultados en formato JSON
echo json_encode($detalles);

// Cerrar la conexión
$stmt->close();
$conn->close();
?>