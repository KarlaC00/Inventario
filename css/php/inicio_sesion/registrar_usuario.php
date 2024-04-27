<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipo_identificacion = $_POST['tipo_identificacion'];
    $numero_identificacion = $_POST['numero_identificacion'];
    $nivel_acceso = $_POST['nivel_acceso'];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "gestorinventario");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO Usuario (Nombre, Usuario, Contrasena, Direccion, Correo, numeroTelefonico, TipoIdentificacion, numeroIdentificacion, nivelAcceso_IdnivelAcceso, Estado) 
            VALUES ('$nombre', '$usuario', '$contrasena', '$direccion', '$correo', '$telefono', '$tipo_identificacion', '$numero_identificacion', '$nivel_acceso', 1)";

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        // Redireccionar al usuario a la página de inicio de sesión
        header("Location: ../../index.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>