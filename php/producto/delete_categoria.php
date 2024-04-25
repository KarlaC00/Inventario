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
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Verificar si la categoría tiene subcategorías
    $query_count_subcategories = "SELECT COUNT(*) AS numSubcategorias FROM Categoria WHERE IdCategoria <> ? AND Estado = 1";
    $stmt_count = mysqli_prepare($conn, $query_count_subcategories);
    mysqli_stmt_bind_param($stmt_count, "i", $idCategoria);
    mysqli_stmt_execute($stmt_count);
    mysqli_stmt_bind_result($stmt_count, $numSubcategorias);
    mysqli_stmt_fetch($stmt_count);
    mysqli_stmt_close($stmt_count);

    // Verificar si se puede eliminar la categoría
    if ($numSubcategorias == 0) {
        echo "No se puede eliminar la categoría porque tiene subcategorías asociadas.";
    } else {
        // Preparar la consulta SQL para eliminar la categoría
        $query_delete = "DELETE FROM Categoria WHERE IdCategoria = ?";
        $stmt_delete = mysqli_prepare($conn, $query_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $idCategoria);

        // Ejecutar la consulta para eliminar la categoría
        if (mysqli_stmt_execute($stmt_delete)) {
            echo "Categoría eliminada correctamente.";
        } else {
            echo "Error al eliminar la categoría: " . mysqli_error($conn);
        }

        // Cerrar el statement de eliminación
        mysqli_stmt_close($stmt_delete);
    }

    // Cerrar la conexión
    mysqli_close($conn);
} else {
    echo "ID de categoría no recibido.";
}
?>
