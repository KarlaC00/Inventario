SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema Gestor_Inventario
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Gestor_Inventario` DEFAULT CHARACTER SET utf8mb4;
USE `Gestor_Inventario`;

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`nivelAcceso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`nivelAcceso` (
  `IdnivelAcceso` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`IdnivelAcceso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar niveles de acceso.';

-- Insertar los niveles de acceso por defecto
INSERT INTO `Gestor_Inventario`.`nivelAcceso` (`IdnivelAcceso`, `Nombre`) VALUES (1, 'Escritura');
INSERT INTO `Gestor_Inventario`.`nivelAcceso` (`IdnivelAcceso`, `Nombre`) VALUES (2, 'Lectura');

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Usuario` (
  `IdUsuario` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` INT NOT NULL,
  `TipoIdentificacion` BIT(1) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Usuario` VARCHAR(100) NOT NULL,
  `Contrasena` VARCHAR(100) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `nivelAcceso_IdnivelAcceso` INT NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  INDEX `fk_Usuario_nivelAcceso1_idx` (`nivelAcceso_IdnivelAcceso`),
  CONSTRAINT `fk_Usuario_nivelAcceso1`
    FOREIGN KEY (`nivelAcceso_IdnivelAcceso`)
    REFERENCES `Gestor_Inventario`.`nivelAcceso` (`IdnivelAcceso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de usuarios.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Categoria` (
  `IdCategoria` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar categorías de productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Subcategoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Subcategoria` (
  `IdSubcategoria` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(150) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `Categoria_IdCategoria` INT NOT NULL,
  PRIMARY KEY (`IdSubcategoria`),
  INDEX `fk_Subcategoria_Categoria1_idx` (`Categoria_IdCategoria`),
  CONSTRAINT `fk_Subcategoria_Categoria1`
    FOREIGN KEY (`Categoria_IdCategoria`)
    REFERENCES `Gestor_Inventario`.`Categoria` (`IdCategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar subcategorías de productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Producto` (
  `IdProducto` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Descripcion` VARCHAR(500) NULL,
  `Imagen` VARCHAR(50) NULL,
  `Precio` DECIMAL(5,2) NOT NULL,
  `CantidadDisponible` INT NOT NULL,
  `Estado` BIT(1) NOT NULL,
  `Subcategoria_IdSubcategoria` INT NOT NULL,
  PRIMARY KEY (`IdProducto`),
  INDEX `fk_Producto_Subcategoria_idx` (`Subcategoria_IdSubcategoria`),
  CONSTRAINT `fk_Producto_Subcategoria`
    FOREIGN KEY (`Subcategoria_IdSubcategoria`)
    REFERENCES `Gestor_Inventario`.`Subcategoria` (`IdSubcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Proveedor` (
  `IdProveedor` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` INT NOT NULL,
  `TipoIdentificacion` BIT(1) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdProveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de proveedores.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Cliente` (
  `IdCliente` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(100) NOT NULL,
  `Correo` VARCHAR(150) NOT NULL,
  `numeroTelefonico` INT NOT NULL,
  `TipoIdentificacion` BIT(1) NOT NULL,
  `numeroIdentificacion` VARCHAR(20) NOT NULL,
  `Estado` BIT(1) NOT NULL,
  PRIMARY KEY (`IdCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de clientes.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Salida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Salida` (
  `IdSalida` INT NOT NULL AUTO_INCREMENT,
  `FechaSalida` DATE NOT NULL,
  `Cliente_IdCliente` INT NOT NULL,
  `Usuario_IdUsuario` INT NOT NULL,
  PRIMARY KEY (`IdSalida`),
  INDEX `fk_Salida_Cliente1_idx` (`Cliente_IdCliente`),
  INDEX `fk_Salida_Usuario1_idx` (`Usuario_IdUsuario`),
  CONSTRAINT `fk_Salida_Cliente1`
    FOREIGN KEY (`Cliente_IdCliente`)
    REFERENCES `Gestor_Inventario`.`Cliente` (`IdCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Salida_Usuario1`
    FOREIGN KEY (`Usuario_IdUsuario`)
    REFERENCES `Gestor_Inventario`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de salidas de productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`DetalleSalida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`DetalleSalida` (
  `IdDetalleSalida` INT NOT NULL AUTO_INCREMENT,
  `Cantidad` INT NOT NULL,
  `PrecioSalida` DECIMAL(5,2) NOT NULL,
  `Producto_IdProducto` INT NOT NULL,
  `Salida_IdSalida` INT NOT NULL,
  PRIMARY KEY (`IdDetalleSalida`, `Producto_IdProducto`, `Salida_IdSalida`),
  INDEX `fk_DetalleSalida_Producto1_idx` (`Producto_IdProducto`),
  INDEX `fk_DetalleSalida_Salida1_idx` (`Salida_IdSalida`),
  CONSTRAINT `fk_DetalleSalida_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `Gestor_Inventario`.`Producto` (`IdProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleSalida_Salida1`
    FOREIGN KEY (`Salida_IdSalida`)
    REFERENCES `Gestor_Inventario`.`Salida` (`IdSalida`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar detalles de salidas de productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`Entrada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`Entrada` (
  `IdEntrada` INT NOT NULL AUTO_INCREMENT,
  `FechaEntrada` DATE NOT NULL,
  `Proveedor_IdProveedor` INT NOT NULL,
  `Usuario_IdUsuario` INT NOT NULL,
  PRIMARY KEY (`IdEntrada`),
  INDEX `fk_Entrada_Proveedor1_idx` (`Proveedor_IdProveedor`),
  INDEX `fk_Entrada_Usuario1_idx` (`Usuario_IdUsuario`),
  CONSTRAINT `fk_Entrada_Proveedor1`
    FOREIGN KEY (`Proveedor_IdProveedor`)
    REFERENCES `Gestor_Inventario`.`Proveedor` (`IdProveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Entrada_Usuario1`
    FOREIGN KEY (`Usuario_IdUsuario`)
    REFERENCES `Gestor_Inventario`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar información de entradas de productos.';

-- -----------------------------------------------------
-- Table `Gestor_Inventario`.`DetalleEntrada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Gestor_Inventario`.`DetalleEntrada` (
  `IdDetalleEntrada` INT NOT NULL AUTO_INCREMENT,
  `Cantidad` INT NOT NULL,
  `PrecioEntrada` DECIMAL(5,2) NOT NULL,
  `Producto_IdProducto` INT NOT NULL,
  `Entrada_IdEntrada` INT NOT NULL,
  PRIMARY KEY (`IdDetalleEntrada`, `Producto_IdProducto`, `Entrada_IdEntrada`),
  INDEX `fk_DetalleEntrada_Producto1_idx` (`Producto_IdProducto`),
  INDEX `fk_DetalleEntrada_Entrada1_idx` (`Entrada_IdEntrada`),
  CONSTRAINT `fk_DetalleEntrada_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `Gestor_Inventario`.`Producto` (`IdProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleEntrada_Entrada1`
    FOREIGN KEY (`Entrada_IdEntrada`)
    REFERENCES `Gestor_Inventario`.`Entrada` (`IdEntrada`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla para almacenar detalles de entradas de productos.';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;