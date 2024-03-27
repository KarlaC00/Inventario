<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo y la contraseña enviados desde el formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Conectar a la base de datos (reemplaza las credenciales con las tuyas)
    $host = 'localhost';
    $dbname = 'gestor_inventario';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Consulta SQL para verificar si existe un usuario con el correo y la contraseña proporcionados
        $sql = "SELECT * FROM Usuario WHERE Correo = :email AND Contrasena = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró un usuario con las credenciales proporcionadas
        if ($user) {
            // Iniciar sesión y redirigir al usuario a otra página
            $_SESSION['user_id'] = $user['id']; // Puedes almacenar cualquier dato del usuario que necesites
            header("Location: .././Html/index.html"); // Reemplaza 'inicio.php' con la página a la que deseas redirigir al usuario
            exit();
        } else {
            // Credenciales incorrectas, muestra un mensaje de error
            echo "Correo o contraseña incorrectos";
        }
    } catch(PDOException $e) {
        // En caso de error en la conexión o en la consulta SQL
        echo "Error: " . $e->getMessage();
    }

    // Cierra la conexión a la base de datos
    $pdo = null;
}
?>
