DROP TABLE IF EXISTS `SYS_ROLE`;
CREATE TABLE `SYS_ROLE` (
  `CODE` VARCHAR(60) NOT NULL,
  `NAME` VARCHAR(100) NOT NULL,
  `ORG` VARCHAR(160) NOT NULL,
  `NOTE` TEXT,
  `CREATOR` VARCHAR(160) NOT NULL,
  `CREATETIME` DATETIME NOT NULL DEFAULT NOW(),
  `LASTUPD` VARCHAR(160) NOT NULL,
  `LASTUPDTIME` DATETIME NOT NULL DEFAULT NOW(),
  `ORDERNO` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`CODE`), UNIQUE (`NAME`, `ORG`)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;
