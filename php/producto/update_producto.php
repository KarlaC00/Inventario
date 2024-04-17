<?php
// Verificar si se recibieron los datos del formulario
if (!isset($_POST['idProducto'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad_disponible'], $_POST['estado'], $_POST['subcategoria_id'], $_POST['categoria_id'])) {
    echo "Error: Se deben proporcionar todos los datos del producto.";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Recibir datos del formulario y evitar inyección de SQL
$idProducto = mysqli_real_escape_string($conn, $_POST['idProducto']);
$nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
$descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
$precio = floatval($_POST['precio']);
$cantidad_disponible = intval($_POST['cantidad_disponible']);
$estado = intval($_POST['estado']);
$subcategoria_id = intval($_POST['subcategoria_id']);
$categoria_id = intval($_POST['categoria_id']);

// Procesar imagen si se proporcionó
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen = $_FILES['imagen']['tmp_name'];
    $imagenData = addslashes(file_get_contents($imagen));
} else {
    $imagenData = null;
}

// Query SQL para actualizar el producto
$query = "UPDATE Producto SET 
            Nombre='$nombre', 
            Descripcion='$descripcion', 
            Precio=$precio, 
            CantidadDisponible=$cantidad_disponible, 
            Estado=$estado, 
            Subcategoria_IdSubcategoria=$subcategoria_id";

// Agregar la actualización de la imagen si se proporcionó
if ($imagenData !== null) {
    $query .= ", Imagen='$imagenData'";
}

$query .= " WHERE IdProducto=$idProducto";

// Ejecutar la consulta
if (mysqli_query($conn, $query)) {
    echo "Producto actualizado correctamente.";
} else {
    echo "Error al actualizar el producto: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/producto/producto.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>