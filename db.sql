CREATE TABLE `db`.`happyornot` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rate` INT NULL,
  `timestamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));
);