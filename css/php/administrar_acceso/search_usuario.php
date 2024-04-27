<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
//$conectar    = mysqli_connect($servidor, $usuario, $clave, $datos);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica si se ha enviado un término de búsqueda
if(isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Prepara la consulta SQL para buscar usuarios por múltiples campos
    $query = "SELECT u.*, n.Nombre AS NivelAcceso 
              FROM Usuario u
              INNER JOIN nivelAcceso n ON u.nivelAcceso_IdnivelAcceso = n.IdnivelAcceso
              WHERE u.Nombre LIKE '%$searchTerm%'
              OR u.Correo LIKE '%$searchTerm%'
              OR u.Contrasena LIKE '%$searchTerm%'
              OR u.numeroTelefonico LIKE '%$searchTerm%'
              OR u.TipoIdentificacion LIKE '%$searchTerm%'
              OR u.numeroIdentificacion LIKE '%$searchTerm%'
              OR u.Usuario LIKE '%$searchTerm%'";

    $result = mysqli_query($conn, $query);

    if($result) {
        $usuarios = array();
        // Recorre los resultados y los almacena en un array
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
        // Devuelve los usuarios encontrados en formato JSON
        echo json_encode($usuarios);
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