<?php
session_start(); // Iniciar sesión para obtener el ID del usuario logueado

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirigir a la página de inicio de sesión si no está logueado
    header("Location: ../../index.php");
    exit();
}

// Obtener el id del usuario desde la sesión
$IdSesion = $_SESSION['IdUsuario'];

// Recibir el ID del usuario a eliminar
$IdUsuario = $_POST['idUsuario'];

// Verificar si el ID a eliminar es el mismo que el del usuario en sesión
if ($IdSesion === $IdUsuario) {
    // No se puede eliminar el usuario si es el mismo que está logueado
    echo "No se puede eliminar el usuario que esta actualmente en sesión.";
    mysqli_close($conn);
    exit();
}

// Verificar si el usuario tiene alguna salida o entrada asociada
$query_check_entrada = "SELECT * FROM Entrada WHERE Usuario_IdUsuario = '$IdUsuario'";
$result_check_entrada = mysqli_query($conn, $query_check_entrada);

$query_check_salida = "SELECT * FROM Salida WHERE Usuario_IdUsuario = '$IdUsuario'";
$result_check_salida = mysqli_query($conn, $query_check_salida);

if (mysqli_num_rows($result_check_entrada) > 0 || mysqli_num_rows($result_check_salida) > 0) {
    // Si el usuario tiene entradas o salidas asociadas, mostrar un mensaje de error
    echo "No se puede eliminar el usuario porque está asociado a movimientos en el inventario.";
} else {
    // Si no tiene dependencias, proceder a eliminar el usuario
    $query_delete = "DELETE FROM Usuario WHERE IdUsuario = '$IdUsuario'";

    if (mysqli_query($conn, $query_delete)) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar el usuario: " . mysqli_error($conn);
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>