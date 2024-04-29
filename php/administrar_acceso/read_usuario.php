<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crea la conexion
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Comprueba la conexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener todos los usuarios
$query = "SELECT u.IdUsuario, u.Nombre, u.Direccion, u.Correo, u.numeroTelefonico, u.TipoIdentificacion, u.numeroIdentificacion, u.Usuario, u.Contrasena, u.Estado, n.Nombre AS NivelAcceso
          FROM Usuario u
          INNER JOIN nivelAcceso n ON u.nivelAcceso_IdnivelAcceso = n.IdnivelAcceso";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar los usuarios
$users = array();

// Recorrer los resultados y añadirlos al array
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Devolver los usuarios en formato JSON
echo json_encode($users);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>