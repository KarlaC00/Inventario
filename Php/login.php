<?php
// Verificar si se recibieron datos del formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo y la contraseña enviados desde el formulario
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Conectar a la base de datos (reemplaza las credenciales con las tuyas)
    $host = 'localhost';
    $dbname = 'gestor_inventario';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Consulta SQL para verificar si existe un usuario con el correo y la contraseña proporcionados
        $sql = "SELECT * FROM Usuario WHERE Correo = :correo AND Contrasena = :contrasena AND Estado = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Si el usuario existe y está activo, iniciar sesión y redirigir al gestor de inventario
            session_start();
            $_SESSION["user_id"] = $usuario["IdUsuario"];
            header("Location: gestion_inventario.php");
            exit();
        } else {
            // Si el usuario no existe o no está activo, mostrar un mensaje de error
            echo "Correo o contraseña incorrectos.";
        }
    } catch(PDOException $e) {
        // En caso de error, mostrar el mensaje de error
        echo "Error: " . $e->getMessage();
    }
}
?>