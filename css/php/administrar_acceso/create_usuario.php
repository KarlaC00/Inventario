<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];
$numeroTelefonico = $_POST['telefono'];
$tipoIdentificacion = $_POST['tipo_identificacion'];
$numeroIdentificacion = $_POST['numero_identificacion'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$estado = 1; // Estado por defecto, puedes cambiarlo según tus necesidades
$nivelAcceso_IdnivelAcceso = $_POST['nivel_acceso'];

// Query SQL para insertar un nuevo usuario
$query = "INSERT INTO Usuario (Nombre, Correo, Direccion, numeroTelefonico, TipoIdentificacion, numeroIdentificacion, Usuario, Contrasena, Estado, nivelAcceso_IdnivelAcceso)
          VALUES ('$nombre', '$correo', '$direccion', '$numeroTelefonico', '$tipoIdentificacion', '$numeroIdentificacion', '$usuario', '$contrasena', '$estado', '$nivelAcceso_IdnivelAcceso')";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Usuario agregado correctamente.";
} else {
    echo "Error al agregar el usuario: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/administrar_acceso/usuario.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>