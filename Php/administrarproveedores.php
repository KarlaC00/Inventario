<?php
// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se recibió una solicitud de eliminación de proveedor
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        // Obtener los datos enviados en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->providerId)) {
            // Obtener el ID del proveedor a eliminar
            $providerId = $data->providerId;

            // Realizar la eliminación del proveedor con el ID proporcionado
            $sql = "DELETE FROM Proveedor WHERE IdProveedor = :providerId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':providerId', $providerId, PDO::PARAM_INT);
            $stmt->execute();

            // Devolver una respuesta exitosa al cliente (código 200)
            http_response_code(200);
        } else {
            // Si no se proporciona el ID del proveedor, devolver un mensaje de error al cliente con código 400 (Solicitud incorrecta)
            http_response_code(400);
            echo json_encode(array("message" => "ID de proveedor no proporcionado."));
        }
    } else {
        // Consulta SQL para obtener los proveedores
        $sql = "SELECT * FROM Proveedor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los proveedores en formato JSON
        echo json_encode($providers);
    }
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al procesar la solicitud: " . $e->getMessage()));
}
?>