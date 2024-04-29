<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verifica si se ha proporcionado un término de búsqueda
$searchTerm = isset($_GET['search']) ? trim((string) $_GET['search']) : null;

// Asegúrate de que el término de búsqueda no sea nulo
if ($searchTerm !== null) {
    // Prepara la consulta SQL para buscar productos por múltiples campos
    $query = "
        SELECT 
            p.*, 
            s.Nombre AS Subcategoria, 
            c.Nombre AS Categoria
        FROM 
            Producto p
        INNER JOIN 
            Subcategoria s ON p.Subcategoria_IdSubcategoria = s.IdSubcategoria
        INNER JOIN 
            Categoria c ON s.Categoria_IdCategoria = c.IdCategoria
        WHERE 
            p.Nombre LIKE ?
            OR p.Descripcion LIKE ?
            OR p.Precio LIKE ?
            OR p.CantidadDisponible LIKE ?
            OR s.Nombre LIKE ?
            OR c.Nombre LIKE ?
    ";

    // Prepara la consulta
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Configurar el patrón de búsqueda
        $searchPattern = '%' . $searchTerm . '%';

        // Vincular parámetros
        $stmt->bind_param('ssssss', 
            $searchPattern, 
            $searchPattern, 
            $searchPattern,
            $searchPattern, 
            $searchPattern, 
            $searchPattern
        );

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();

        if ($result) {
            $productos = array();
            // Recorre los resultados y almacena en un array
            while ($row = mysqli_fetch_assoc($result)) {
                $row['Imagen'] = base64_encode($row['Imagen']); // Codificar la imagen como base64
                $productos[] = $row;
            }

            // Devuelve los productos encontrados en formato JSON
            echo json_encode($productos);
        } else {
            // Si ocurre un error en la consulta
            echo json_encode(array('error' => 'Error al obtener resultados'));
        }

        // Cierra la declaración
        $stmt->close();
    } else {
        // Si la preparación de la consulta falla
        echo json_encode(array('error' => 'Error al preparar la consulta'));
    }
} else {
    // Si no se proporciona un término de búsqueda
    echo json_encode(array('error' => 'No se proporcionó un término de búsqueda'));
}

// Cierra la conexión
$conn->close();
?>