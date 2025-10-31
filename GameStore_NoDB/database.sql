-- Base de datos: `gamestore_db`
--

CREATE DATABASE IF NOT EXISTS `gamestore_db`;
USE `gamestore_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor','cliente') NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(1, 'Administrador', 'admin@gmail.com', '$2y$10$w..E4l5o0.1/9.z49.CSs.p8/.wIdprYk8/3d2.i9.5caEGJjO3e.', 'admin'),
(2, 'Vendedor Uno', 'vendedor@gamestore.com', '$2y$10$3cJfLi8538.O6E.9a/C2Iu5.2v2.Y3A5U5.3l.0k.Xz7.P/9j.1S.', 'vendedor'),
(3, 'Cliente Uno', 'cliente@gamestore.com', '$2y$10$6C.E1m.2k/8e/d.6f.Y0p.Z5v.T3v.Y3k/6k/5n.U4j/7s.F1h/1.', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id_juego` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `genero` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `rating` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_juego`),
  KEY `id_vendedor` (`id_vendedor`),
  CONSTRAINT `juegos_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`titulo`, `genero`, `precio`, `stock`, `id_vendedor`, `rating`, `imagen`) VALUES
('The Witcher 3', 'RPG', 39.99, 50, 2, 5, 'imgtw3.jpg'),
('Cyberpunk 2077', 'RPG', 49.99, 30, 2, 4, 'imgCyberpunk.jpg'),
('Red Dead Redemption 2', 'Action', 59.99, 40, 2, 5, 'imgrdr2.jpg'),
('Hades', 'Roguelike', 24.99, 100, 2, 5, 'imghades.jpg'),
('Elden Ring', 'Action RPG', 59.99, 60, 2, 5, 'imgeldenring.jpg'),
('Baldur\'s Gate 3', 'RPG', 59.99, 70, 2, 5, 'imgbg3.jpg'),
('GTA V', 'Action', 29.99, 200, 2, 5, 'imgGTA5.jpg'),
('Zelda: Breath of the Wild', 'Action-Adventure', 59.99, 80, 1, 5, 'imgzelda.jpg'),
('Stardew Valley', 'Simulation', 14.99, 150, 2, 5, 'imgstardew.jpg'),
('Hollow Knight', 'Metroidvania', 14.99, 120, 2, 5, 'imghollow.jpg'),
('God of War (2018)', 'Action-Adventure', 39.99, 90, 1, 5, 'imggow.jpg'),
('Minecraft', 'Sandbox', 26.95, 500, 2, 5, 'imgminecraft.jpg'),
('Among Us', 'Social', 4.99, 300, 2, 4, 'imgamongus.jpg'),
('Fortnite', 'Battle Royale', 0.00, 1000, 1, 4, 'imgfortnite.jpg'),
('Valorant', 'FPS', 0.00, 1000, 1, 4, 'imgvalorant.jpg'),
('League of Legends', 'MOBA', 0.00, 1000, 1, 3, 'imglol.jpg'),
('Overwatch 2', 'FPS', 0.00, 1000, 2, 4, 'imgow2.jpg'),
('Apex Legends', 'Battle Royale', 0.00, 1000, 2, 4, 'imgapex.jpg'),
('DOOM Eternal', 'FPS', 39.99, 70, 1, 5, 'imgdoom.jpg'),
('Final Fantasy VII Remake', 'RPG', 59.99, 60, 2, 4, 'imgff7r.jpg'),
('Persona 5 Royal', 'RPG', 59.99, 50, 2, 5, 'imgp5r.jpg'),
('Ghost of Tsushima', 'Action-Adventure', 49.99, 80, 1, 5, 'imggot.jpg'),
('Marvel\'s Spider-Man', 'Action-Adventure', 39.99, 100, 2, 5, 'imgspiderman.jpg'),
('Bloodborne', 'Action RPG', 19.99, 60, 1, 5, 'imgbloodborne.jpg'),
('Sekiro: Shadows Die Twice', 'Action-Adventure', 59.99, 50, 2, 5, 'imgsekiro.jpg'),
('Dark Souls III', 'Action RPG', 39.99, 70, 1, 5, 'imgds3.jpg'),
('Celeste', 'Platformer', 19.99, 90, 2, 5, 'imgceleste.jpg'),
('Subnautica', 'Survival', 29.99, 80, 2, 5, 'imgsubnautica.jpg'),
('Skyrim', 'RPG', 39.99, 250, 1, 5, 'imgskyrim.jpg'),
('Portal 2', 'Puzzle', 9.99, 300, 2, 5, 'imgportal2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id_transaccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_transaccion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_transacciones`
--

CREATE TABLE `detalle_transacciones` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaccion` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `id_transaccion` (`id_transaccion`),
  KEY `id_juego` (`id_juego`),
  CONSTRAINT `detalle_transacciones_ibfk_1` FOREIGN KEY (`id_transaccion`) REFERENCES `transacciones` (`id_transaccion`),
  CONSTRAINT `detalle_transacciones_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id_juego`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
