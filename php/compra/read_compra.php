<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obtener todas las entradas junto con la suma de las cantidades de los productos
$query = "SELECT e.IdEntrada, e.FechaEntrada, 
                 p.Nombre AS ProveedorNombre, 
                 u.Usuario AS UsuarioNombre, 
                 (SELECT SUM(Cantidad) 
                  FROM DetalleEntrada 
                  WHERE Entrada_IdEntrada = e.IdEntrada) AS Productos, 
                 (SELECT SUM(PrecioEntrada) 
                  FROM DetalleEntrada 
                  WHERE Entrada_IdEntrada = e.IdEntrada) AS PrecioTotal
          FROM Entrada e
          INNER JOIN Proveedor p ON e.Proveedor_IdProveedor = p.IdProveedor
          INNER JOIN Usuario u ON e.Usuario_IdUsuario = u.IdUsuario";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar las entradas
$entries = array();

// Recorrer los resultados y añadirlos al array
while ($row = mysqli_fetch_assoc($result)) {
    $entries[] = $row;
}

// Devolver las entradas en formato JSON
echo json_encode($entries);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>