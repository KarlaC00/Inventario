<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Recibir datos del formulario
$idCategoria = $_POST['idCategoria'];
$nombre = $_POST['nombre'];
$estado = $_POST['estado']; // This should be '0' or '1'

// Convertir el estado a entero (optional, but needed for BIT(1) storage)
$estado = intval($estado);

// Query SQL para actualizar la categoría
$query = "UPDATE Categoria 
          SET Nombre='$nombre', Estado=$estado
          WHERE IdCategoria='$idCategoria'";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Categoría actualizada correctamente.";
} else {
    echo "Error al actualizar la categoría: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a una página después de procesar el formulario (por ejemplo, a la lista de categorías)
header("Location: ../../pagina/producto/categoria.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
