DROP DATABASE dwes;
-- Creamos la base de datos
CREATE DATABASE `dwes` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;USE `dwes`;
-- Creamos las tablas
CREATE TABLE `dwes`.`tienda` (`cod` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`nombre` VARCHAR(100)NOT NULL,`tlf` VARCHAR(13)NULL)ENGINE=INNODB;
CREATE TABLE  `dwes`.`producto` (`cod` VARCHAR(12)NOT NULL,`nombre` VARCHAR(200)NULL,`nombre_corto` VARCHAR(50)NOT NULL,`descripcion` TEXT NULL,`PVP` DECIMAL(10,2)NOT NULL,`familia` VARCHAR(6)NOT NULL,PRIMARY KEY(  `cod` ),INDEX(  `familia` ),UNIQUE( `nombre_corto` ))ENGINE=INNODB;
CREATE TABLE  `dwes`.`familia` (`cod` VARCHAR(6)NOT NULL,`nombre` VARCHAR(200)NOT NULL,PRIMARY KEY(  `cod` ))ENGINE=INNODB;
CREATE TABLE  `dwes`.`stock` (`producto` VARCHAR(12)NOT NULL,`tienda` INT NOT NULL,`unidades` INT NOT NULL,PRIMARY KEY(  `producto` ,  `tienda` ))ENGINE=INNODB;
-- Creamos las claves foráneas
ALTER TABLE `producto`ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY(`familia`) REFERENCES `familia` (`cod`)ON UPDATE CASCADE;
ALTER TABLE `stock`ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY(`tienda`)REFERENCES `tienda` (`cod`) ON UPDATE CASCADE,ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY(`producto`)REFERENCES `producto` (`cod`)ON UPDATE CASCADE;
CREATE USER `dwes`IDENTIFIED BY'abc123.';
GRANT ALL ON `dwes`.* TO `dwes`;