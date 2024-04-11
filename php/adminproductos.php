<?php
// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se recibió una solicitud de eliminación de producto
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        // Obtener los datos enviados en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->productId)) {
            // Obtener el ID del producto a eliminar
            $productId = $data->productId;

            // Realizar la eliminación del producto con el ID proporcionado
            $sql = "DELETE FROM Producto WHERE IdProducto = :productId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            // Devolver una respuesta exitosa al cliente (código 200)
            http_response_code(200);
        } else {
            // Si no se proporciona el ID del producto, devolver un mensaje de error al cliente con código 400 (Solicitud incorrecta)
            http_response_code(400);
            echo json_encode(array("message" => "ID de producto no proporcionado."));
        }
    } else {
        // Consulta SQL para obtener los productos
        $sql = "SELECT * FROM Producto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los productos en formato JSON
        echo json_encode($products);
    }
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al procesar la solicitud: " . $e->getMessage()));
}
?>
