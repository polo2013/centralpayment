alter table zdcw_payment_detail add `CURRENCY` VARCHAR(100) NOT NULL DEFAULT '人民币';


DROP TABLE IF EXISTS `sys_currency`;
CREATE TABLE `sys_currency` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `CURRENCY` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

INSERT INTO sys_currency (CURRENCY)
VALUES('人民币');
INSERT INTO sys_currency (CURRENCY)
VALUES('泰铢');
INSERT INTO sys_currency (CURRENCY)
VALUES('美元');
INSERT INTO sys_currency (CURRENCY)
VALUES('日元');

