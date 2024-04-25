<?php
// Configuración de la base de datos
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia si es necesario
$password = ""; // Cambia si es necesario
$dbname = "gestorinventario"; // Cambia si es necesario

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verifica si se ha enviado un término de búsqueda
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Consulta SQL segura con sentencias preparadas
    $query = "SELECT s.IdSalida, s.FechaSalida, c.Nombre AS ClienteNombre, u.Usuario AS UsuarioNombre,
                    (SELECT COUNT(*) 
                     FROM DetalleSalida
                     WHERE Salida_IdSalida = s.IdSalida) AS Productos,
                    (SELECT SUM(PrecioSalida) 
                     FROM DetalleSalida 
                     WHERE Salida_IdSalida = s.IdSalida) AS PrecioTotal
              FROM Salida s
              INNER JOIN Cliente c ON s.Cliente_IdCliente = c.IdCliente
              INNER JOIN Usuario u ON s.Usuario_IdUsuario = u.IdUsuario
              WHERE s.FechaSalida LIKE ?
              OR c.Nombre LIKE ?
              OR u.Usuario LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $searchPattern = '%' . $searchTerm . '%';

    mysqli_stmt_bind_param($stmt, 'sss', $searchPattern, $searchPattern, $searchPattern);
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verifica el resultado de la consulta
    if ($result) {
        $ventas = array();
        // Recorre los resultados y los almacena en un array
        while ($row = mysqli_fetch_assoc($result)) {
            $ventas[] = $row;
        }
        
        // Establece el encabezado para indicar respuesta JSON
        header('Content-Type: application/json');
        // Devuelve las salidas encontradas en formato JSON
        echo json_encode($ventas);
    } else {
        // Si ocurre un error en la consulta
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error al ejecutar la consulta'));
    }
} else {
    // Si no se proporciona un término de búsqueda
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'No se proporcionó un término de búsqueda'));
}

// Cerrar la conexión
mysqli_close($conn);
?>