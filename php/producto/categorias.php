<?php
// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Consulta SQL para obtener las categorías
        $sql = "SELECT * FROM Categoria";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver las categorías en formato JSON
        echo json_encode($categorias);
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar si se está creando una categoría o una subcategoría
        if (isset($_POST['nombreCategoria'])) {
            // Crear una nueva categoría
            $nombreCategoria = $_POST['nombreCategoria'];

            // Insertar la categoría en la tabla Categoria
            $sql = "INSERT INTO Categoria (Nombre, Estado) VALUES (:nombreCategoria, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombreCategoria', $nombreCategoria, PDO::PARAM_STR);
            $stmt->execute();

            // Devolver una respuesta exitosa al cliente (código 200)
            http_response_code(200);
        } else if (isset($_POST['nombreSubcategoria']) && isset($_POST['categoriaPadre'])) {
            // Crear una nueva subcategoría
            $nombreSubcategoria = $_POST['nombreSubcategoria'];
            $categoriaPadre = $_POST['categoriaPadre'];

            // Insertar la subcategoría en la tabla Subcategoria
            $sql = "INSERT INTO Subcategoria (Nombre, Estado, Categoria_IdCategoria) VALUES (:nombreSubcategoria, 1, :categoriaPadre)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombreSubcategoria', $nombreSubcategoria, PDO::PARAM_STR);
            $stmt->bindParam(':categoriaPadre', $categoriaPadre, PDO::PARAM_INT);
            $stmt->execute();

            // Devolver una respuesta exitosa al cliente (código 200)
            http_response_code(200);
        } else {
            // Si no se reciben datos válidos, devolver un mensaje de error al cliente con código 400 (Solicitud incorrecta)
            http_response_code(400);
            echo json_encode(array("message" => "Datos no proporcionados o incorrectos."));
        }
    }
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al procesar la solicitud: " . $e->getMessage()));
}
?>
