<link rel="stylesheet" href="../../sidebar_styles.css">
<nav class="sidebar">
    <div class="logo">
        <img src="../../img/svg/gestor.svg" alt="Logo">
        <div class="logo-content">
            <span>Gestión</span>
            <span>de inventario</span>
        </div>
    </div>
    <ul class="menu">
        <li <?php echo ($page == 'inicio') ? 'class="active"' : ''; ?>><a href="../../pagina/inicio/inicio.php"><span <?php echo ($page == 'inicio') ? 'class="indicator"' : ''; ?>></span><img src="../../img/svg/house.svg" alt="Inicio">Inicio</a></li>
        <li <?php echo ($page == 'producto') ? 'class="active"' : ''; ?>><a href="../../pagina/producto/producto.php"><span <?php echo ($page == 'producto') ? 'class="indicator"' : ''; ?>></span><img src="../../img/svg/box.svg" alt="Productos">Productos</a></li>
        <li <?php echo ($page == 'compra') ? 'class="dropdown active"' : ''; ?><?php echo ($page != 'compra') ? 'onclick="toggleDropdown(this)"' : ''; ?> class="dropdown">
            <a href="#">
                <img src="../../img/svg/buy.svg" alt="Compra">
                <span <?php echo ($page == 'compra') ? 'class="indicator"' : ''; ?>></span>
                Compra
                <img src="../../img/svg/arrow.svg" alt="Desplegar" class="arrow" style="margin-left: 10px; width: 10px; height: 10px;">
            </a>
            <ul class="submenu">
                <li><a href="../../pagina/compra/agregar_compra.php">Nueva compra</a></li>
                <li><a href="../../pagina/compra/compra.php">Historial</a></li>
            </ul>
        </li>
        <li <?php echo ($page == 'venta') ? 'class="dropdown active"' : ''; ?><?php echo ($page != 'venta') ? 'onclick="toggleDropdown(this)"' : ''; ?> class="dropdown">
            <a href="#">
                <img src="../../img/svg//sell.svg" alt="Venta">
                <span <?php echo ($page == 'compra') ? 'class="indicator"' : ''; ?>></span>
                Venta
                <img src="../../img/svg/arrow.svg" alt="Desplegar" class="arrow" style="margin-left: 10px; width: 10px; height: 10px;">
            </a>
            <ul class="submenu">
                <li><a href="../../pagina/venta/agregar_venta.php">Nueva venta</a></li>
                <li><a href="../../pagina/venta/venta.php">Historial</a></li>
            </ul>
        </li>
        <li <?php echo ($page == 'contacto') ? 'class="dropdown active"' : ''; ?><?php echo ($page != 'contacto') ? 'onclick="toggleDropdown(this)"' : ''; ?> class="dropdown">
            <a href="#">
                <img src="../../img/svg/contact.svg" alt="Contacto">
                <span <?php echo ($page == 'contacto') ? 'class="indicator"' : ''; ?>></span>
                Contacto
                <img src="../../img/svg/arrow.svg" alt="Desplegar" class="arrow" style="margin-left: 10px; width: 10px; height: 10px;">
            </a>
            <ul class="submenu">
                <li><a href="../../pagina/contacto/cliente.php">Cliente</a></li>
                <li><a href="../../pagina/contacto/proveedor.php">Proveedor</a></li>
            </ul>
        </li>
        <li <?php echo ($page == 'administrar_acceso') ? 'class="active"' : ''; ?>><a href="../../pagina/administrar_acceso/usuario.php"><span <?php echo ($page == 'administrar_acceso') ? 'class="indicator"' : ''; ?>></span><img src="../../img/svg/lock.svg" alt="Administrar acceso">Administrar acceso</a></li>
    </ul>
</nav>
<script src="../../sidebar_scripts.js"></script>