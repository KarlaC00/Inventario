<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Obtener el ID de la categoría seleccionada desde la solicitud GET
$categoriaId = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Consultar las subcategorías correspondientes a la categoría seleccionada
$query = "SELECT * FROM Subcategoria WHERE Categoria_IdCategoria = $categoriaId";
$result = mysqli_query($conn, $query);
$subcategorias = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Devolver las subcategorías como respuesta en formato JSON
echo json_encode($subcategorias);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>