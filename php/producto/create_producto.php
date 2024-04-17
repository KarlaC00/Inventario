<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$imagen = $_FILES['imagen']['tmp_name']; // Get temporary location of the uploaded file
$precio = $_POST['precio'];
$cantidadDisponible = $_POST['cantidad_disponible'];
$estado = 1; // Estado por defecto, puedes cambiarlo según tus necesidades
$subcategoria_IdSubcategoria = $_POST['subcategoria_id'];

// Conectar a la base de datos
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Procesar imagen
if ($imagen) {
    // Read the file content
    $imagenData = addslashes(file_get_contents($imagen));
} else {
    // No image uploaded, set default image or handle accordingly
    $imagenData = null;
}

// Query SQL para insertar un nuevo producto
$query = "INSERT INTO Producto (Nombre, Descripcion, Imagen, Precio, CantidadDisponible, Estado, Subcategoria_IdSubcategoria)
          VALUES ('$nombre', '$descripcion', '$imagenData', '$precio', '$cantidadDisponible', '$estado', '$subcategoria_IdSubcategoria')";

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