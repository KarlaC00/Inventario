<?php
// editarcliente.php
// Este archivo manejará la solicitud para editar los datos de un cliente en la base de datos

// Conexión a la base de datos (reemplaza las credenciales con las tuyas)
$host = 'localhost';
$dbname = 'Gestor_Inventario';
$username = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos enviados en el cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    // Preparar la consulta SQL para actualizar los datos del cliente
    $sql = "UPDATE Cliente SET Nombre = :nombre, Direccion = :direccion, Correo = :correo, numeroTelefonico = :numeroTelefonico, 
            TipoIdentificacion = :tipoIdentificacion, numeroIdentificacion = :numeroIdentificacion, Estado = :estado WHERE IdCliente = :idCliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $data->nombre, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $data->direccion, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $data->correo, PDO::PARAM_STR);
    $stmt->bindParam(':numeroTelefonico', $data->numeroTelefonico, PDO::PARAM_INT);
    $stmt->bindParam(':tipoIdentificacion', $data->tipoIdentificacion, PDO::PARAM_STR);
    $stmt->bindParam(':numeroIdentificacion', $data->numeroIdentificacion, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $data->estado, PDO::PARAM_INT);
    $stmt->bindParam(':idCliente', $data->IdCliente, PDO::PARAM_INT);
    $stmt->execute();

    // Devolver una respuesta exitosa al cliente (código 200)
    http_response_code(200);
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error al cliente con código 500 (Error interno del servidor)
    http_response_code(500);
    echo json_encode(array("message" => "Error al actualizar los datos del cliente: " . $e->getMessage()));
}
?>
