DROP TABLE IF EXISTS `SYS_ORG`;
CREATE TABLE `SYS_ORG` (
  `CODE` VARCHAR(60) NOT NULL,
  `NAME` VARCHAR(100) NOT NULL,
  `PARENT` VARCHAR(160) NOT NULL,
  `TEL` VARCHAR(255) NULL,
  `FAX` VARCHAR(255) NULL,
  `ADDR` VARCHAR(255) NULL,
  `ZIPCODE` VARCHAR(255) NULL,
  `NOTE` TEXT,
  `CREATOR` VARCHAR(160) NOT NULL,
  `CREATETIME` DATETIME NOT NULL DEFAULT NOW(),
  `LASTUPD` VARCHAR(160) NOT NULL,
  `LASTUPDTIME` DATETIME NOT NULL DEFAULT NOW(),
  `ORDERNO` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`CODE`), UNIQUE (`NAME`, `PARENT`)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;
