ALTER TABLE `medewerker` CHANGE `wachtwoord` `wachtwoord` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL; 
ALTER TABLE `medewerker` DROP `salt`;
ALTER TABLE `medewerker` ADD `rol` VARCHAR(50) NOT NULL AFTER `grens_assistent`;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 1;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 2;
UPDATE `medewerker` SET `rol` = 'medewerker' WHERE `medewerker`.`id` = 3;