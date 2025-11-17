ALTER TABLE client DROP afdeling;

ALTER TABLE `client` ADD `afdeling_id` INT NULL AFTER `foto`, ADD INDEX (`afdeling_id`);

ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`afdeling_id`) REFERENCES `afdelingen` (`id`);
