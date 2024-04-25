<?php
session_start();

// Verificar si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar credenciales (aquí deberías agregar la lógica para verificar en tu base de datos)
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "gestorinventario");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para verificar las credenciales y obtener el nivel de acceso y el estado del usuario
    $sql = "SELECT * FROM Usuario WHERE Usuario = '$usuario' AND Correo = '$correo' AND Contrasena = '$contrasena'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['Estado'] == 1) {
            // Las credenciales son válidas y el usuario está activo
            $_SESSION['loggedin'] = true;
            $_SESSION['usuario'] = $usuario;
            $_SESSION['nivelAcceso_IdnivelAcceso'] = $row['nivelAcceso_IdnivelAcceso']; // Almacenar el nivel de acceso en la sesión
            header("Location: ../../Pagina/inicio/inicio.php");
            exit();
        } else {
            // El usuario está inactivo
            echo '<script>alert("El usuario está inactivo."); window.location.href = "../../index.php";</script>';
        }
    } else {
        // Si las credenciales son incorrectas, mostrar un mensaje de error
        echo '<script>alert("Credenciales incorrectas. Por favor, inténtalo de nuevo."); window.location.href = "../../index.php";</script>';
    }

    $conexion->close();
}
?>