-- =======================================================================
-- DPD Migration Script 20: Allow NULL on Checkboxes & Standardize Soft-Delete
-- (Idempotent & Safe for any MySQL / MariaDB version in phpMyAdmin)
-- =======================================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_safe_modify_column`$$
CREATE PROCEDURE `sp_safe_modify_column`(
    IN in_table VARCHAR(64),
    IN in_column VARCHAR(64),
    IN in_type VARCHAR(100)
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = in_table
          AND COLUMN_NAME = in_column
    ) THEN
        SET @sql := CONCAT('ALTER TABLE `', in_table, '` MODIFY COLUMN `', in_column, '` ', in_type);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_safe_rename_or_add_column`$$
CREATE PROCEDURE `sp_safe_rename_or_add_column`(
    IN in_table VARCHAR(64),
    IN in_old_column VARCHAR(64),
    IN in_new_column VARCHAR(64),
    IN in_type VARCHAR(100)
)
BEGIN
    SET @new_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = in_table AND COLUMN_NAME = in_new_column);
    SET @old_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = in_table AND COLUMN_NAME = in_old_column);

    IF @new_exists > 0 THEN
        -- Replace any existing NULLs with 0 first to prevent Error #1138
        SET @sql := CONCAT('UPDATE `', in_table, '` SET `', in_new_column, '` = 0 WHERE `', in_new_column, '` IS NULL');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql := CONCAT('ALTER TABLE `', in_table, '` MODIFY COLUMN `', in_new_column, '` ', in_type);
    ELSEIF @old_exists > 0 THEN
        -- Replace any existing NULLs with 0 first to prevent Error #1138
        SET @sql := CONCAT('UPDATE `', in_table, '` SET `', in_old_column, '` = 0 WHERE `', in_old_column, '` IS NULL');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql := CONCAT('ALTER TABLE `', in_table, '` CHANGE COLUMN `', in_old_column, '` `', in_new_column, '` ', in_type);
    ELSE
        SET @sql := CONCAT('ALTER TABLE `', in_table, '` ADD COLUMN `', in_new_column, '` ', in_type);
    END IF;

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- -----------------------------------------------------------------------
-- 1. Soft-delete kolom standaardiseren naar `is_deleted`
-- -----------------------------------------------------------------------
CALL sp_safe_rename_or_add_column('client', 'deleted', 'is_deleted', 'TINYINT(1) NOT NULL DEFAULT 0');
CALL sp_safe_rename_or_add_column('medewerker', 'isDeleted', 'is_deleted', 'TINYINT(1) NOT NULL DEFAULT 0');

-- -----------------------------------------------------------------------
-- 2. Alle velden in alle 11 patronen nullable maken (NULL DEFAULT NULL)
-- -----------------------------------------------------------------------

-- patroon01gezondheidsbeleving
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'algemene_gezondheid', 'text NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'gezondheids_bezigheid', 'text NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'rookt', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'rookt_hoeveelheid', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'drinkt', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'drinkt_hoeveelheid', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'besmettelijke_aandoening', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'besmettelijke_aandoening_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'alergieen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'alergieen_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oorzaak_huidige_toestand', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oht_actie', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oht_hoe_effectief', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oht_wat_nodig', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oht_wat_belangrijk', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'oht_reactie_op_advies', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'preventie', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon01gezondheidsbeleving', 'observatie', 'varchar(10) NULL DEFAULT NULL');

-- patroon02voedingstofwisseling
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'eetlust', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'dieet', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'dieet_welk', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'gewicht_verandert', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'moeilijk_slikken', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'gebitsproblemen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'gebitsprothese', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'huidproblemen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'gevoel', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon02voedingstofwisseling', 'observatie', 'varchar(6) NULL DEFAULT NULL');

-- patroon03uitscheiding
CALL sp_safe_modify_column('patroon03uitscheiding', 'ontlasting_probleem', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'op_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'op_preventie', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'op_medicijnen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'op_medicijnen_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'urineer_probleem', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'up_incontinentie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'up_incontinentie_behandeling', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'up_incontinentie_behandeling_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'transpiratie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'transpiratie_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon03uitscheiding', 'observatie', 'VARCHAR(10) NULL DEFAULT NULL');

-- patroon04activiteiten
CALL sp_safe_modify_column('patroon04activiteiten', 'voeding', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'aankleden', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'alg_mobiliteit', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'koken', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'huishouden', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'financien', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'verzorging', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'baden', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'toiletgang', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'uit_bed_komen', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'winkelen', 'int(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'tijd_voor_uzelf_nodig', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'tijd_voor_uzelf_nodig_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'dagelijkse_activiteiten', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'dagelijkse_gewoontes', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'dagelijkse_gewoontes_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'lichamelijke_beperking', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'lichamelijke_beperking_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'vermoeitheids_klachten', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'passiever', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'passiever_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'problemen_starten_dag', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'problemen_starten_dag_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'hobbys', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'hobbys_bestedingstijd', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'activiteiten_weggevallen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'activiteiten_weggevallen_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'observatie', 'varchar(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon04activiteiten', 'vermoeidheids_klachten', 'TINYINT(1) NULL DEFAULT NULL');

-- patroon05slaaprust
CALL sp_safe_modify_column('patroon05slaaprust', 'verandering_inslaaptijd', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'verandering_inslaaptijd_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'verandering_kwaliteit_slapen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'verandering_kwaliteit_slapen_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'gebruik_inslaapmiddel', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'gebruik_inslaapmiddel_welke', 'varchar(6) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'gebruik_inslaapmiddel_anders', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'slaapduur', 'float NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'uitgerust_wakker', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'dromen_nachtmerries', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'rustperiodes_overdag', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'gemakkelijk_ontspannen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon05slaaprust', 'observatie', 'varchar(1) NULL DEFAULT NULL');

-- patroon06cognitiewaarneming
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moeilijk_horen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'hoort_stemmen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'hoort_stemmen_wat', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moete_met_zien', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moete_met_zien_wat', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'ruikt_iets_onverklaarbaar', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'ruikt_iets_onverklaarbaar_wat', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'verandering_denken', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moeite_spreken', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'taal_thuis', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'verandering_concentratievermogen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moeilijker_beslissen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'verandering_geheugen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'verandering_orientatie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'invloed_medicatie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'invloed_medicatie_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_softdrugs', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_harddrugs', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_alcohol', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_anders', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'pijnklachten', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'pijnklachten_waar_wanneer_soort', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'pijnklachten_tegengaan_pijn', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'pijnklachten_preventie', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'observatie', 'varchar(12) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moeite_met_zien', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'moeite_met_zien_wat', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'ziet_dingen', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'ziet_dingen_wat', 'TEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_softdrugs_welke', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_harddrugs_welke', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_alcohol_welke', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon06cognitiewaarneming', 'gebruikt_middelen_anders_welke', 'TINYTEXT NULL DEFAULT NULL');

-- patroon07zelfbeleving
CALL sp_safe_modify_column('patroon07zelfbeleving', 'zelfbeschrijving', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'opkomen_voor_uzelf', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'wel_niet_opkomen_blijktuit', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'verandering_stemming', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'verandering_stemming_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'gevoel_op_dit_moment', 'varchar(11) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'gevoel_op_dit_moment_anders', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'verandering_concentratie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'verandering_denkpatroon', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'verandering_uiterlijk', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'sensaties', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'sensaties_welk_gevoel', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'gevoel_momenteel', 'varchar(3) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'lichamelijke_energie', 'varchar(3) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'zelfverzorging', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'observatie', 'VARCHAR(19) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon07zelfbeleving', 'ervaring_voorheen', 'TINYINT(1) NULL DEFAULT NULL');

-- patroon08rollenrelatie
CALL sp_safe_modify_column('patroon08rollenrelatie', 'getrouwd_samenwonend', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'kinderen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'tevreden_thuissituatie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'steun_vrienden_familie', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'inkomstenbron', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_fin_sit_vroeger', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_fin_sit_vroeger_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_fin_sit_toekomst', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_fin_sit_toekomst_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'opleiding', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_sociale_contacten', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verandering_sociale_contacten_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'groot_gezin', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'plaats_in_gezin', 'varchar(15) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'onderlinge_contacten_gezin', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'agressie_gezin', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verenigingslid', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'vereniging_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'contact_met_derden', 'varchar(50) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verlies_geleden', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'verlies_geleden_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon08rollenrelatie', 'observatie', 'varchar(17) NULL DEFAULT NULL');

-- patroon09seksualiteitvoorplanting
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'verandering_seksuele_beleving', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'verandering_seksuele_beleving_door', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'verandering_seksueel_gedrag', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'wisselende_contacten', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'veilig_vrijen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'anticonceptiemiddel', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'anticonceptiemiddel_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'anticonceptiemiddel_problemen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'seksuele_gerichtheid', 'varchar(3) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'seksuele_gerichtheid_problemen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'soa', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'soa_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon09seksualiteitvoorplanting', 'observatie', 'varchar(4) NULL DEFAULT NULL');

-- patroon10stressverwerking
CALL sp_safe_modify_column('patroon10stressverwerking', 'reactie_spanningen', 'varchar(13) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'spanningsvolle_situaties_voorkomen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'spanningsvolle_situaties_voorkomen_hoe', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'spanningsvolle_situaties_oplossen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'spanningsvolle_situaties_oplossen_hoe', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'omstandigheden_in_war_raken', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'omstandigheden_in_war_raken_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angsig_paniek', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angsig_paniek_actie', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angsig_paniek_lukt_voorkomen', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'suicidaal', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'suicidaal_momenteel', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'agressiief', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'anderen_iets_aan_willen_doen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'maatregelen_veiligheid', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'maatregelen_veiligheid_door', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'moeite_uiten_gevoelens', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'bespreken_gevoelens_met', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'observatie', 'varchar(10) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'reactie_anders', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'agressief', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angstig_paniek', 'TINYINT(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angstig_paniek_actie', 'TINYTEXT NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon10stressverwerking', 'angstig_paniek_lukt_voorkomen', 'TINYINT(1) NULL DEFAULT NULL');

-- patroon11waardelevensovertuiging
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gelovig', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'geloof_welk', 'varchar(6) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'geloof_anders', 'varchar(25) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'behoefte_religieuze_activiteit', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_tav_geloofsovertuiging', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_tav_geloofsovertuiging_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_tav_geloofsovertuiging_wanneer', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'overeenkomst_waarden_normen', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'etnische_achtergrond', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_mbt_etnische_achtergrond', 'tinyint(1) NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_mbt_etnische_achtergrond_welke', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'gebruiken_mbt_etnische_achtergrond_wanneer', 'tinytext NULL DEFAULT NULL');
CALL sp_safe_modify_column('patroon11waardelevensovertuiging', 'observatie', 'varchar(4) NULL DEFAULT NULL');

-- -----------------------------------------------------------------------
-- 3. Tijdelijke procedures opruimen
-- -----------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sp_safe_modify_column`;
DROP PROCEDURE IF EXISTS `sp_safe_rename_or_add_column`;
