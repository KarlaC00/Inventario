<?php
$page = 'contacto'; // Define la página actual
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
    <link rel="stylesheet" href="../../css/contacto/actualizar_proveedor_styles.css">
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
            <img src="../../img/svg/contact.svg" alt="Proveedor" class="title-svg">
            <span>Proveedor</span>
        </div>
        <div class="body-content">
            <div class="container">
                <div class="add-user">
                    <img src="../../img/svg/edit.svg" alt="Agregar Proveedor">
                    <span class="title">Editar Proveedor</span>
                </div>
                <?php
                // Verificar si se recibió un ID de proveedor válido
                if (isset($_GET['id']) && !empty($_GET['id'])) {
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

                    // Obtener el ID del proveedor
                    $idProveedor = $_GET['id'];

                    // Consulta SQL para obtener los datos del proveedor por su ID
                    $query = "SELECT * FROM Proveedor WHERE IdProveedor = $idProveedor";
                    $result = mysqli_query($conn, $query);

                    // Verificar si se encontraron resultados
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                ?>
                        <form action="../../php/contacto/update_proveedor.php" method="POST" id="edit-provider-form" class="form-columns">
                            <!-- Aquí van tus campos de formulario existentes -->
                            <input type="hidden" name="idProveedor" value="<?php echo $row['IdProveedor']; ?>">
                            <div class="input-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="correo">Correo</label>
                                <input type="email" id="correo" name="correo" value="<?php echo $row['Correo']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" id="direccion" name="direccion" value="<?php echo $row['Direccion']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="telefono">Número Telefónico</label>
                                <input type="tel" id="telefono" name="telefono" value="<?php echo $row['numeroTelefonico']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="tipo_identificacion">Tipo de Identificación</label>
                                <select id="tipo_identificacion" name="tipo_identificacion" required>
                                    <option value="CC" <?php if ($row['TipoIdentificacion'] == 'CC') echo 'selected'; ?>>Cédula de Ciudadanía</option>
                                    <option value="TI" <?php if ($row['TipoIdentificacion'] == 'TI') echo 'selected'; ?>>Tarjeta de Identidad</option>
                                    <option value="RCN" <?php if ($row['TipoIdentificacion'] == 'RCN') echo 'selected'; ?>>Registro Civil de Nacimiento</option>
                                    <option value="CE" <?php if ($row['TipoIdentificacion'] == 'CE') echo 'selected'; ?>>Cédula de Extranjería</option>
                                    <option value="RUT" <?php if ($row['TipoIdentificacion'] == 'RUT') echo 'selected'; ?>>Registro Único Tributario</option>
                                    <option value="TIN" <?php if ($row['TipoIdentificacion'] == 'TIN') echo 'selected'; ?>>Tarjeta de Identificación Tributaria</option>
                                    <option value="NIT" <?php if ($row['TipoIdentificacion'] == 'NIT') echo 'selected'; ?>>Número de Identificación Tributaria</option>
                                    <option value="NUIP" <?php if ($row['TipoIdentificacion'] == 'NUIP') echo 'selected'; ?>>Número Único de Identificación Personal</option>
                                    <option value="EPS" <?php if ($row['TipoIdentificacion'] == 'EPS') echo 'selected'; ?>>Entidad Promotora de Salud</option>
                                    <option value="ARP" <?php if ($row['TipoIdentificacion'] == 'ARP') echo 'selected'; ?>>Administradora de Riesgos Laborales</option>
                                    <option value="PPE" <?php if ($row['TipoIdentificacion'] == 'PPE') echo 'selected'; ?>>Permiso Provisional Especial</option>
                                    <option value="PEP" <?php if ($row['TipoIdentificacion'] == 'PEP') echo 'selected'; ?>>Permiso Especial de Permanencia</option>
                                    <option value="TSE" <?php if ($row['TipoIdentificacion'] == 'TSE') echo 'selected'; ?>>Tarjeta de Servicios Electrónicos</option>
                                    <option value="TP" <?php if ($row['TipoIdentificacion'] == 'TP') echo 'selected'; ?>>Tarjeta Profesional</option>
                                    <option value="CREMIL" <?php if ($row['TipoIdentificacion'] == 'CREMIL') echo 'selected'; ?>>Carné Militar</option>
                                    <option value="RUNT" <?php if ($row['TipoIdentificacion'] == 'RUNT') echo 'selected'; ?>>Registro Único Nacional de Tránsito</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="numero_identificacion">Número de Identificación</label>
                                <input type="text" id="numero_identificacion" name="numero_identificacion" value="<?php echo $row['numeroIdentificacion']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="estado">Estado:</label>
                                <select id="estado" name="estado" required>
                                    <option value="1" <?php if ($row['Estado'] == 1) echo 'selected'; ?>>Activo</option>
                                    <option value="0" <?php if ($row['Estado'] == 0) echo 'selected'; ?>>Inactivo</option>
                                </select>
                            </div>
                            <button type="submit">Actualizar</button>
                        </form>
                <?php
                    } else {
                        echo "<p>No se encontraron datos de proveedor para editar.</p>";
                    }

                    // Cerrar conexión a la base de datos
                    mysqli_close($conn);
                } else {
                    echo "<p>No se proporcionó un ID de proveedor válido.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>