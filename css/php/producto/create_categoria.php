<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Recibir datos del formulario
$nombre = $_POST['nombre'];

// Definir estado por defecto
$estado = isset($_POST['estado']) ? $_POST['estado'] : 1;

// Conectar a la base de datos
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Preparar consulta SQL para insertar la categoría
$query = "INSERT INTO Categoria (Nombre, Estado) VALUES ('$nombre', '$estado')";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Categoría actualizada correctamente.";
} else {
    echo "Error al actualizar la categoría: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a la página de gestión de categorías después de procesar el formulario
header("Location: ../../pagina/producto/categoria.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
