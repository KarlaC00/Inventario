<?php
// Verificar si se recibieron datos del formulario de agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados desde el formulario
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $numeroTelefonico = $_POST["numeroTelefonico"];
    $tipoIdentificacion = $_POST["tipoIdentificacion"];
    $numeroIdentificacion = $_POST["numeroIdentificacion"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $nivelAccesoId = $_POST["nivelAcceso"]; // Obtener el ID del nivel de acceso seleccionado

    // Conectar a la base de datos (reemplaza las credenciales con las tuyas)
    $host = 'localhost';
    $dbname = 'gestor_inventario';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insertar los datos del nuevo usuario en la base de datos
        $sql = "INSERT INTO Usuario (Nombre, Direccion, Correo, numeroTelefonico, TipoIdentificacion, numeroIdentificacion, Usuario, Contrasena, Estado, nivelAcceso_IdnivelAcceso) VALUES (:nombre, :direccion, :correo, :numeroTelefonico, :tipoIdentificacion, :numeroIdentificacion, :usuario, :contrasena, 1, :nivelAccesoId)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':numeroTelefonico', $numeroTelefonico);
        $stmt->bindParam(':tipoIdentificacion', $tipoIdentificacion);
        $stmt->bindParam(':numeroIdentificacion', $numeroIdentificacion);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':nivelAccesoId', $nivelAccesoId); // Asignar el ID del nivel de acceso
        $stmt->execute();

        // Redirigir al usuario
        header("Location: ../Html/administraraccesos.html");
        exit();
    } catch(PDOException $e) {
        // En caso de error, mostrar el mensaje de error
        echo "Error: " . $e->getMessage();
    }
}
?>