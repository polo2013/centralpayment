/*新增导入模块*/
DELETE FROM SYS_MODULES where code = '011';
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) 
VALUES ('011', '从OA导入报销流程', '业务模块', 'zdcwImpFromOA','ZDCW_IMP_FROM_OA','[admin]管理员','[admin]管理员',3,'从OA导入报销!');

/*011导入模块的权限*/
DELETE FROM SYS_MODULES_RIGHTS where code like '011%';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '搜索权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '生成单据权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '设置权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'005'), '导入信息删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '011';


/*设置表*/
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



-- 给单据增加导入标记
alter table zdcw_payment_master add `IMP_FLAG` VARCHAR(20) NOT NULL DEFAULT '';

-- 新增状态
DELETE FROM SYS_STAT where code = '014';
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) 
VALUES ('014', '已导入', '','','[admin]管理员','[admin]管理员');
DELETE FROM SYS_STAT where code = '015';
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) 
VALUES ('015', '付款确认通过', '付款确认通过','014','[admin]管理员','[admin]管理员');
DELETE FROM SYS_STAT where code = '016';
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) 
VALUES ('016', '付款确认不通过', '付款确认不通过','014','[admin]管理员','[admin]管理员');

-- 更改付款开始的条件
UPDATE SYS_STAT SET FIRESTAT = CONCAT(FIRESTAT, ',015') where code = '007';

-- 增加导入人、导入时间
alter table zdcw_payment_master add `PAYIMPORT` VARCHAR(160) NULL;
alter table zdcw_payment_master add `PAYIMPORTTIME` DATETIME NULL;
-- 增加付款确认人、付款确认时间
alter table zdcw_payment_master add `PAYCONFIRM` VARCHAR(160) NULL;
alter table zdcw_payment_master add `PAYCONFIRMTIME` DATETIME NULL;

-- 增加付款确认权
DELETE FROM SYS_MODULES_RIGHTS where code like '009%' AND name = '付款确认权'; 
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'015'), '付款确认权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';

/*导入流程记录表*/
DROP TABLE IF EXISTS `ZDCW_IMP_FROM_OA_REC`;
CREATE TABLE `ZDCW_IMP_FROM_OA_REC` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `FLOWTYPE` VARCHAR(255) NOT NULL,
  `FLOWINFO` VARCHAR(255) NOT NULL,
  `NUM` VARCHAR(60) NOT NULL,
  `ITEMNO` VARCHAR(10) NOT NULL,
  `ORG` VARCHAR(160) NOT NULL,
  `APPLICANT` VARCHAR(160) NOT NULL,
  `PAYMENT` TEXT,
  `TOTALAMT` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `PAYEE` VARCHAR(160) NOT NULL,
  `BANK` VARCHAR(255) NOT NULL,
  `ACCOUNT` VARCHAR(255) NOT NULL,
  `NOTE` TEXT,
  `CURRENCY` VARCHAR(100) NOT NULL DEFAULT '人民币',
  PRIMARY KEY (`ID`)
) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=UTF8;

-- 增加导入单据的查看权
DELETE FROM SYS_MODULES_RIGHTS where code like '010%' AND name = '导入单据查看权'; 
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '导入单据查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '010';
