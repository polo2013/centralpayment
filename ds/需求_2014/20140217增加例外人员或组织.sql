DROP TABLE IF EXISTS `zdcw_another_people`;
CREATE TABLE `zdcw_another_people` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `ORG` varchar(160) NOT NULL,
  `OTYPE` varchar(50) NOT NULL,
  `ANOTHER` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001006','people','zdzd055');
INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001006','people','zdzd056');
