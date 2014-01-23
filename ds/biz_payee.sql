DROP TABLE IF EXISTS `BIZ_PAYEE`;
CREATE TABLE `BIZ_PAYEE` (
  `CODE` VARCHAR(60) NOT NULL,
  `NAME` VARCHAR(100) NOT NULL,
  `ORG` VARCHAR(160) NOT NULL,
  `BANK` VARCHAR(255) NULL,
  `ACCOUNT` VARCHAR(255) NULL,
  `NOTE` TEXT,
  `CREATOR` VARCHAR(160) NOT NULL,
  `CREATETIME` DATETIME NOT NULL DEFAULT NOW(),
  `LASTUPD` VARCHAR(160) NOT NULL,
  `LASTUPDTIME` DATETIME NOT NULL DEFAULT NOW(),
  `ORDERNO` INT NOT NULL DEFAULT 0,
  `CHECKSTAT` VARCHAR(255) NOT NULL DEFAULT '未复核',
  `MOBILE` VARCHAR(255) NULL,
  `EMAIL` VARCHAR(255) NULL,
  `CONTACTS` VARCHAR(255) NULL,
  PRIMARY KEY (`CODE`), UNIQUE (`NAME`,`ORG`)
) ENGINE=INNODB DEFAULT CHARSET=UTF8;
