<?php
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia si es necesario
$password = ""; // Cambia si es necesario
$dbname = "gestorinventario"; // Cambia si es necesario

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica si se ha enviado un término de búsqueda
if(isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Prepara la consulta SQL para buscar entradas por múltiples campos
    $query = "SELECT e.IdEntrada, e.FechaEntrada, p.Nombre AS ProveedorNombre, u.Usuario AS UsuarioNombre,
                    (SELECT COUNT(*) 
                     FROM DetalleEntrada 
                     WHERE Entrada_IdEntrada = e.IdEntrada) AS Productos,
                    (SELECT SUM(PrecioEntrada) 
                     FROM DetalleEntrada 
                     WHERE Entrada_IdEntrada = e.IdEntrada) AS PrecioTotal
              FROM Entrada e
              INNER JOIN Proveedor p ON e.Proveedor_IdProveedor = p.IdProveedor
              INNER JOIN Usuario u ON e.Usuario_IdUsuario = u.IdUsuario
              WHERE e.FechaEntrada LIKE '%$searchTerm%'
              OR p.Nombre LIKE '%$searchTerm%'
              OR u.Usuario LIKE '%$searchTerm%'";

    $result = mysqli_query($conn, $query);

    if($result) {
        $compras = array();
        // Recorre los resultados y los almacena en un array
        while ($row = mysqli_fetch_assoc($result)) {
            $compras[] = $row;
        }
        // Devuelve las entradas encontradas en formato JSON
        echo json_encode($compras);
    } else {
        // Si ocurre un error en la consulta
        echo json_encode(array('error' => 'Error al ejecutar la consulta: ' . mysqli_error($conn)));
    }
} else {
    // Si no se proporciona un término de búsqueda
    echo json_encode(array('error' => 'No se proporcionó un término de búsqueda'));
}

// Cierra la conexión
mysqli_close($conn);
?>