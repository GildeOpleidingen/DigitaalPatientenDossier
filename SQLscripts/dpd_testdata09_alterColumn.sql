ALTER TABLE `patroon10stressverwerking` ADD `reactie_anders` TINYTEXT NULL DEFAULT NULL AFTER `reactie_spanningen`; 
ALTER TABLE `patroon06cognitiewaarneming` CHANGE `moete_met_zien_wat` `moeite_met_zien_wat` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `patroon06cognitiewaarneming` CHANGE `moete_met_zien` `moeite_met_zien` TINYINT(1) NOT NULL; 
ALTER TABLE `patroon10stressverwerking` CHANGE `agressiief` `agressief` TINYINT(1) NOT NULL; 