-- =======================================================================
-- DPD Migration Script 21: Add Soft-Delete to verzorgerregel & Update Telefoonnummer
-- Geschikt voor directe invoer in phpMyAdmin en MySQL CLI
-- =======================================================================

-- -----------------------------------------------------------------------
-- 1. Voeg 'is_deleted' toe aan verzorgerregel (soft delete van zorgverleners)
-- MariaDB ondersteunt 'IF NOT EXISTS'. In standaard MySQL kan 'IF NOT EXISTS'
-- worden weggelaten als de kolom nog niet bestaat.
-- -----------------------------------------------------------------------
ALTER TABLE `verzorgerregel` ADD COLUMN IF NOT EXISTS `is_deleted` TINYINT(1) NOT NULL DEFAULT 0;

-- -----------------------------------------------------------------------
-- 2. Telefoonnummer aanpassen naar maximaal 9 karakters
-- Bestaande langere nummers eerst inkorten om #1265 (Data truncated) te voorkomen
-- -----------------------------------------------------------------------
UPDATE `client` 
SET `telefoonnummer` = LEFT(`telefoonnummer`, 9) 
WHERE CHAR_LENGTH(`telefoonnummer`) > 9;

ALTER TABLE `client` MODIFY COLUMN `telefoonnummer` VARCHAR(9) NULL;

UPDATE `medewerker` 
SET `telefoonnummer` = LEFT(`telefoonnummer`, 9) 
WHERE CHAR_LENGTH(`telefoonnummer`) > 9;

ALTER TABLE `medewerker` MODIFY COLUMN `telefoonnummer` VARCHAR(9) NULL;
