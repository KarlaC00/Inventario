<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica si se ha enviado un término de búsqueda
if(isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Prepara la consulta SQL para buscar clientes por múltiples campos
    $query = "SELECT * FROM Cliente
              WHERE Nombre LIKE '%$searchTerm%'
              OR Correo LIKE '%$searchTerm%'
              OR Direccion LIKE '%$searchTerm%'
              OR numeroTelefonico LIKE '%$searchTerm%'
              OR TipoIdentificacion LIKE '%$searchTerm%'
              OR numeroIdentificacion LIKE '%$searchTerm%'";

    $result = mysqli_query($conn, $query);

    if($result) {
        $clientes = array();
        // Recorre los resultados y los almacena en un array
        while ($row = mysqli_fetch_assoc($result)) {
            $clientes[] = $row;
        }
        // Devuelve los clientes encontrados en formato JSON
        echo json_encode($clientes);
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