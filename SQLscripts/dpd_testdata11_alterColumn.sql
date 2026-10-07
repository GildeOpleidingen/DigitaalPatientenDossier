ALTER TABLE `medewerker` CHANGE `wachtwoord` `wachtwoord` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL; 
ALTER TABLE `medewerker` ADD `grens_assistent` VARCHAR(50) NOT NULL AFTER `wachtwoord`;
ALTER TABLE `medewerker` ADD `rol` VARCHAR(50) NOT NULL AFTER `grens_assistent`;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 1;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 2;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 3;


INSERT INTO `medewerker` (`id`, `naam`, `klas`, `foto`, `email`, `telefoonnummer`, `wachtwoord`, `grens_assistent`, `rol`) VALUES
(6, 'Fem Angenheister', 'ZP41AVP00H5YH', NULL, 'Fem.Angenheister@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(7, 'Lynn van der Burgt', 'ZP41AVP00H5YH', NULL, 'Lynn.van.der.Burgt@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(8, 'Bente Frederix', 'ZP41AVP00H5YH', NULL, 'Bente.Frederix@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(9, 'Lisa de Graaf', 'ZP41AVP00H5YH', NULL, 'Lisa.de.Graaf@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(10, 'Lynn van Helden', 'ZP41AVP00H5YH', NULL, 'Lynn.van.Helden@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(11, 'Maud Jenniskens', 'ZP41AVP00H5YH', NULL, 'Maud.Jenniskens@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(12, 'Jolijn Kessels', 'ZP41AVP00H5YH', NULL, 'Jolijn.Kessels@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(13, 'Jill Lemmen', 'ZP41AVP00H5YH', NULL, 'Jill.Lemmen@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(14, 'Bregje Nabuurs', 'ZP41AVP00H5YH', NULL, 'Bregje.Nabuurs@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(15, 'Lynn Sijbers', 'ZP41AVP00H5YH', NULL, 'Lynn.Sijbers@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(16, 'Vera Wijnen', 'ZP41AVP00H5YH', NULL, 'Vera.Wijnen@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(17, 'Daphne Willems', 'ZP41AVP00H5YH', NULL, 'Daphne.Willems@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(18, 'Lara van den Berg', 'ZP41BVP00H5YH', NULL, 'Lara.van.den.Berg@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(19, 'Emma Geurts', 'ZP41BVP00H5YH', NULL, 'Emma.Geurts@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(20, 'Sylke Geurts', 'ZP41BVP00H5YH', NULL, 'Sylke.Geurts@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(21, 'Bas Janssen2', 'ZP41BVP00H5YH', NULL, 'Bas.Janssen2@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(22, 'Silke Konisser', 'ZP41BVP00H5YH', NULL, 'Silke.Konisser@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(23, 'Pleun Kreutz', 'ZP41BVP00H5YH', NULL, 'Pleun.Kreutz@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(24, 'Aukje van de Logt', 'ZP41BVP00H5YH', NULL, 'Aukje.van.de.Logt@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(25, 'Camille Martens', 'ZP41BVP00H5YH', NULL, 'Camille.Martens@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(26, 'Floor Naus', 'ZP41BVP00H5YH', NULL, 'Floor.Naus@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(27, 'Fenna Poulissen', 'ZP41BVP00H5YH', NULL, 'Fenna.Poulissen@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(28, 'Famke Bindels', 'ZP41BVP00H5YH', NULL, 'Famke.Bindels@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(29, 'Nanouk Schoeber', 'ZP41BVP00H5YH', NULL, 'Nanouk.Schoeber@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(30, 'Meike Volleberg', 'ZP41BVP00H5YH', NULL, 'Meike.Volleberg@student.gildeopleidingen.nl', '', '$2y$10$GeyYblF4Oa1Fy.lEwWJbZuAoCWr7KcFbcidhib51p/EhK0HoIu6Im', 0, 'medewerker'),
(31, 'Eline Baartz', '', NULL, 'e.baartz@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(32, 'Jim Becks', '', NULL, 'j.becks@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(33, 'Kylie Berens', '', NULL, 'k.berens@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(34, 'Evelien Derks', '', NULL, 'e.vdputten@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(35, 'Ramon Ghielen', '', NULL, 'r.ghielen@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(36, 'Jude Hendrikse', '', NULL, 'j.hendrikse@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(37, 'Elly Janssen', '', NULL, 'e.janssen1@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(38, 'Mieke Janssen', '', NULL, 'm.janssen1@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(39, 'Carin Krebben', '', NULL, 'c.custers@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(40, 'Hannelore Lutgens', '', NULL, 'h.lutgens@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(41, 'Sanne Martens', '', NULL, 's.martens@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(42, 'Joris Nabben', '', NULL, 'j.nabben@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(43, 'Anita Reumer', '', NULL, 'a.oomen@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(44, 'Raymond Rongen', '', NULL, 'r.rongen@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(45, 'Mayke Smeets', '', NULL, 'm.smeets1@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(46, 'Lisanne Verheijen', '', NULL, 'l.verheijen@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder'),
(47, 'Marcha Welbers', '', NULL, 'm.welbers@rocgilde.nl', NULL, '$2y$10$u3VimbBZ9lHkLjlgyCm/CO7Iaf9Pvaw6.eOT3OpiJgzdgpPk.jKiy', 0, 'beheerder');