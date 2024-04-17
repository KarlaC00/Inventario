<?php
// Verificar si se recibió el ID de la categoría a eliminar
if (isset($_POST['idSubcategoria'])) {
    // Obtener y sanear el ID de la subcategoría
    $idSubcategoria = intval($_POST['idSubcategoria']); // Convertir a entero para mayor seguridad

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

    // Preparar la consulta SQL usando un prepared statement
    $query = "DELETE FROM subcategoria WHERE IdSubcategoria = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $idSubcategoria);
    
    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        echo "Subcategoria eliminada correctamente.";
    } else {
        echo "Error al eliminar la Subcategoria: " . mysqli_error($conn);
    }

    // Cerrar el statement y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "ID de Subcategoria no recibido.";
}
?>
