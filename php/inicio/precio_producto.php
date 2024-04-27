<?php
function getTotalProductCost($servername, $username, $password, $database)
{
    // Establecer conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $database);

    // Revisar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener el costo total de todos los productos
    $sql = "SELECT SUM(Precio * CantidadDisponible) AS CostoTotal FROM Producto";


    $result = $conn->query($sql);

    $costoTotal = 0;

    if ($result->num_rows > 0) {
        // Obtener el costo total sumando los costos individuales de cada producto
        $row = $result->fetch_assoc();
        $costoTotal = $row["CostoTotal"];
    }

    // Cerrar conexión a la base de datos
    $conn->close();

    return $costoTotal;
}

// Uso de la función para obtener el total de precios multiplicados por cantidad
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

$total = getTotalProductCost($servername, $username, $password, $dbname);

?>