<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verifica si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? trim((string)$_GET['search']) : null;

if ($searchTerm !== null) {
    // Prepara la consulta SQL para buscar salidas por múltiples campos
    $query = "
        SELECT 
            s.IdSalida, 
            s.FechaSalida, 
            c.Nombre AS ClienteNombre, 
            u.Usuario AS UsuarioNombre,
            (SELECT SUM(Cantidad)
             FROM DetalleSalida 
             WHERE Salida_IdSalida = s.IdSalida) AS Productos,
            (SELECT SUM(PrecioSalida) 
             FROM DetalleSalida 
             WHERE Salida_IdSalida = s.IdSalida) AS PrecioTotal
        FROM 
            Salida s
        INNER JOIN 
            Cliente c ON s.Cliente_IdCliente = c.IdCliente
        INNER JOIN 
            Usuario u ON s.Usuario_IdUsuario = u.IdUsuario
        WHERE 
            s.FechaSalida LIKE ?
            OR c.Nombre LIKE ?
            OR u.Usuario LIKE ?
    ";

    // Prepara la consulta
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Configurar el patrón de búsqueda
        $searchPattern = '%' . $searchTerm . '%';

        // Vincular parámetros
        $stmt->bind_param('sss', 
            $searchPattern, 
            $searchPattern, 
            $searchPattern
        );

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();

        if ($result) {
            $ventas = array();
            // Recorre los resultados y almacena en un array
            while ($row = $result->fetch_assoc()) {
                $ventas[] = $row;
            }
            // Devuelve las salidas encontradas en formato JSON
            echo json_encode($ventas);
        } else {
            // Si ocurre un error en la consulta
            echo json_encode(array('error' => 'Error al ejecutar la consulta'));
        }

        // Cierra la declaración
        $stmt->close();
    } else {
        // Si la preparación de la consulta falla
        echo json_encode(array('error' => 'Error al preparar la consulta'));
    }
} else {
    // Si no se proporciona un término de búsqueda
    echo json_encode(array('error' => 'No se proporcionó un término de búsqueda'));
}

// Cierra la conexión
$conn->close();
?>