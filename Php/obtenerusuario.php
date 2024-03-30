<?php
// obtenerusuario.php
// Este archivo manejará la solicitud para obtener los datos de un usuario específico de la base de datos

// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID del usuario de la URL
    $userId = $_GET['id'];

    // Consulta SQL para obtener los datos del usuario con el ID proporcionado
    $sql = "SELECT * FROM Usuario WHERE IdUsuario = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos del usuario en formato JSON
    echo json_encode($userData);
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al obtener los datos del usuario: " . $e->getMessage()));
}
?>
