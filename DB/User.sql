CREATE TABLE `User` (
  `UserId` INT NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(100) NOT NULL,
  `Password` VARCHAR(100) NOT NULL,
  `Name` VARCHAR(100) NOT NULL,
  `DOB` DATETIME NOT NULL,
  `CreatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CreatedBy` INT NOT NULL DEFAULT 0,
  `ModifiedOn` DATETIME NULL DEFAULT NULL,
  `ModifiedBy` INT NULL DEFAULT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE INDEX `Username_UNIQUE` (`Username` ASC);

CREATE VIEW `vwUserList` AS
    SELECT 
        `User`.`UserId` AS `UserId`,
        `User`.`Username` AS `Username`,
        `User`.`Name` AS `Name`,
        `User`.`DOB` AS `DOB`,
        `User`.`CreatedOn` AS `CreatedOn`,
        `User`.`CreatedBy` AS `CreatedBy`,
        `User`.`ModifiedOn` AS `ModifiedOn`,
        `User`.`ModifiedBy` AS `ModifiedBy`
    FROM
        `User`
