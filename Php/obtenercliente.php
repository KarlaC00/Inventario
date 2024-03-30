<?php
// obtenercliente.php
// Este archivo manejará la solicitud para obtener los datos de un cliente específico de la base de datos

// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del cliente de la URL
    $clientId = $_GET['id'];

    // Consulta SQL para obtener los datos del cliente con el ID proporcionado
    $sql = "SELECT * FROM Cliente WHERE IdCliente = :clientId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos del cliente en formato JSON
    echo json_encode($clientData);
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al obtener los datos del cliente: " . $e->getMessage()));
}
?>
