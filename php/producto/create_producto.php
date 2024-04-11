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
$descripcion = $_POST['descripcion'];
$imagen = $_POST['imagen'];
$precio = $_POST['precio'];
$cantidadDisponible = $_POST['cantidad_disponible'];
$estado = 1; // Estado por defecto, puedes cambiarlo según tus necesidades
$subcategoria_IdSubcategoria = $_POST['subcategoria_id'];

// Query SQL para insertar un nuevo producto
$query = "INSERT INTO Producto (Nombre, Descripcion, Imagen, Precio, CantidadDisponible, Estado, Subcategoria_IdSubcategoria)
          VALUES ('$nombre', '$descripcion', '$imagen', '$precio', '$cantidadDisponible', '$estado', '$subcategoria_IdSubcategoria')";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Producto agregado correctamente.";
} else {
    echo "Error al agregar el producto: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/producto/producto.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>