-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-01-2026 a las 16:34:59
-- Versión del servidor: 5.7.24
-- Versión de PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `qr_carta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `negocio_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `negocio_id`, `nombre`) VALUES
(1, 1, 'Hamburguesas'),
(2, 1, 'Papas Fritas'),
(3, 1, 'Bebidas'),
(4, 2, 'Cucuruchos'),
(5, 2, 'Postres'),
(6, 2, 'Bebidas Frías');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `negocios`
--

CREATE TABLE `negocios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `negocios`
--

INSERT INTO `negocios` (`id`, `nombre`, `slug`, `logo_url`) VALUES
(1, 'Burger Queen43', 'burger-queen', 'https://via.placeholder.com/150?text=Burger+Logo'),
(2, 'Heladería Antártida', 'heladeria-antartida', 'https://via.placeholder.com/150?text=Helados+Logo'),
(4, 'EL CHORI LOCO', 'PARRILLA Y CAFE', 'https://www.cucinare.tv/wp-content/uploads/2022/08/Choripan-1024x576.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `imagen_url`) VALUES
(1, 1, 'Doble con Queso y tomate 222', 'Doble carne, doble cheddar y salsa especial.', '850.00', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500'),
(2, 1, 'Burger Veggie', 'Medallón de lentejas, lechuga y tomate.', '750.00', 'https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=500'),
(3, 2, 'Papas Cheddar', 'Papas grandes con lluvia de bacon y cheddar.', '450.00', 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?q=80&w=500'),
(4, 3, 'Gaseosa 500ml', 'Línea Coca-Cola bien fría.', '300.00', 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?q=80&w=500'),
(5, 4, 'Cucurucho 2 Gustos', 'Elegí tus sabores favoritos.', '600.00', 'https://images.unsplash.com/photo-1501443762994-82bd5dace89a?q=80&w=500'),
(6, 5, 'Banana Split', 'Helado, banana, dulce de leche y crema.', '1200.00', 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?q=80&w=500');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negocio_id` (`negocio_id`);

--
-- Indices de la tabla `negocios`
--
ALTER TABLE `negocios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `negocios`
--
ALTER TABLE `negocios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
