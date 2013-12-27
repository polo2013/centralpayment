<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$CODE = $_REQUEST['CODE'];
$OLDPASSWD = md5($_REQUEST['OLDPASSWD']);
$NEWPASSWD = md5($_REQUEST['NEWPASSWD']);
//验证旧密码
$query_check = "SELECT * FROM SYS_USER WHERE CODE = '$CODE' AND PASSWD = '$OLDPASSWD'";
$cursor_check = exequery($connection,$query_check);
if($row_check = mysqli_fetch_array($cursor_check)){
	//log
	$logArray = array();
	$logArray_new = array();
	$logArray_old = array();

	//旧数据
	$logArray_old['CODE']=urlencode($row_check['CODE']);
	$logArray_old['NAME']=urlencode($row_check['NAME']);
	$logArray_old['PASSWD']=urlencode($row_check['PASSWD']);
	$logArray_old['ROLE']=urlencode($row_check['ROLE']);
	$logArray_old['ORG']=urlencode($row_check['ORG']);
	$logArray_old['MOBILE']=urlencode($row_check['MOBILE']);
	$logArray_old['EMAIL']=urlencode($row_check['EMAIL']);
	$logArray_old['BANK']=urlencode($row_check['BANK']);
	$logArray_old['ACCOUNT']=urlencode($row_check['ACCOUNT']);
	$logArray_old['STAT']=urlencode($row_check['STAT']);
	$logArray_old['CHECKSTAT']=urlencode($row_check['CHECKSTAT']);
	$logArray_old['NOTE']=urlencode($row_check['NOTE']);
	$logArray_old['ORDERNO']=urlencode($row_check['ORDERNO']);		

	//新数据
	$logArray_new = $logArray_old;
	$logArray_new['PASSWD']=urlencode($NEWPASSWD);
	$query = "UPDATE SYS_USER SET PASSWD='$NEWPASSWD',LASTUPD='$login_user',LASTUPDTIME=NOW() WHERE CODE='$CODE'";

	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;

	//开始更新
	if(exequery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '保存成功！';
		writeDBLog($login_user, $MODULEOBJ, '修改密码', urldecode(json_encode($logArray)), $MODULETITLE);
	}else{
		$result['success'] = false;
		$result['message'] = '保存失败！';
	}
}else{
	$result['success'] = false;
	$result['message'] = '旧密码与现有密码不符！';
}

echo json_encode($result);
?>