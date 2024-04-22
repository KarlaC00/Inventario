<?php
// Verificar si se recibió el ID de la categoría a eliminar
if (isset($_POST['idCategoria'])) {
    // Obtener y sanear el ID de la categoría
    $idCategoria = intval($_POST['idCategoria']); // Convertir a entero para mayor seguridad

    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestorinventario";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verificar si la categoría tiene subcategorías
    $query_count_subcategories = "SELECT COUNT(*) AS numSubcategorias FROM Subcategoria WHERE Categoria_IdCategoria = ? AND Estado = 1";
    $stmt_count = $conn->prepare($query_count_subcategories);
    $stmt_count->bind_param("i", $idCategoria);
    $stmt_count->execute();
    $stmt_count->bind_result($numSubcategorias);
    $stmt_count->fetch();
    $stmt_count->close();

    // Verificar si se puede eliminar la categoría
    if ($numSubcategorias > 0) {
        echo "No se puede eliminar la categoría porque tiene subcategorías asociadas.";
    } else {
        // Preparar la consulta SQL para eliminar la categoría
        $query_delete = "DELETE FROM Categoria WHERE IdCategoria = ?";
        $stmt_delete = $conn->prepare($query_delete);
        $stmt_delete->bind_param("i", $idCategoria);

        // Ejecutar la consulta para eliminar la categoría
        if ($stmt_delete->execute()) {
            echo "Categoría eliminada correctamente.";
        } else {
            echo "Error al eliminar la categoría: " . $stmt_delete->error;
        }

        // Cerrar el statement de eliminación
        $stmt_delete->close();
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "ID de categoría no recibido.";
}
?>
