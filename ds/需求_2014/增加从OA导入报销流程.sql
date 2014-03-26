DELETE FROM SYS_MODULES where code = '011';
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) 
VALUES ('011', '从OA导入报销流程', '业务模块', 'zdcwImpFromOA','ZDCW_IMP_FROM_OA','[admin]管理员','[admin]管理员',3,'从OA导入报销!');

DELETE FROM SYS_MODULES_RIGHTS where code like '011%';
/*011*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '搜索权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '生成单据权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';

DROP TABLE IF EXISTS `ZDCW_IMP_FROM_OA`;
CREATE TABLE `ZDCW_IMP_FROM_OA` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `ORG` VARCHAR(255) NOT NULL,
  `ORGDESC` VARCHAR(255) NOT NULL,
  `FLOWID` INT(11) NOT NULL,
  `FLOWNAME` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

DELETE FROM ZDCW_IMP_FROM_OA;
INSERT INTO ZDCW_IMP_FROM_OA(ORG,ORGDESC,FLOWID,FLOWNAME)
VALUES ('审计部','BEGIN_DEPT = 1','87','报销流程');
INSERT INTO ZDCW_IMP_FROM_OA(ORG,ORGDESC,FLOWID,FLOWNAME)
VALUES ('信息中心','BEGIN_DEPT = 2','87','报销流程');
INSERT INTO ZDCW_IMP_FROM_OA(ORG,ORGDESC,FLOWID,FLOWNAME)
VALUES ('其他部门','BEGIN_DEPT NOT IN (1,2)','87','报销流程');



-- 给单据增加flag标记：imp表示导入。
alter table zdcw_payment_master add `FLAG` VARCHAR(20) NOT NULL DEFAULT '';

