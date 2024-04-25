SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema GestorInventario
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `GestorInventario` DEFAULT CHARACTER SET utf8mb4;
USE `GestorInventario`;

-- -----------------------------------------------------
-- Table `GestorInventario`.`nivelAcceso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`nivelAcceso` (
  `IdnivelAcceso` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`IdnivelAcceso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar niveles de acceso.';

-- Insertar los niveles de acceso por defecto
INSERT INTO `GestorInventario`.`nivelAcceso` (`IdnivelAcceso`, `Nombre`) VALUES (1, 'Escritura');
INSERT INTO `GestorInventario`.`nivelAcceso` (`IdnivelAcceso`, `Nombre`) VALUES (2, 'Lectura');

-- -----------------------------------------------------
-- Table `GestorInventario`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Usuario` (
  `IdUsuario` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` VARCHAR(15) NOT NULL,
  `TipoIdentificacion` VARCHAR(10) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Usuario` VARCHAR(100) NOT NULL,
  `Contrasena` VARCHAR(100) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `nivelAcceso_IdnivelAcceso` INT NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  INDEX `fk_Usuario_nivelAcceso1_idx` (`nivelAcceso_IdnivelAcceso`),
  CONSTRAINT `fk_Usuario_nivelAcceso1`
    FOREIGN KEY (`nivelAcceso_IdnivelAcceso`)
    REFERENCES `GestorInventario`.`nivelAcceso` (`IdnivelAcceso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de usuarios.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Categoria` (
  `IdCategoria` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar categorías de productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Subcategoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Subcategoria` (
  `IdSubcategoria` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(150) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `Categoria_IdCategoria` INT NOT NULL,
  PRIMARY KEY (`IdSubcategoria`),
  INDEX `fk_Subcategoria_Categoria1_idx` (`Categoria_IdCategoria`),
  CONSTRAINT `fk_Subcategoria_Categoria1`
    FOREIGN KEY (`Categoria_IdCategoria`)
    REFERENCES `GestorInventario`.`Categoria` (`IdCategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar subcategorías de productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Producto` (
  `IdProducto` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(150) NOT NULL,
  `Descripcion` VARCHAR(500) NULL,
  `Imagen` LONGBLOB NULL,
  `Precio` DECIMAL(10,2) NOT NULL,
  `CantidadDisponible` INT NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `Subcategoria_IdSubcategoria` INT NOT NULL,
  PRIMARY KEY (`IdProducto`),
  INDEX `fk_Producto_Subcategoria_idx` (`Subcategoria_IdSubcategoria`),
  CONSTRAINT `fk_Producto_Subcategoria`
    FOREIGN KEY (`Subcategoria_IdSubcategoria`)
    REFERENCES `GestorInventario`.`Subcategoria` (`IdSubcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Proveedor` (
  `IdProveedor` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(150) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` VARCHAR(15) NOT NULL,
  `TipoIdentificacion` VARCHAR(10) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdProveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de proveedores.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Cliente` (
  `IdCliente` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(150) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` VARCHAR(15) NOT NULL,
  `TipoIdentificacion` VARCHAR(10) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de clientes.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Salida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Salida` (
  `IdSalida` INT NOT NULL AUTO_INCREMENT,
  `FechaSalida` DATE NOT NULL,
  `Cliente_IdCliente` INT NOT NULL,
  `Usuario_IdUsuario` INT NOT NULL,
  PRIMARY KEY (`IdSalida`),
  INDEX `fk_Salida_Cliente1_idx` (`Cliente_IdCliente`),
  INDEX `fk_Salida_Usuario1_idx` (`Usuario_IdUsuario`),
  CONSTRAINT `fk_Salida_Cliente1`
    FOREIGN KEY (`Cliente_IdCliente`)
    REFERENCES `GestorInventario`.`Cliente` (`IdCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Salida_Usuario1`
    FOREIGN KEY (`Usuario_IdUsuario`)
    REFERENCES `GestorInventario`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de salidas de productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`DetalleSalida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`DetalleSalida` (
  `IdDetalleSalida` INT NOT NULL AUTO_INCREMENT,
  `Cantidad` INT NOT NULL,
  `PrecioSalida` DECIMAL(10,2) NOT NULL,
  `Producto_IdProducto` INT NOT NULL,
  `Salida_IdSalida` INT NOT NULL,
  PRIMARY KEY (`IdDetalleSalida`, `Producto_IdProducto`, `Salida_IdSalida`),
  INDEX `fk_DetalleSalida_Producto1_idx` (`Producto_IdProducto`),
  INDEX `fk_DetalleSalida_Salida1_idx` (`Salida_IdSalida`),
  CONSTRAINT `fk_DetalleSalida_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `GestorInventario`.`Producto` (`IdProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleSalida_Salida1`
    FOREIGN KEY (`Salida_IdSalida`)
    REFERENCES `GestorInventario`.`Salida` (`IdSalida`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar detalles de salidas de productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`Entrada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`Entrada` (
  `IdEntrada` INT NOT NULL AUTO_INCREMENT,
  `FechaEntrada` DATE NOT NULL,
  `Proveedor_IdProveedor` INT NOT NULL,
  `Usuario_IdUsuario` INT NOT NULL,
  PRIMARY KEY (`IdEntrada`),
  INDEX `fk_Entrada_Proveedor1_idx` (`Proveedor_IdProveedor`),
  INDEX `fk_Entrada_Usuario1_idx` (`Usuario_IdUsuario`),
  CONSTRAINT `fk_Entrada_Proveedor1`
    FOREIGN KEY (`Proveedor_IdProveedor`)
    REFERENCES `GestorInventario`.`Proveedor` (`IdProveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Entrada_Usuario1`
    FOREIGN KEY (`Usuario_IdUsuario`)
    REFERENCES `GestorInventario`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de entradas de productos.';

-- -----------------------------------------------------
-- Table `GestorInventario`.`DetalleEntrada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestorInventario`.`DetalleEntrada` (
  `IdDetalleEntrada` INT NOT NULL AUTO_INCREMENT,
  `Cantidad` INT NOT NULL,
  `PrecioEntrada` DECIMAL(10,2) NOT NULL,
  `Producto_IdProducto` INT NOT NULL,
  `Entrada_IdEntrada` INT NOT NULL,
  PRIMARY KEY (`IdDetalleEntrada`, `Producto_IdProducto`, `Entrada_IdEntrada`),
  INDEX `fk_DetalleEntrada_Producto1_idx` (`Producto_IdProducto`),
  INDEX `fk_DetalleEntrada_Entrada1_idx` (`Entrada_IdEntrada`),
  CONSTRAINT `fk_DetalleEntrada_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `GestorInventario`.`Producto` (`IdProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleEntrada_Entrada1`
    FOREIGN KEY (`Entrada_IdEntrada`)
    REFERENCES `GestorInventario`.`Entrada` (`IdEntrada`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar detalles de entradas de productos.';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;