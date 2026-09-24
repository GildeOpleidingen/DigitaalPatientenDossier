ALTER TABLE medewerker
ADD isDeleted bool;

UPDATE `medewerker` SET `isDeleted`='0' WHERE 1;

CREATE TABLE locaties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plaats VARCHAR(50) NOT NULL
);

INSERT INTO `locaties`(`plaats`) VALUES ('Venray');
INSERT INTO `locaties`(`plaats`) VALUES ('Venlo - Groenveldsingel 40');
INSERT INTO `locaties`(`plaats`) VALUES ('Venlo - Auxiliatrixweg 35');
INSERT INTO `locaties`(`plaats`) VALUES ('Roermond');
INSERT INTO `locaties`(`plaats`) VALUES ('Weert');
INSERT INTO `locaties`(`plaats`) VALUES ('Geleen');
INSERT INTO `locaties`(`plaats`) VALUES ('Sittard');

CREATE TABLE klassen (
    klasID INT AUTO_INCREMENT PRIMARY KEY,
    locatieID INT NOT NULL,
    klasCode VARCHAR(50) NOT NULL,
    FOREIGN KEY (locatieID) REFERENCES locaties(id)
);

CREATE TABLE medewerkerKlas (
    medewerkerID INT NOT NULL,
    klasID INT NOT NULL,

    PRIMARY KEY (medewerkerID, klasID),

    FOREIGN KEY (medewerkerID) REFERENCES medewerker(id),
    FOREIGN KEY (klasID) REFERENCES klassen(klasID)
);

ALTER TABLE `medewerker` ADD UNIQUE (`email`);