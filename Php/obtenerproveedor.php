<?php
// obtenerproveedor.php
// Este archivo manejará la solicitud para obtener los datos de un proveedor específico de la base de datos

// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del proveedor de la URL
    $providerId = $_GET['id'];

    // Consulta SQL para obtener los datos del proveedor con el ID proporcionado
    $sql = "SELECT * FROM Proveedor WHERE IdProveedor = :providerId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':providerId', $providerId, PDO::PARAM_INT);
    $stmt->execute();
    $providerData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos del proveedor en formato JSON
    echo json_encode($providerData);
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al obtener los datos del proveedor: " . $e->getMessage()));
}
?>