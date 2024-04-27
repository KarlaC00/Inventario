<?php
// Verificar si se recibió el ID de la subcategoría a eliminar
if (isset($_POST['idSubcategoria'])) {
    // Obtener y sanear el ID de la subcategoría
    $idSubcategoria = intval($_POST['idSubcategoria']); // Convertir a entero para mayor seguridad

    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestorinventario";

    // Crear conexión usando objetos mysqli (más seguro)
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verificar si hay productos asociados a esta subcategoría
    $query_count_products = "SELECT COUNT(*) AS numProductos FROM Producto WHERE Subcategoria_IdSubcategoria = ?";
    $stmt_count = $conn->prepare($query_count_products);
    $stmt_count->bind_param("i", $idSubcategoria);
    $stmt_count->execute();
    $stmt_count->bind_result($numProductos);
    $stmt_count->fetch();
    $stmt_count->close();

    // Verificar si se puede eliminar la subcategoría
    if ($numProductos > 0) {
        echo "No se puede eliminar la subcategoría porque tiene productos asociados.";
    } else {
        // Preparar la consulta SQL para eliminar la subcategoría
        $query_delete = "DELETE FROM Subcategoria WHERE IdSubcategoria = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param("i", $idSubcategoria);

        // Ejecutar la consulta para eliminar la subcategoría
        if ($stmt_delete->execute()) {
            echo "Subcategoría eliminada correctamente.";
        } else {
            echo "Error al eliminar la subcategoría: " . $stmt_delete->error;
        }

        // Cerrar el statement de eliminación
        $stmt_delete->close();
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "ID de subcategoría no recibido.";
}
?>
