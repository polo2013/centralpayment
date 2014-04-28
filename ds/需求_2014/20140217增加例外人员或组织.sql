DROP TABLE IF EXISTS `zdcw_another_people`;
CREATE TABLE `zdcw_another_people` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `ORG` varchar(160) NOT NULL,
  `OTYPE` varchar(50) NOT NULL,
  `ANOTHER` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

//正大新生活要看到稽培君和韩琦
INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001006','people','zdzd055');
INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001006','people','zdzd056');
//正大置地上海办公室要看到林磊
INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001001001','people','ll');
//正大置地上海办公室要看到北京的部门：人才储备库
INSERT INTO zdcw_another_people (ORG,OTYPE,ANOTHER)
VALUES('001001001','org','001001002005');