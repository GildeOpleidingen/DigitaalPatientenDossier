-- =======================================================================
-- DPD Migration Script 21: Add Soft-Delete to verzorgerregel & Update Telefoonnummer
-- (Idempotent & Safe for any MySQL / MariaDB version in phpMyAdmin)
-- =======================================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_add_is_deleted_to_verzorgerregel`$$
CREATE PROCEDURE `sp_add_is_deleted_to_verzorgerregel`()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'verzorgerregel'
          AND COLUMN_NAME = 'is_deleted'
    ) THEN
        ALTER TABLE `verzorgerregel` ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0;
    ELSE
        -- Ensure all existing records are 0 if NULL
        UPDATE `verzorgerregel` SET `is_deleted` = 0 WHERE `is_deleted` IS NULL;
        ALTER TABLE `verzorgerregel` MODIFY COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0;
    END IF;
END$$

DELIMITER ;

CALL sp_add_is_deleted_to_verzorgerregel();
DROP PROCEDURE IF EXISTS `sp_add_is_deleted_to_verzorgerregel`;

-- -----------------------------------------------------------------------
-- Telefoonnummer aanpassen naar maximaal 9 karakters
-- Eerst bestaande langere nummers inkorten zodat MySQL niet struikelt over data-afkapping (#1265)
-- -----------------------------------------------------------------------
UPDATE `client` 
SET `telefoonnummer` = LEFT(`telefoonnummer`, 9) 
WHERE CHAR_LENGTH(`telefoonnummer`) > 9;

ALTER TABLE `client` MODIFY COLUMN `telefoonnummer` VARCHAR(9) NULL;

UPDATE `medewerker` 
SET `telefoonnummer` = LEFT(`telefoonnummer`, 9) 
WHERE CHAR_LENGTH(`telefoonnummer`) > 9;

ALTER TABLE `medewerker` MODIFY COLUMN `telefoonnummer` VARCHAR(9) NULL;