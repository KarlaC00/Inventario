<?php
function getTotalSalidaPrice($servername, $username, $password, $database)
{
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT SUM(PrecioSalida * Cantidad) AS PrecioTotal FROM DetalleSalida";
    $result = $conn->query($sql);

    $precioTotal = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $precioTotal = $row["PrecioTotal"];
    }

    $conn->close();
    return $precioTotal;
}
?>
