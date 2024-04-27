<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirigir a la página de inicio de sesión si no está logueado
    header("Location: ../../index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir el ID del usuario a eliminar
$idUsuario = $_POST['idUsuario'];

// Verificar si el usuario intenta eliminar su propio usuario
if (intval($idUsuario) === intval($_SESSION['loggedin'])) {
    // Si el usuario intenta eliminar su propio usuario, mostrar un mensaje de error
    echo "No se puede eliminar el usuario que inició sesión.";
    exit();
}

// Consulta SQL para eliminar el usuario
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