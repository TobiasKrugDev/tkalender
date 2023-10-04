-- Categories
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `description`, `color`) VALUES
(1, 'Arbeit', 'Alles rund um den Job', '#eb742f'),
(2, 'Orga', 'Alles, was organisatorisch so anfällt', '#3d948e'),
(3, 'Freizeit', 'Hobbies und Zeit mit Freunden u. Familie', '#49e659'),
(4, 'Sport', 'Regelmäßige körperliche Betätigungen', '#c42795');

-- ToDo: Images
-- Contacts
DELETE FROM `contacts`;
INSERT INTO `contacts` (`id`, `firstname`, `lastname`, `description`, `phone_number`, `email_address`, `image`) VALUES
(1, 'Lukas', 'Kaiser', '', '01234/567890', 'lukas.kaiser@test.de', ''),
(2, 'Michael', 'Ackermann', '', '12345/678901', 'michl.ackermann@test.de', ''),
(3, 'Katharina', 'Kastner', '', '23456/789012', 'k.kastner@test.de', ''),
(4, 'Hildergard', 'Schweizer', '', '34567/890123', 'schweizer.hildegard@test.de', ''),
(5, 'Tim', 'Keller', '', '45678/901234', 'timkeller96@test.de', ''),
(6, 'Sophia', 'Herz', '', '56789/012345', 'sherz@test.de', ''),
(7, 'Drechsler', 'Phillipp', '', '67890/123456', 'phillipp-drechsler@test.de', '');

-- Locations
DELETE FROM `locations`;
INSERT INTO `locations` (`id`, `name`, `description`, `street_address`, `postal_code`, `city`) VALUES
(1, 'Büro', 'Mein zweites Zuhause', 'Keßlerstraße 2', '96047', 'Bamberg'),
(2, 'Omas Haus', 'Hier ist es immer schön', 'Am Kranen 5', '96047', 'Bamberg'),
(3, 'FC Wacker 1927 Bamberg e.V.', 'Auf gehts FC Bamberg!', 'Margaretendamm 7', '96052', 'Bamberg'),
(5, 'BLOCKHELDEN Bamberg', 'Nur nicht nach unten sehen...', 'Memmelsdorfer Str. 211', '96052', 'Bamberg'),
(6, 'Odeon Kino', 'Was ist Netflix?', 'Luitpoldstraße', '96052', 'Bamberg'),
(7, 'Hallo Welt GmbH', 'Die Werkstatt meines Vetrauens', 'Gutenbergstraße 6', '96050', 'Bamberg'),
(8, 'Brose Arena', 'Eintrittskarten nicht vergessen', 'Forchheimer Str. 15', '96050', 'Bamberg');

-- Appointments
DELETE FROM `appointments`;
INSERT INTO `appointments` (`id`, `name`, `description`, `startAt`, `endAt`, `location`, `category`, `icon`) VALUES
(1, 'Weekly Developer Meeting', 'Besprechung der Projektstände', DATE_ADD(CURRENT_DATE(), INTERVAL '14' HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '16' HOUR), 1, 1, NULL),
(2, 'Kuchen essen bei Oma', 'Omas Käsekuchen ist legendär', DATE_ADD(CURRENT_DATE(), INTERVAL '-1 7' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '-1 5' DAY_HOUR), 2, 3, NULL),
(3, 'Fußball-Training', 'Auf gehts FC Bamberg!', DATE_ADD(CURRENT_DATE(), INTERVAL '1 18' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '1 20' DAY_HOUR), 3, 4, NULL),
(5, 'Bouldern', 'Nur nicht nach unten sehen...', DATE_ADD(CURRENT_DATE(), INTERVAL '3 20' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '3 22' DAY_HOUR), 5, 4, NULL),
(6, 'Ins Kino gehen', 'Was ist Netflix?', DATE_ADD(CURRENT_DATE(), INTERVAL '6 20' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '6 23' DAY_HOUR), 6, 3, NULL),
(7, 'Pitch der Projektidee', 'Der Firma meine Idee mit Tinder für Pferde pitchen', DATE_ADD(CURRENT_DATE(), INTERVAL '5 10' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '5 14' DAY_HOUR), 1, 1, NULL),
(8, 'Auto aus der Werkstatt holen', 'Hoffentlich nichts schlimmes...', DATE_ADD(CURRENT_DATE(), INTERVAL '-3 15' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '-3 13' DAY_HOUR), 7, 2, NULL),
(9, 'Launch der Website', 'Endlich kann die Website online gehen', DATE_ADD(CURRENT_DATE(), INTERVAL '9 10' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '9 12' DAY_HOUR), 1, 1, NULL),
(10, 'Konzert', 'Eintrittskarten nicht vergessen', DATE_ADD(CURRENT_DATE(), INTERVAL '14 20' DAY_HOUR), DATE_ADD(CURRENT_DATE(), INTERVAL '14 23' DAY_HOUR), 8, 3, NULL);

-- Participations
DELETE FROM `participations`;
INSERT INTO `participations` (`appointment`, `contact`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 5),
(2, 4),
(3, 7),
(3, 2),
(3, 3),
(3, 5),
(5, 2),
(5, 3),
(5, 6),
(6, 6),
(7, 1),
(7, 2),
(7, 3),
(7, 5),
(8, 7),
(9, 1),
(9, 2),
(9, 3),
(9, 5),
(10, 1),
(10, 6);

-- Users
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'demouser', '$2y$10$YE8oeXOFA8fTMrJmKYeCEuGLX66jSkb.g2ZNWhxSPEGphJSSijyoK');