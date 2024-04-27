<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestorinventario";

// Verificar si se recibieron los datos del formulario
if (isset($_POST['nombre']) && isset($_POST['categoria'])) {
    // Recibir y sanitizar datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $categoriaId = intval($_POST['categoria']); // Convertir a entero

    // Establecer el valor por defecto de estado como 1 si no se proporciona
    $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;

    // Conectar a la base de datos
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar conexión
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query SQL para insertar la subcategoría usando un prepared statement
    $query = "INSERT INTO Subcategoria (Nombre, Estado, Categoria_IdCategoria) VALUES (?, ?, ?)";
    
    // Preparar la consulta SQL usando un prepared statement
    $stmt = mysqli_prepare($conn, $query);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        // Vincular parámetros y ejecutar la consulta
        mysqli_stmt_bind_param($stmt, "sii", $nombre, $estado, $categoriaId);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Subcategoría creada correctamente.";
        } else {
            echo "Error al crear la subcategoría: " . mysqli_error($conn);
        }

        // Cerrar el statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error en la preparación de la consulta: " . mysqli_error($conn);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
} else {
    echo "Error: Datos incompletos.";
}

// Redireccionar a la página de gestión de subcategorías después de procesar el formulario
header("Location: ../../pagina/producto/subcategoria.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
