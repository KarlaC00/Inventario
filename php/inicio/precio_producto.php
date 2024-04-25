<?php
function getTotalProductPrice($servername, $username, $password, $database)
{
    // Establecer conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $database);

    // Revisar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener la suma total de precios de todos los productos
    $sql = "SELECT SUM(Precio) AS PrecioTotal FROM Producto";

    $result = $conn->query($sql);

    $precioTotal = 0; // Inicializar el precio total como cero

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $precioTotal = $row["PrecioTotal"];
    }

    // Cerrar conexión a la base de datos
    $conn->close();

    return $precioTotal;
}
?>
