ALTER TABLE `verzorgerregel` ADD toegang BOOLEAN  NOT NULL;
ALTER TABLE `medewerker` ADD `grens_assistent` BOOLEAN  NOT NULL;
ALTER TABLE `medewerker` ADD `salt` VARCHAR(250) NOT NULL AFTER `wachtwoord`;

CREATE TABLE `gebeurtenis` (`id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL , `toegediend` DATETIME NOT NULL , `ingepland` DATETIME NOT NULL , PRIMARY KEY (`id`));
ADD CONSTRAINT gebeurtenis_client_id_fk FOREIGN KEY (client_id) REFERENCES client (id);
ALTER TABLE `gebeurtenis` ADD `medicijn` VARCHAR(200) NOT NULL AFTER `ingepland`;
