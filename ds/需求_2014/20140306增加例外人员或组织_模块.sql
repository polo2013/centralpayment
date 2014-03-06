DELETE FROM SYS_MODULES where code = '012';
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) 
VALUES ('012', '例外情况设置', '系统设置', 'sysexcept','zdcw_another_people','[admin]管理员','[admin]管理员',1,'本部门之外的费用申请人!');

DELETE FROM SYS_MODULES_RIGHTS where code like '012%';
/*011*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '012';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '012';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '012';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '012';

