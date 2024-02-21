CREATE TABLE `dpd`.`pijnkaart` ( `pijnindex` INT NOT NULL , `pijnomschrijving` VARCHAR(60) NOT NULL , PRIMARY KEY (`pijnindex`));

INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (0, 'Nergens last van');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (1, 'Iets gevoelig, geen beperkingen in de activiteiten');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (2, 'Gevoelig, lichte beperking in de activiteiten');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (3, 'Toch wel pijnlijk maar als ik stil lig gaat het wel');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (4, 'Matig pijnlijk, ook in rust');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (5, 'Pijnlijk maar ik slaap snachts wel door');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (6, 'Pijnlijk, ik word vaak wakker van de pijn');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (7, 'Erg pijnlijk, ik slaap helemaal niet');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (8, 'Erg pijnlijk, ik kan aan niets anders meer denken');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (9, 'Zeer pijnlijk, doe iets!');
INSERT INTO `pijnkaart`(`pijnindex`, `pijnomschrijving`) VALUES (10, 'Zeer zeer pijnlijk, niet meer te doen!!!');


ALTER TABLE `meting` ADD INDEX(`pijn`);

ALTER TABLE `meting`
  ADD CONSTRAINT `meting_constraint` FOREIGN KEY (`pijn`) REFERENCES `pijnkaart` (`pijnindex`) ON DELETE NO ACTION ON UPDATE NO ACTION;