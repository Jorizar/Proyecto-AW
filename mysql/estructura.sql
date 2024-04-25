/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
DROP TABLE IF EXISTS `comentarios`;
DROP TABLE IF EXISTS `favoritos`;
DROP TABLE IF EXISTS `listas`;
DROP TABLE IF EXISTS `noticias`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `peliculas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE IF NOT EXISTS `comentarios` (
  `comentario_id` int(3) NOT NULL,
  `user_id` int(2) UNSIGNED NOT NULL,
  `pelicula_id` int(2) UNSIGNED NOT NULL,
  `texto` text NOT NULL,
  `valoracion` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int(2) UNSIGNED NOT NULL,
  `user_id` int(2) UNSIGNED NOT NULL,
  `pelicula_id` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `listas`
--

CREATE TABLE IF NOT EXISTS `listas` (
  `user_id` int(2) UNSIGNED NOT NULL,
  `pelicula_id` int(2) UNSIGNED NOT NULL,
  `lista_id` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE IF NOT EXISTS `noticias` (
  `titulo` varchar(1000) NOT NULL,
  `post_id` int(2) UNSIGNED NOT NULL,
  `portada` varchar(100) DEFAULT NULL,
  `texto` text NOT NULL,
  `autor` varchar(40) NOT NULL,
  `fecha` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `titulo` varchar(60) NOT NULL,
  `director` varchar(35) NOT NULL,
  `id` int(2) UNSIGNED NOT NULL,
  `annio` year(4) NOT NULL,
  `genero` int(2) UNSIGNED NOT NULL,
  `sinopsis` text NOT NULL,
  `portada` varchar(100) NOT NULL,
  `reparto` longtext NOT NULL,
  `Val_IMDb` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `usuarios`
--

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(2) UNSIGNED NOT NULL,
  `genero` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `usuarios` (
  `username` varchar(20) NOT NULL,
  `password` varchar(80) NOT NULL,
  `user_id` int(2) UNSIGNED NOT NULL,
  `rol` varchar(20) NOT NULL,
  `email` varchar(35) DEFAULT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Estructura de tabla para la tabla `reseñas`
--
CREATE TABLE IF NOT EXISTS `reseñas` (
  `reseña_id` int(3) UNSIGNED NOT NULL,
  `user_id` int(2) UNSIGNED NOT NULL,
  `pelicula_id` int(2) UNSIGNED NOT NULL,
  `texto` text NOT NULL,
  `valoracion` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

  ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`comentario_id`);

    ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`reseña_id`);

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `user_id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

ALTER TABLE `favoritos`
  MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

ALTER TABLE `comentarios`
  MODIFY `comentario_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;

ALTER TABLE `reseñas`
  MODIFY `reseña_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;
