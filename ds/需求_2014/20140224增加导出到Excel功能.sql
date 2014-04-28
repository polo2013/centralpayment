INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'013'), '导出权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
