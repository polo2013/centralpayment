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

?>
