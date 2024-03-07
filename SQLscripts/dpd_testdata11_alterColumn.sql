ALTER TABLE `medewerker` CHANGE `wachtwoord` `wachtwoord` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL; 
ALTER TABLE `medewerker` DROP `salt`;