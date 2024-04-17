<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Recibir datos del formulario
$idSubcategoria = $_POST['idSubcategoria'];
$nombre = $_POST['nombre'];
$estado = $_POST['estado'];
$categoriaId = $_POST['categoria']; // Obtener el ID de la categoría seleccionada

// Convertir el estado a entero (necesario para almacenar en BIT(1))
$estado = intval($estado);

// Consulta SQL para actualizar la subcategoría, incluyendo el campo de categoría
$query = "UPDATE Subcategoria 
          SET Nombre='$nombre', Estado=$estado, Categoria_IdCategoria=$categoriaId
          WHERE IdSubcategoria='$idSubcategoria'";

// Ejecutar la consulta de actualización
if (mysqli_query($conn, $query)) {
    // Consulta para obtener el nombre de la categoría asociada a la subcategoría
    $categoriaQuery = "SELECT c.Nombre AS CategoriaNombre
                       FROM Categoria c
                       JOIN Subcategoria s ON c.IdCategoria = s.Categoria_IdCategoria
                       WHERE s.IdSubcategoria='$idSubcategoria'";

    $result = mysqli_query($conn, $categoriaQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $categoriaNombre = $row['CategoriaNombre'];
        echo "Subcategoría actualizada correctamente. Categoría: $categoriaNombre";
    } else {
        echo "Subcategoría actualizada correctamente.";
    }
} else {
    echo "Error al actualizar la subcategoría: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Redireccionar a una página después de procesar el formulario (por ejemplo, a la lista de subcategorías)
header("Location: ../../pagina/producto/subcategoria.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
