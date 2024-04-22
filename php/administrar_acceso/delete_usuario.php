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

// Consulta SQL para eliminar el usuario, asegurándonos de que no sea el usuario que inició sesión
$query = "DELETE FROM Usuario WHERE IdUsuario = '$idUsuario' AND IdUsuario != '" . $_SESSION['loggedin'] . "'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "No se puede eliminar el usuario que inició sesión.";
    }
} else {
    echo "Error al eliminar el usuario: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>