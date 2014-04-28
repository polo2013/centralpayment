/*
编码以三位为基础单位，向内层扩展，程序解析时每三位为一层。
*/
/*必须初始化部分*/
DELETE FROM SYS_SETTING;
INSERT INTO SYS_SETTING(STYPE,SNAME,SVALUE,NOTE) VALUES ('public', 'pay_role', '[001005]付款人','该角色负责付款，只能看到已批准待付款的单据');

DELETE FROM SYS_MODULES;
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('001', '系统模块和菜单', '系统设置', 'system','SYS_MODULES','[admin]管理员','[admin]管理员',1,'设置模块和菜单!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('002', '系统状态流', '系统设置', 'sysstat','SYS_STAT','[admin]管理员','[admin]管理员',1,'设置状态流!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('003', '机构设置', '基础资料设置', 'sysorg','SYS_ORG','[admin]管理员','[admin]管理员',2,'设置机构!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('004', '角色设置', '基础资料设置', 'sysrole','SYS_ROLE','[admin]管理员','[admin]管理员',2,'设置角色!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('005', '权限设置', '基础资料设置', 'sysauth','SYS_AUTH','[admin]管理员','[admin]管理员',2,'设置权限!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('006', '用户设置', '基础资料设置', 'sysuser','SYS_USER','[admin]管理员','[admin]管理员',2,'设置用户!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('007', '项目设置', '基础资料设置', 'bizproject','BIZ_PROJECT','[admin]管理员','[admin]管理员',2,'设置项目!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('008', '收款人设置', '基础资料设置', 'bizpayee','BIZ_PAYEE','[admin]管理员','[admin]管理员',2,'设置收款人!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('009', '付款汇总表', '业务模块', 'zdcwpayment','ZDCW_PAYMENT','[admin]管理员','[admin]管理员',3,'付款汇总表!');
INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,OBJ,CREATOR,LASTUPD,ORDERNO,NOTE) VALUES ('010', '付款汇总表查询', '业务模块', 'zdcwpaymentview','ZDCW_PAYMENT_VIEW','[admin]管理员','[admin]管理员',3,'查询付款汇总表!');
/*-----------------------------------------*/
DELETE FROM SYS_STAT;
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('001', '录入', '录入','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('002', '待审核', '提交审核','001,004,006,009','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('003', '已审核待批准', '审核通过','001,002,010','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('004', '审核不通过', '审核不通过','002,010','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('005', '已批准待付款', '批准通过','003,011','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('006', '批准不通过', '批准不通过','003,011','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('007', '付款中', '付款开始','005','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('008', '付款已完成', '付款完成','007','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('009', '付款不通过', '付款不通过','007','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('010', '重新审核', '反审核','003','[admin]管理员','[admin]管理员');
INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD) VALUES ('011', '重新批准', '反批准','005','[admin]管理员','[admin]管理员');

/*-----------------------------------------*/
DELETE FROM SYS_MODULES_RIGHTS;
/*001*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '001';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '001';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '001';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '001';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'005'), '模块权限设置权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '001';
/*002*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '002';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '002';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '002';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '002';
/*003*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '003';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '003';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '003';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '003';
/*004*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '004';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '004';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '004';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '004';
/*005*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '005';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '005';
/*006*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'005'), '查看所属机构用户权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules WHERE CODE = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'006'), '启用禁用权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules WHERE CODE = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'007'), '复核权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules WHERE CODE = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'008'), '修改密码权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules WHERE CODE = '006';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'009'), '重置用户权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules WHERE CODE = '006';
/*007*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '007';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '007';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '007';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '007';
/*008*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '008';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '新增权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '008';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '008';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '008';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'005'), '复核权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '008';
/*009*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '录入权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '修改权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'004'), '审核权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'005'), '批准权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'006'), '付款权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'007'), '取消付款权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'008'), '备注权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'009'), '反审核权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'010'), '反批准权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'011'), '打印权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'012'), '删除权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '009';

/*010*/
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'001'), '查看权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '010';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'002'), '查看所属机构单据权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '010';
INSERT INTO SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD)
select CONCAT(`CODE`,'003'), '查看所有单据权', CONCAT('[',`CODE`,']',`NAME`),'[admin]管理员' from sys_modules where code = '010';

/*-----------------------------------------*/
DELETE FROM SYS_ORG;
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001','正大集团','','','','','','[admin]管理员','[admin]管理员');

/*-----------------------------------------*/
DELETE FROM SYS_ROLE;
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001001','管理员','[001]正大集团','[admin]管理员','[admin]管理员');

/*-----------------------------------------*/
DELETE FROM SYS_USER;
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CREATOR, LASTUPD) VALUES ('admin', '管理员', 'd41d8cd98f00b204e9800998ecf8427e', '[001001]管理员', '[001]正大集团', '', '', '', '', '启用', '[admin]管理员', '[admin]管理员');
/*-----------------------------------------*/

/*插入admin权限*/
DELETE FROM SYS_AUTH;
INSERT INTO SYS_AUTH(ROLE,RIGHTS,LASTUPD)
SELECT CONCAT('[',A.CODE,']',A.NAME), CONCAT('[',B.CODE,']',B.NAME), '[admin]管理员'
FROM SYS_ROLE A, SYS_MODULES_RIGHTS B
WHERE A.CODE = '001001';
/*-----------------------------------------*/



/*
--内测--begin

INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001001','正大置地有限公司','[001]正大集团','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001001001','正大置地上海会计部','[001001]正大置地有限公司','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001001002','正大置地北京办公室','[001001]正大置地有限公司','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001002','审计部','[001]正大集团','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001003','IT部','[001]正大集团','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001004','上海财务部','[001]正大集团','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001005','上海天泰租赁有限公司','[001]正大集团','','','','','[admin]管理员','[admin]管理员');



INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001002','录入人','[001]正大集团','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001003','审核人','[001]正大集团','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001004','批准人','[001]正大集团','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001005','付款人','[001]正大集团','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001006','员工','[001]正大集团','[admin]管理员','[admin]管理员');



delete from SYS_AUTH where ROLE = '[001002]录入人'; insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006002]新增权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006003]修改权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006004]删除权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006005]查看所属机构用户权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006006]启用禁用权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[006008]修改密码权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[007001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[007002]新增权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[007003]修改权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[007004]删除权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[008001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[008002]新增权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[008003]修改权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[008004]删除权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[009001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[009002]录入权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[009003]修改权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[009011]打印权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[009012]删除权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001002]录入人', '[010001]查看权', '[admin]管理员'); 

delete from SYS_AUTH where ROLE = '[001003]审核人'; insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006005]查看所属机构用户权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006006]启用禁用权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006007]复核权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006008]修改密码权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[006009]重置用户权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[008001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[008005]复核权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[009001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[009004]审核权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[009009]反审核权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[010001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001003]审核人', '[010002]查看所属机构单据权', '[admin]管理员'); 

delete from SYS_AUTH where ROLE = '[001004]批准人'; insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[006001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[006008]修改密码权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[009001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[009005]批准权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[009010]反批准权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[010001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001004]批准人', '[010002]查看所属机构单据权', '[admin]管理员'); 

delete from SYS_AUTH where ROLE = '[001005]付款人'; insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[006001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[006008]修改密码权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[009001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[009006]付款权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[009007]取消付款权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[009008]备注权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[009011]打印权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[010001]查看权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[010002]查看所属机构单据权', '[admin]管理员'); insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('[001005]付款人', '[010003]查看所有单据权', '[admin]管理员'); 



INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('yuting.shen', '沈钰婷', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人', '[001001001]正大置地上海会计部', '', 'yuting.shen@chiatailand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('angela.ma', '马爱萍', 'd41d8cd98f00b204e9800998ecf8427e', '[001003]审核人,[001004]批准人', '[001001001]正大置地上海会计部', '', 'angela.ma@chiatailand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('liuminzhe_spark', '刘敏哲', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人', '[001001002]正大置地北京办公室', '', 'liuminzhe_spark@163.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('annlee', '李安', 'd41d8cd98f00b204e9800998ecf8427e', '[001003]审核人', '[001001002]正大置地北京办公室', '', 'annlee@charoenpokphand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('jack.mei', '梅志豪', 'd41d8cd98f00b204e9800998ecf8427e', '[001004]批准人', '[001001002]正大置地北京办公室', '', 'jack.mei@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('jennifer.liao', '廖米佳', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人,[001003]审核人', '[001003]IT部', '', 'jennifer.liao@cp-it.com.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('liweiguo', '李为国', 'd41d8cd98f00b204e9800998ecf8427e', '[001004]批准人', '[001003]IT部', '', 'liweiguo@chiatailand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('sujie', '苏婕', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人', '[001005]上海天泰租赁有限公司', '', 'sujie@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('hanjuanjuan', '韩娟娟', 'd41d8cd98f00b204e9800998ecf8427e', '[001003]审核人', '[001005]上海天泰租赁有限公司', '', 'hanjuanjuan@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('huhua', '胡华', 'd41d8cd98f00b204e9800998ecf8427e', '[001005]付款人|[001004]批准人', '[001004]上海财务部|[001005]上海天泰租赁有限公司', '', 'huhua@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('chenli', '陈莉', 'd41d8cd98f00b204e9800998ecf8427e', '[001005]付款人', '[001004]上海财务部', '', 'chenli@chiatailand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('hee.zhang', '张扬', 'd41d8cd98f00b204e9800998ecf8427e', '[001005]付款人', '[001004]上海财务部', '', 'hee.zhang@chiatailand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('shirley.bao', '包娅莎', 'd41d8cd98f00b204e9800998ecf8427e', '[001005]付款人', '[001004]上海财务部', '', 'shirley.bao@superbrandmall.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('jiangwenjing', '蒋文靖', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人,[001003]审核人', '[001002]审计部', '13311912805', 'jiangwenjing@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('yantingting', '严婷婷', 'd41d8cd98f00b204e9800998ecf8427e', '[001002]录入人,[001003]审核人', '[001002]审计部', '13564374289', 'yantingting@cpgroup.cn', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');
INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CHECKSTAT, CREATOR, LASTUPD) VALUES ('betty.wong', '黄佩珊', 'd41d8cd98f00b204e9800998ecf8427e', '[001004]批准人', '[001002]审计部', '13818146929', 'betty.wong@charoenpokphand.com', '', '', '启用', '已复核', '[admin]管理员', '[admin]管理员');


--内测--end

-------------------------------------------------------
DELETE FROM BIZ_PAYEE;
INSERT INTO BIZ_PAYEE(CODE,NAME,ORG,BANK,ACCOUNT,CREATOR,LASTUPD,MOBILE, EMAIL,CONTACTS) VALUES ('001001001','北京乐途国际旅行社有限公司','[001001]正大置地有限公司','中国建设银行北京建国支行','11001042500056150048','[admin]管理员','[admin]管理员','13811112222', 'lalala@CP-IT.COM.CN','张三');
INSERT INTO BIZ_PAYEE(CODE,NAME,ORG,BANK,ACCOUNT,CREATOR,LASTUPD,MOBILE, EMAIL,CONTACTS) VALUES ('001001002','爱马克服务产业（中国）有限公司北京分公司','[001001]正大置地有限公司','中国银行北京支行','0200242419020128326','[admin]管理员','[admin]管理员','13811112222', 'lalala@CP-IT.COM.CN','李四');

DELETE FROM BIZ_PROJECT;
INSERT INTO BIZ_PROJECT(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001001001','工程前期','[001001]正大置地有限公司','[admin]管理员','[admin]管理员');
INSERT INTO BIZ_PROJECT(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001002001','工程后期','[001002]正大新生活有限公司','[admin]管理员','[admin]管理员');

INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001001','正大置地有限公司','[001]正大集团','','','','','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001001001','正大置地财务部','[001001]正大置地有限公司','010-12345678-888','010-98765432-999','北京市建国门大街2号','100200','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001002','正大新生活有限公司','[001]正大集团','021-23456789','021-87654321','上海市浦东新区东方路2号','200100','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001002001','正大新生活法务部','[001002]正大新生活有限公司','021-23456789-888','021-87654321-999','上海市浦东新区东方路2号','200100','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,CREATOR,LASTUPD) VALUES ('001003','上海资金部','[001]正大集团','021-12345678','021-12345678','上海市浦东新区东方路1363号18C','200100','[admin]管理员','[admin]管理员');


INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001001001','正大置地系统管理员','[001001]正大置地有限公司','[admin]管理员','[admin]管理员');
INSERT INTO SYS_ROLE(CODE,NAME,ORG,CREATOR,LASTUPD) VALUES ('001001001001','正大置地财务部会计','[001001001]正大置地财务部','[admin]管理员','[admin]管理员');

INSERT INTO SYS_USER(CODE, NAME, PASSWD, ROLE, ORG, MOBILE, EMAIL, BANK, ACCOUNT, STAT, CREATOR, LASTUPD) VALUES ('zzz', '正大置地系统管理员', 'd41d8cd98f00b204e9800998ecf8427e', '[001001001]正大置地系统管理员', '[001001]正大置地有限公司', '13801112222', 'ZZZZCCCCCBB@CP-IT.COM.CN', '中国建设银行', '6217001210009583333', '启用', '[admin]管理员', '[admin]管理员');

insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) values ('[001001001]正大置地系统管理员','[003001]查看权','[admin]管理员');
insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) values ('[001001001]正大置地系统管理员','[003002]新增权','[admin]管理员');
insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) values ('[001001001]正大置地系统管理员','[003003]修改权','[admin]管理员');
insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) values ('[001001001]正大置地系统管理员','[003004]删除权','[admin]管理员');


INSERT INTO SYS_AUTH(ROLE,RIGHTS,LASTUPD)
SELECT CONCAT('[',A.CODE,']',A.NAME), CONCAT('[',B.CODE,']',B.NAME), '[admin]管理员'
FROM SYS_ROLE A, SYS_MODULES_RIGHTS B
WHERE A.CODE = '001001'
and CONCAT('[',B.CODE,']',B.NAME) not in (select rights from sys_auth where role = CONCAT('[',A.CODE,']',A.NAME));


*/
