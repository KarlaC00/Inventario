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

// Recibir datos del formulario de edición
$idProveedor = $_POST['idProveedor'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$tipoIdentificacion = $_POST['tipo_identificacion'];
$numeroIdentificacion = $_POST['numero_identificacion'];
$estado = $_POST['estado']; // Asegúrate de que este valor se esté pasando correctamente desde el formulario

// Convertir el estado de cadena a entero (opcional)
$estado = intval($estado);

// Query SQL para actualizar la información del proveedor
$query = "UPDATE Proveedor SET 
            Nombre='$nombre', 
            Direccion='$direccion', 
            Correo='$correo', 
            numeroTelefonico='$telefono', 
            TipoIdentificacion='$tipoIdentificacion', 
            numeroIdentificacion='$numeroIdentificacion', 
            Estado=$estado
          WHERE IdProveedor='$idProveedor'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Proveedor actualizado correctamente.";
} else {
    echo "Error al actualizar el proveedor: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/contacto/proveedor.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>