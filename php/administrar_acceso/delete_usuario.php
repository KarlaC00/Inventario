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

// Recibir el ID del usuario a eliminar
$idUsuario = $_POST['idUsuario'];

// Query SQL para eliminar el usuario
$query = "DELETE FROM Usuario WHERE IdUsuario = '$idUsuario'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Usuario eliminado correctamente.";
} else {
    echo "Error al eliminar el usuario: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>