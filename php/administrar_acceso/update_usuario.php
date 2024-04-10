<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
//$conectar    = mysqli_connect($servidor, $usuario, $clave, $datos);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir datos del formulario de edición
$idUsuario = $_POST['idUsuario'];
$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$direccion = $_POST['direccion'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$tipoIdentificacion = $_POST['tipo_identificacion'];
$numeroIdentificacion = $_POST['numero_identificacion'];
$nivelAccesoId = $_POST['nivel_acceso'];
$estado = $_POST['estado']; // Asegúrate de que este valor se esté pasando correctamente desde el formulario

// Convertir el estado de cadena a entero (opcional)
$estado = intval($estado);

// Query SQL para actualizar la información del usuario
$query = "UPDATE Usuario SET 
            Nombre='$nombre', 
            Usuario='$usuario', 
            Contrasena='$contrasena', 
            Direccion='$direccion', 
            Correo='$correo', 
            numeroTelefonico='$telefono', 
            TipoIdentificacion='$tipoIdentificacion', 
            numeroIdentificacion='$numeroIdentificacion', 
            nivelAcceso_IdnivelAcceso='$nivelAccesoId', 
            Estado=$estado
          WHERE IdUsuario='$idUsuario'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "Error al actualizar el usuario: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/administrar_acceso/usuario.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>