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

// Consulta SQL para obtener todas las salidas junto con la suma de las cantidades de los productos
$query = "SELECT s.IdSalida, s.FechaSalida, 
                 c.Nombre AS ClienteNombre, 
                 u.Usuario AS UsuarioNombre, 
                 (SELECT SUM(Cantidad) 
                  FROM DetalleSalida 
                  WHERE Salida_IdSalida = s.IdSalida) AS Productos, 
                 (SELECT SUM(PrecioSalida) 
                  FROM DetalleSalida 
                  WHERE Salida_IdSalida = s.IdSalida) AS PrecioTotal
          FROM Salida s
          INNER JOIN Cliente c ON s.Cliente_IdCliente = c.IdCliente
          INNER JOIN Usuario u ON s.Usuario_IdUsuario = u.IdUsuario";

$result = mysqli_query($conn, $query);

// Crear un array para almacenar las salidas
$departures = array();

// Recorrer los resultados y añadirlos al array
while ($row = mysqli_fetch_assoc($result)) {
    $departures[] = $row;
}

// Devolver las salidas en formato JSON
echo json_encode($departures);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>