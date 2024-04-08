/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
TRUNCATE TABLE `RolesUsuario`;
TRUNCATE TABLE `Roles`;
TRUNCATE TABLE `Usuarios`;

INSERT INTO `Roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');


INSERT INTO `RolesUsuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 2);

/*
  user: userpass
  admin: adminpass
*/
INSERT INTO `usuarios` (`username`, `password`, `user_id`, `rol`, `email`, `foto`) VALUES
('user', 'user', 1, 'premium', 'user@gmail.com', './img/fotosPerfil/2.png'),
('admin', 'admin', 2, 'admin', 'admin@gmail.com', './img/fotosPerfil/admin.png'),
('critico', 'critico', 3, 'critico', 'critico@gmail.com', './img/fotosPerfil/1.png'),
('free', 'user2', 4, 'premium', 'user2@gmail.com', './img/fotosPerfil/1.png'),
('premium', 'premium', 5, 'premium', 'premium@gmail.com', './img/fotosPerfil/1.png'),
('quentin_tarantino', 'malditosBastardos', 7, 'director', 'quentin@gmail.com', './img/fotosPerfil/quentin.png'),
('bradpitt', 'bradpittcontra', 8, 'actor', 'bradpitt@gmail.com', './img/fotosPerfil/brad.png');

