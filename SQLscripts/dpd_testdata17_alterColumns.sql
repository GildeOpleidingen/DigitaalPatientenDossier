ALTER TABLE `patroon06cognitiewaarneming`
ADD COLUMN ziet_dingen TINYINT(1) NOT NULL,
    ADD COLUMN ziet_dingen_wat TEXT DEFAULT NULL;
ADD COLUMN gebruikt_middelen_softdrugs_welke TINYTEXT,
    ADD COLUMN gebruikt_middelen_harddrugs_welke TINYTEXT,
    ADD COLUMN gebruikt_middelen_alcohol_welke TINYTEXT,
    ADD COLUMN gebruikt_middelen_anders_welke TINYTEXT;