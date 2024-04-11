<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if (
    isset($_POST['IdProducto']) &&
    isset($_POST['nombre']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['imagen']) &&
    isset($_POST['precio']) &&
    isset($_POST['cantidad_disponible']) &&
    isset($_POST['subcategoria_id']) &&
    isset($_POST['estado'])
) {
    // Recibir datos del formulario de edición
    $idProducto = $_POST['IdProducto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];
    $precio = $_POST['precio'];
    $cantidadDisponible = $_POST['cantidad_disponible'];
    $subcategoriaId = $_POST['subcategoria_id'];
    $estado = $_POST['estado'];

    // Convertir el estado de cadena a entero (opcional)
    $estado = intval($estado);

    // Prepare SQL statement using a prepared statement
    $stmt = $conn->prepare("UPDATE Producto SET 
                                Nombre=?, 
                                Descripcion=?, 
                                Imagen=?, 
                                Precio=?, 
                                CantidadDisponible=?, 
                                Estado=?, 
                                Subcategoria_IdSubcategoria=?
                            WHERE IdProducto=?");

    // Bind parameters to the prepared statement
    $stmt->bind_param("sssiisi", $nombre, $descripcion, $imagen, $precio, $cantidadDisponible, $estado, $subcategoriaId, $idProducto);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Producto actualizado correctamente.";
    } else {
        echo "Error al actualizar el producto: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Error: Datos del formulario incompletos.";
}

// Close the database connection
$conn->close();

// Redireccionar a index.php después de procesar el formulario
header("Location: ../../pagina/producto/producto.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
