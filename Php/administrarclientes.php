<?php
// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se recibió una solicitud de eliminación de cliente
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        // Obtener los datos enviados en el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->clientId)) {
            // Obtener el ID del cliente a eliminar
            $clientId = $data->clientId;

            // Realizar la eliminación del cliente con el ID proporcionado
            $sql = "DELETE FROM Cliente WHERE IdCliente = :clientId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmt->execute();

            // Devolver una respuesta exitosa al cliente (código 200)
            http_response_code(200);
        } else {
            // Si no se proporciona el ID del cliente, devolver un mensaje de error al cliente con código 400 (Solicitud incorrecta)
            http_response_code(400);
            echo json_encode(array("message" => "ID de cliente no proporcionado."));
        }
    } else {
        // Consulta SQL para obtener los clientes
        $sql = "SELECT * FROM Cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los clientes en formato JSON
        echo json_encode($clients);
    }
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al procesar la solicitud: " . $e->getMessage()));
}
?>