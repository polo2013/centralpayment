<?php
//设置时区
date_default_timezone_set ('Asia/Shanghai');

//-- 网页根目录配置(Apache:自动获取) --
$ROOT_PATH=getenv("DOCUMENT_ROOT");
if($ROOT_PATH=="")
	$ROOT_PATH="c:/xampp/htdocs/";
if(substr($ROOT_PATH,-1)!="/")
	$ROOT_PATH.="/";

$ROOT_PATH.="centralpayment/";
$HTTP_ROOT_PATH="/centralpayment/";

//-- 空闲强制自动离线时间，单位分钟，0为不限制 --
$OFFLINE_TIME_MIN=0;

//-- 数据库配置 --
$MYSQL_SERVER="localhost:3306";
$MYSQL_USER="root";
$MYSQL_PASS="";
$MYSQL_DB="centralpayment";
$MY_DB_CHARSET="utf8";

//是否记录文本日志。true则记录日志到logs文件夹下，false则不记录。
$TURN_LOG_ON=true;

//系统名称
$SYS_TITLE="正大财务集中支付确认系统";

//版本演进
$VERSION_INFO = ''
.'<div>'
	.'<div>v1.1->v1.2</div>'
	.'<ol>'
		.'<li>[新增] 支持用户在两个机构任职设定</li>'
	.'</ol>'
.'</div>'
.'<div>'
	.'<div>v1.0->v1.1</div>'
	.'<ol>'
		.'<li>[新增] 支持用户同一机构多角色设定</li>'
	.'</ol>'
.'</div>'
.'<div>'
	.'<div>v1.0</div>'
	.'<ol>'
		.'<li>[新增] 构建系统框架</li>'
		.'<li>[新增] 付款汇总表模块</li>'
	.'</ol>'
.'</div>';
?>
