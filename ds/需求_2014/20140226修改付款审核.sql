/*增加付款审核权*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'014'), '付款审核权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';

/*赋予付款审核人角色以付款审核权*/
INSERT INTO SYS_AUTH(ROLE,RIGHTS,LASTUPD)
SELECT CONCAT('[',A.CODE,']',A.NAME), CONCAT('[',B.CODE,']',B.NAME), '[admin]管理员'
FROM SYS_ROLE A, SYS_MODULES_RIGHTS B
WHERE A.CODE = '001007' and b.code='009014';