<?php
// Verificar si se recibieron datos del formulario de agregar cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados desde el formulario
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $numeroTelefonico = $_POST["numeroTelefonico"];
    $tipoIdentificacion = $_POST["tipoIdentificacion"];
    $numeroIdentificacion = $_POST["numeroIdentificacion"];

    // Conectar a la base de datos (reemplaza las credenciales con las tuyas)
    $host = 'localhost';
    $dbname = 'Gestor_Inventario';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insertar los datos del nuevo cliente en la base de datos
        $sql = "INSERT INTO Cliente (Nombre, Direccion, Correo, numeroTelefonico, TipoIdentificacion, numeroIdentificacion, Estado) VALUES (:nombre, :direccion, :correo, :numeroTelefonico, :tipoIdentificacion, :numeroIdentificacion, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':numeroTelefonico', $numeroTelefonico);
        $stmt->bindParam(':tipoIdentificacion', $tipoIdentificacion);
        $stmt->bindParam(':numeroIdentificacion', $numeroIdentificacion);
        $stmt->execute();

        // Redirigir al usuario
        header("Location: ../Html/administrarclientes.html");
        exit();
    } catch(PDOException $e) {
        // En caso de error, mostrar el mensaje de error
        echo "Error: " . $e->getMessage();
    }
}
?>
