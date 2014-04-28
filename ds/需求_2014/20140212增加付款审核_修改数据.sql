/*重新确定系统流程*/
DELETE FROM SYS_STAT;
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('001','录入','录入','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('002','待审核','提交审核','001,004,006,009,013','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('003','已审核','审核通过','001,002,010','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('004','审核不通过','审核不通过','002,010','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('005','已批准','批准通过','003,011','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('006','批准不通过','批准不通过','003,011','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('007','付款中','付款开始','005,012','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('008','付款已完成','付款完成','007','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('009','付款不通过','付款不通过','007','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('010','重新审核','反审核','003','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('011','重新批准','反批准','005','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('012', '付款审核通过', '付款审核通过','005','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('013', '付款审核不通过', '付款审核不通过','005','[admin]管理员','[admin]管理员');
/*增加系统选项*/
delete from SYS_SETTING where sname = 'pay_role' and stype = 'public';
INSERT INTO SYS_SETTING(STYPE,SNAME,SVALUE,NOTE) VALUES ('public', 'pay_role', '[001005]付款人','该角色负责付款，只能看到进入付款环节的单据');
delete from SYS_SETTING where sname = 'pay_check_role' and stype = 'public';
INSERT INTO SYS_SETTING(STYPE,SNAME,SVALUE,NOTE) VALUES ('public', 'pay_check_role', '[001007]付款审核人','该角色负责付款审核，能看到已批准的单据');
delete from SYS_SETTING where sname = 'pay_check_org' and stype = 'public';
INSERT INTO SYS_SETTING(STYPE,SNAME,SVALUE,NOTE) VALUES ('public', 'pay_check_org', '001002,001003','要求付款审核的机构列表');
/*增加付款审核人角色*/
delete from SYS_ROLE where code = '001007';
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001007','付款审核人','[001]正大集团','[admin]管理员','[admin]管理员');
/*赋予马爱萍付款审核人的角色*/
update sys_user set ROLE = '[001003]审核人,[001004]批准人,[001007]付款审核人' where code = 'angela.ma';
