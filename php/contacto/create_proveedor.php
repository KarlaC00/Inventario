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
$estado = 1; // Estado por defecto, puedes cambiarlo según tus necesidades

// Query SQL para insertar un nuevo proveedor
$query = "INSERT INTO Proveedor (Nombre, Correo, Direccion, numeroTelefonico, TipoIdentificacion, numeroIdentificacion, Estado)
          VALUES ('$nombre', '$correo', '$direccion', '$numeroTelefonico', '$tipoIdentificacion', '$numeroIdentificacion', '$estado')";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Proveedor agregado correctamente.";
} else {
    echo "Error al agregar el proveedor: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/contacto/proveedor.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>