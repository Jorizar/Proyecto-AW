/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
TRUNCATE TABLE `peliculas`;
TRUNCATE TABLE `usuarios`;



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

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`titulo`, `director`, `id`, `annio`, `genero`, `sinopsis`, `portada`, `reparto`, `Val_IMDb`) VALUES
('La La Land', 'Damien Chazelle', 1, '2016', 'Musical', 'Mia (Emma Stone), una joven aspirante a actriz que trabaja como camarera mientras acude a castings, y Sebastian (Ryan Gosling), un pianista de jazz que se gana la vida tocando en sórdidos tugurios, se enamoran, pero su gran ambición por llegar a la cima en sus carreras artísticas amenaza con separarlos.', './img/portadas/la_la_land.jpg', '[\r\n    {\"nombre\": \"Ryan Gosling\", \"personaje\": \"Sebastian\"},\r\n    {\"nombre\": \"Emma Stone\", \"personaje\": \"Mia\"},\r\n    {\"nombre\": \"John Legend\", \"personaje\": \"Keith\"}\r\n]', 8.0),
('La Playa de los Ahogados', 'Gerardo Herrero', 2, '2015', 'Drama', 'Una mañana, el cadáver de un marinero es arrastrado por la marea hasta la costa. Si no tuviese las manos atadas a la espalda, Justo Castelo sería otro de los hijos del mar que encontró la muerte entre las aguas mientras faenaba. Sin testigos ni pistas de la embarcación del fallecido, el lacónico inspector Leo Caldas se sumergirá en el ambiente marinero de la villa, tratando de solucionar el crimen.', './img/portadas/la_playa_de_los_ahogados.jpg', '[\r\n    {\"nombre\": \"Carmelo Gómez\", \"personaje\": \"Leo Caldas\"},\r\n    {\"nombre\": \"Antonio Garrido\", \"personaje\": \"Rafael Estévez\"},\r\n    {\"nombre\": \"Tamar Novas\", \"personaje\": \"Diego Neira\"},\r\n    {\"nombre\": \"Celso Bugallo\", \"personaje\": \"padre de Caldas\"},\r\n    {\"nombre\": \"Celia Freijeiro\", \"personaje\": \"Ana\"},\r\n    {\"nombre\": \"Marta Larralde\", \"personaje\": \"Alicia Castelo\"},\r\n    {\"nombre\": \"Luis Zahera\", \"personaje\": \"José Arias\"}\r\n]', 6.1),
('La ciudad no es para mí', 'Pedro Lazaga', 3, '1966', 'Comedia', 'Agustín Valverde, viudo y hacendado sesentón aragonés, marcha a Madrid, donde se instala en casa de su hijo, un prestigioso médico casado con una modesta costurera. A la mujer todo el mundo la llama Luchy, desde que consiguiera su brillante posición social casándose con el Dr. Valverde. Pero al llegar a Madrid Agustín descubre que en la capital hay muchos más problemas que en su pueblo. Luchy se siente atraída por el ayudante del doctor. Sara, la nieta de Agustín, vive una vida frívola y desordenada con una pandilla de amigos estúpidos. Y hasta Filo, la empleada del hogar, tiene su correspondiente complicación.', './img/portadas/la_ciudad_no_es_para_mi.jpg', '[\r\n    {\"nombre\": \"Paco Martínez Soria\", \"personaje\": \"Agustín Valverde\"},\r\n    {\"nombre\": \"Doris Coll\", \"personaje\": \"Luciana (Luchy)\"},\r\n    {\"nombre\": \"Gracita Morales\",\"personaje\": \"Filo\"},\r\n    {\"nombre\": \"Alfredo Landa\", \"personaje\": \"Genaro\"}\r\n]', 6.0),
('Unico Testigo', 'Peter Weir', 4, '1985', 'Drama', 'En su primer viaje a Philadelphia, el pequeño Samuel Lap (Lukas Haas), un niño de una comunidad amish, presencia por casualidad el brutal asesinato de un hombre. John Book (Harrison Ford) es el policía encargado de proteger al chico y a su madre Rachel de quienes quieren eliminar a Samuel, único testigo del homicidio.', './img/portadas/witness.jpg', '[\r\n    {\"nombre\": \"Harrison Ford\", \"personaje\": \"John Book\"}, \r\n    {\"nombre\": \"Kelly McGillis\", \"personaje\": \"Rachel Lapp\"}, \r\n    {\"nombre\": \"Lukas Haas\", \"personaje\": \"Samuel Lapp\"}\r\n]', 7.4),
('The Fast and the Furious', 'Rob Cohen', 5, '2001', 'Acción', 'Una misteriosa banda de delincuentes se dedica a robar camiones en marcha desde vehículos deportivos. La policía decide infiltrar un hombre en el mundo de las carreras ilegales para descubrir posibles sospechosos. El joven y apuesto Brian entra en el mundo del tunning donde conoce a Dominic, rey indiscutible de este mundo y sospechoso número uno, pero todo se complicará cuando se enamore de su hermana.', './img/portadas/the_fast_and_the_furious.jpg', '[\r\n    {\"nombre\": \"Vin Diesel\", \"personaje\": \"Dominic Toretto\"}, \r\n    {\"nombre\": \"Paul Walker\", \"personaje\": \"Brian O Conner\"}, \r\n    {\"nombre\": \"Michelle Rodriguez\", \"personaje\": \"Letty Ortiz\"},\r\n    {\"nombre\": \"Jordana Brewster\", \"personaje\": \"Mia Toretto\"}\r\n]', 6.8),
('Ocho Apellidos Vascos', 'Emilio Martínez-Lázaro', 6, '2014', 'Comedia', 'Rafa (Dani Rovira) es un joven señorito andaluz que no ha tenido que salir jamás de su Sevilla natal para conseguir lo único que le importa en la vida: el fino, la gomina, el Betis y las mujeres. Todo cambia cuando conoce una mujer que se resiste a sus encantos: es Amaia (Clara Lago), una chica vasca. Decidido a conquistarla, se traslada a un pueblo de las Vascongadas, donde se hace pasar por vasco para vencer su resistencia. Adopta el nombre de Antxon y varios apellidos vascos: Arguiñano, Igartiburu, Erentxun, Gabilondo, Urdangarín, Otegi, Zubizarreta... y Clemente.', './img/portadas/ocho_apellidos_vascos.jpg', '[\r\n    {\"nombre\": \"Dani Rovira\", \"personaje\": \"Rafa\"},\r\n    {\"nombre\": \"Clara Lago\", \"personaje\": \"Amaia\"},\r\n    {\"nombre\": \"Carmen Machi\", \"personaje\": \"Merche\"},\r\n    {\"nombre\": \"Karra Elejalde\", \"personaje\": \"Koldo\"},\r\n    {\"nombre\": \"Alberto López\", \"personaje\": \"Joquin\"},\r\n    {\"nombre\": \"Alfonso Sánchez\", \"personaje\": \"Curro\"}\r\n]', 6.5),
('Estoy hecho un Chaval', 'Pedro Lazaga', 7, '1976', 'Comedia', 'Juan Esteban, un contable de sesenta y cinco años, recibe con alegría la noticia de que va a ser padre de nuevo. Pero, cuando se dispone a pedir un aumento de sueldo en la oficina donde trabaja desde hace cuarenta años, le comunican que van a jubilarlo. Pero él, que se siente todavía joven, no se resigna y, lleno de optimismo, decide buscar otro trabajo.', './img/portadas/estoy_hecho_un_chaval.jpg', '[\r\n    {\"nombre\": \"Paco Martínez Soria\", \"personaje\": \"Juan Esteban\"},\r\n    {\"nombre\": \"Rafaela Aparicio\", \"personaje\": \"Abuela\"},\r\n    {\"nombre\": \"Antonio Ozores\", \"personaje\": \"Sr. Villancio\"}\r\n]', 5.0),
('Forrest Gump', 'Robert Zemeckis', 8, '1994', 'Drama', 'Forrest Gump (Tom Hanks) sufre desde pequeño un cierto retraso mental. A pesar de todo, gracias a su tenacidad y a su buen corazón será protagonista de acontecimientos cruciales de su país durante varias décadas. Mientras pasan por su vida multitud de cosas en su mente siempre está presente la bella Jenny (Robin Wright), su gran amor desde la infancia, que junto a su madre será la persona más importante en su vida.', './img/portadas/forrest_gump.jpg', '[\r\n    {\"nombre\": \"Tom Hanks\", \"personaje\": \"Forrest Gump\"},\r\n    {\"nombre\": \"Robin Wright\", \"personaje\": \"Jenny Curran\"},\r\n    {\"nombre\": \"Gary Sinise\", \"personaje\": \"Teniente Dan Taylor\"},\r\n    {\"nombre\": \"Sally Field\", \"personaje\": \"Sra. Gump\"},\r\n    {\"nombre\": \"Haley Joel Osment\", \"personaje\": \"Forrest Gump Jr.\"},\r\n    {\"nombre\": \"Mykelti Williamson\", \"personaje\": \"Bubba Blue\"}\r\n]', 8.8),
('La Roca', 'Michael Bay', 9, '1996', 'Acción', 'Francis Hummel pretende que se indemnice a las familias de los soldados muertos en misiones secretas. Tras robar 16 misiles equipados con gas venenoso, toma Alcatraz y amenaza con lanzarlos sobre San Francisco. Para resolver la situación, el F.B.I. envía a la isla a un especialista en armamento biológico y al único fugado de la famosa prisión.', './img/portadas/the_rock.jpg', '[\r\n    {\"nombre\": \"Sean Connery\", \"personaje\": \"John Patrick Mason\"},\r\n    {\"nombre\": \"Nicolas Cage\", \"personaje\": \"Stanley Goodspeed\"},\r\n    {\"nombre\": \"Ed Harris\", \"personaje\": \"General Francis X. Hummel\"},\r\n    {\"nombre\": \"Michael Biehn\", \"personaje\": \"Comandante Anderson\"},\r\n    {\"nombre\": \"William Forsythe\", \"personaje\": \"Sargento Frye\"},\r\n    {\"nombre\": \"David Morse\", \"personaje\": \"Mayor Tom Baxter\"}\r\n]', 7.4),
('La Ventana Indiscreta', 'Alfred Hitchcock', 10, '1954', 'Misterio', 'Un reportero fotográfico (Stewart) se ve obligado a permanecer en reposo con una pierna escayolada. A pesar de la compañía de su novia (Kelly) y de su enfermera (Ritter), procura escapar al tedio observando desde la ventana de su apartamento con unos prismáticos lo que ocurre en las viviendas de enfrente. Debido a una serie de extrañas circunstancias empieza a sospechar de un vecino cuya mujer ha desaparecido.', './img/portadas/rear_window.jpg', '[\r\n    {\"nombre\": \"James Stewart\", \"personaje\": \"L.B. Jefferies\"},\r\n    {\"nombre\": \"Grace Kelly\", \"personaje\": \"Lisa Carol Fremont\"},\r\n    {\"nombre\": \"Wendell Corey\", \"personaje\": \"Detective Thomas J. Doyle\"},\r\n    {\"nombre\": \"Thelma Ritter\", \"personaje\": \"Stella\"},\r\n    {\"nombre\": \"Raymond Burr\", \"personaje\": \"Lars Thorwald\"},\r\n    {\"nombre\": \"Judith Evelyn\", \"personaje\": \"Srta. Lonelyhearts\"}\r\n]', 8.5),
('Pobres Criaturas', 'Yorgos Lanthimos', 11, '2023', 'Drama', 'Del cineasta Yorgos Lanthimos y la productora Emma Stone llega la increíble historia y la fantástica evolución de Bella Baxter (Stone), una joven a la que el brillante y poco ortodoxo científico Dr. Godwin Baxter (Willem Dafoe) devuelve la vida. Bajo la protección de Baxter, Bella está ansiosa por aprender. Hambrienta de la mundanalidad que le falta, Bella se escapa con Duncan Wedderburn (Mark Ruffalo), un abogado astuto y libertino, en una aventura vertiginosa a través de los continentes. Libre de los prejuicios de su época, Bella se mantiene firme en su propósito de defender la igualdad y la liberación.', './img/portadas/Pobres_criaturas.jpg', '[\r\n  {\"nombre\": \"Emma Stone\", \"personaje\": \"Bella Baxter\"},\r\n   {\"nombre\": \"Willem Dafoe\", \"personaje\": \"Godwin Baxter\"},\r\n   {\"nombre\": \"Mark Ruffalo\", \"personaje\": \"Duncan Wedderburn\"},\r\n  {\"nombre\": \"Ramy Youssef\", \"personaje\": \"Max McCandles\"} \r\n ]', 7.9);

-- --------------------------------------------------------