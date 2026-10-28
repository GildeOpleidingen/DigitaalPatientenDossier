ALTER TABLE `patroon04activiteiten` CHANGE `vermoeitheids_klachten` `vermoeidheids_klachten` TINYINT(1) NOT NULL; 

ALTER TABLE `patroon10stressverwerking` ADD `reactie_anders` TINYTEXT NULL DEFAULT NULL AFTER `reactie_spanningen`; 
ALTER TABLE `patroon10stressverwerking` CHANGE `agressiief` `agressief` TINYINT(1) NOT NULL; 

ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek` `angstig_paniek` TINYINT(1) NOT NULL; 
ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek_actie` `angstig_paniek_actie` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL; 
ALTER TABLE `patroon10stressverwerking` CHANGE `angsig_paniek_lukt_voorkomen` `angstig_paniek_lukt_voorkomen` TINYINT(1) NOT NULL; 

ALTER TABLE `patroon07zelfbeleving` ADD `ervaring_voorheen` TINYINT NOT NULL AFTER `verandering_denkpatroon`; 