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

// Verifica si se ha enviado un término de búsqueda
if(isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Prepara la consulta SQL para buscar productos por múltiples campos
    $query = "SELECT p.*, s.Nombre AS Subcategoria, c.Nombre AS Categoria
        FROM Producto p
        INNER JOIN Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
        INNER JOIN Categoria c ON s.Categoria_IdCategoria = c.IdCategoria
        WHERE p.Nombre LIKE '%$searchTerm%'
        OR p.Descripcion LIKE '%$searchTerm%'
        OR p.Precio LIKE '%$searchTerm%'
        OR p.CantidadDisponible LIKE '%$searchTerm%'";


    $result = mysqli_query($conn, $query);

    if($result) {
        $productos = array();
        // Recorre los resultados y los almacena en un array
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
        // Devuelve los productos encontrados en formato JSON
        echo json_encode($productos);
    } else {
        // Si ocurre un error en la consulta
        echo json_encode(array('error' => 'Error al ejecutar la consulta: ' . mysqli_error($conn)));
    }
} else {
    // Si no se proporciona un término de búsqueda
    echo json_encode(array('error' => 'No se proporcionó un término de búsqueda'));
}

// Cierra la conexión
mysqli_close($conn);
?>