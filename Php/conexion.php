<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Conexion</title>
</head>
<body>
<?php
// Credenciales de la base de datos
$host = 'localhost'; 
$dbname = 'gestor_inventario';
$username = 'root';
$password = '';

try {
    // Establecer la conexión usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Configurar el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opcional: configurar el conjunto de caracteres a UTF-8
    $pdo->exec("set names utf8");
    
    echo "Conexión exitosa";
} catch(PDOException $e) {
    // En caso de error, mostrar el mensaje de error
    echo "Error de conexión: " . $e->getMessage();
}
?>
</body>
</html>

