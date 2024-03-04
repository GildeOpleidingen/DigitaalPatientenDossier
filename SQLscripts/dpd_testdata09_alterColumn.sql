ALTER TABLE `patroon04activiteiten` CHANGE `vermoeitheids_klachten` `vermoeidheids_klachten` TINYINT(1) NOT NULL; 

ALTER TABLE `patroon10stressverwerking` ADD `reactie_anders` TINYTEXT NULL DEFAULT NULL AFTER `reactie_spanningen`; 
ALTER TABLE `patroon10stressverwerking` CHANGE `agressiief` `agressief` TINYINT(1) NOT NULL; 

ALTER TABLE `patroon06cognitiewaarneming` ADD `gebruikt_middelen_softdrugs` TINYINT NOT NULL AFTER `gebruikt_middelen`; 
ALTER TABLE `patroon06cognitiewaarneming` ADD `gebruikt_middelen_harddrugs` TINYINT NOT NULL AFTER `gebruikt_middelen_softdrugs_welke`; 
ALTER TABLE `patroon06cognitiewaarneming` ADD `gebruikt_middelen_alcohol` TINYINT NOT NULL AFTER `gebruikt_middelen_harddrugs_welke`; 
ALTER TABLE `patroon06cognitiewaarneming` ADD `gebruikt_middelen_anders` TINYINT NOT NULL AFTER `gebruikt_middelen_alcohol_welke`; 
ALTER TABLE `patroon06cognitiewaarneming` ADD `ziet_dingen` TINYINT NOT NULL AFTER `hoort_stemmen_wat`, ADD `ziet_dingen_wat` TINYTEXT NULL DEFAULT NULL AFTER `ziet_dingen`; 
ALTER TABLE `patroon06cognitiewaarneming` CHANGE `moete_met_zien` `moeite_met_zien` TINYINT(1) NOT NULL; 
ALTER TABLE `patroon06cognitiewaarneming` CHANGE `moete_met_zien_wat` `moeite_met_zien_wat` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `patroon06cognitiewaarneming` CHANGE `gebruikt_middelen_softdrugs` `gebruikt_middelen_softdrugs_welke` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE `gebruikt_middelen_harddrugs` `gebruikt_middelen_harddrugs_welke` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE `gebruikt_middelen_alcohol` `gebruikt_middelen_alcohol_welke` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL, CHANGE `gebruikt_middelen_anders` `gebruikt_middelen_anders_welke` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 

ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek` `angstig_paniek` TINYINT(1) NOT NULL; 
ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek_actie` `angstig_paniek_actie` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek_lukt_voorkomen` `angstig_paniek_lukt_voorkomen` TINYINT(1) NOT NULL; 