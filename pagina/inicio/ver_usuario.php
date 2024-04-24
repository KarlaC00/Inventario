<?php 
$page = 'usuario'; // Define la página actual
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirigir a la página de inicio de sesión si no se ha iniciado sesión
    header("Location: ../../index.php");
    exit();
}

// Definir los roles según el nivel de acceso
$roles = [
    1 => "Escritura",
    2 => "Lectura"
];

// Obtener el nivel de acceso del usuario desde la sesión
$nivelAcceso = isset($_SESSION['nivelAcceso_IdnivelAcceso']) ? $_SESSION['nivelAcceso_IdnivelAcceso'] : null;

// Determinar el rol del usuario
$rolUsuario = isset($roles[$nivelAcceso]) ? $roles[$nivelAcceso] : "Desconocido";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../../css/inicio_sesion/ver_usuario_styles.css">
</head>
    <body>
        <?php include '../../sidebar.php'; ?>
        <div class="content">
            <div class="header">
                <div class="user-info">
                    <div class="user-icon">
                        <img src="../../img/svg/icon.svg" alt="Usuario">
                    </div>
                    <div class="user-details">
                        <span style="font-size: 16px; font-weight: bold; margin-bottom: 2px;"><?php echo $_SESSION['usuario']; ?></span>
                        <span style="font-size: 12px;"><?php echo $rolUsuario; ?></span>
                    </div>
                    <div class="dropdown-menu-container">
                        <div class="dropdown-toggle">
                            <img src="../../img/svg/option.svg" alt="Opciones">
                            <span>Opciones</span>
                        </div>
                        <div class="menu-content">
                            <a href="../../logout.php">Cerrar sesión</a>
                            <a href="#">Ver usuario</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="title-section">
                <img src="../../img/svg/person.svg" alt="Administrar acceso" class="title-svg">
                <span>Usuario</span>
            </div>
            <div class="body-content">
                <div class="container">
                    <?php
                    // Conexión a la base de datos
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "gestorinventario";

                    $conn = mysqli_connect($servername, $username, $password, $dbname);

                    if (!$conn) {
                        die("Conexión fallida: " . mysqli_connect_error());
                    }

                    // Obtener el nombre de usuario desde la sesión
                    $nombreUsuario = $_SESSION['usuario'];

                    // Consulta para obtener los datos del usuario actual por nombre de usuario
                    $query = "SELECT * FROM Usuario WHERE Usuario = '$nombreUsuario'";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        ?>
                        <div class="form-columns">
                            <!-- Mostrar los detalles del usuario -->
                            <div class="input-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre']; ?>" readonly>
                            </div>
                            <div class="input-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" id="usuario" name="usuario" value="<?php echo $row['Usuario']; ?>" readonly>
                            </div>
                            <div class="input-group">
                            <label for="correo">Correo</label>
                            <input type="email" id="correo" name="correo" value="<?php echo $row['Correo']; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label for="telefono">Número Telefónico</label>
                            <input type="tel" id="telefono" name="telefono" value="<?php echo $row['numeroTelefonico']; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label for="tipo_identificacion">Tipo de Identificación</label>
                            <input type="text" id="tipo_identificacion" name="tipo_identificacion" value="<?php echo $row['TipoIdentificacion']; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label for="numero_identificacion">Número de Identificación</label>
                            <input type="text" id="numero_identificacion" name="numero_identificacion" value="<?php echo $row['numeroIdentificacion']; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label for="nivel_acceso">Nivel de Acceso</label>
                            <input type="text" id="nivel_acceso" name="nivel_acceso" value="<?php echo ($row['nivelAcceso_IdnivelAcceso'] == 1) ? 'Escritura' : 'Lectura'; ?>" readonly>
                        </div>
                    </div>
                    <?php
                    } else {
                        echo "<p>No se encontraron datos de usuario para mostrar.</p>";
                    }

                    // Cerrar conexión a la base de datos
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
