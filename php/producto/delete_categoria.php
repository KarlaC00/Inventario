<?php
if (isset($_POST['idCategoria']) && is_numeric($_POST['idCategoria'])) {
    $idCategoria = intval($_POST['idCategoria']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestorinventario";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Verificar si la categoría tiene subcategorías
    $query_count_subcategories = "SELECT COUNT(*) AS numSubcategorias FROM Subcategoria WHERE Categoria_IdCategoria = ?";
    $stmt_count = mysqli_prepare($conn, $query_count_subcategories);
    mysqli_stmt_bind_param($stmt_count, "i", $idCategoria);
    mysqli_stmt_execute($stmt_count);
    mysqli_stmt_bind_result($stmt_count, $numSubcategorias);
    mysqli_stmt_fetch($stmt_count);
    mysqli_stmt_close($stmt_count);

    if ($numSubcategorias > 0) {
        echo "No se puede eliminar la categoría porque tiene subcategorías asociadas.";
    } else {
        mysqli_autocommit($conn, false); // Inicia una transacción

        // Eliminar la categoría principal
        $query_delete_category = "DELETE FROM Categoria WHERE IdCategoria = ?";
        $stmt_delete_category = mysqli_prepare($conn, $query_delete_category);
        mysqli_stmt_bind_param($stmt_delete_category, "i", $idCategoria);

        if (mysqli_stmt_execute($stmt_delete_category)) {
            mysqli_commit($conn); // Confirma la transacción si todas las consultas se ejecutaron con éxito
            echo "Categoría eliminada correctamente.";
        } else {
            mysqli_rollback($conn); // Revierte la transacción si hubo algún error
            echo "Error al eliminar la categoría: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_delete_category);
    }

    mysqli_close($conn);
} else {
    echo "ID de categoría no recibido o inválido.";
}
?>