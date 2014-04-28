<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$CODE = $_REQUEST['CODE'];
$NAME = $_REQUEST['NAME'];
$PASSWD = md5($_REQUEST['PASSWD']);
$ROLE = $_REQUEST['ROLE'];
$ORG = $_REQUEST['ORG'];

$MOBILE = $_REQUEST['MOBILE'];
$EMAIL = $_REQUEST['EMAIL'];
$BANK = $_REQUEST['BANK'];
$ACCOUNT = $_REQUEST['ACCOUNT'];
$STAT = $_REQUEST['STAT'];
$CHECKSTAT = $_REQUEST['CHECKSTAT'];
$NOTE = $_REQUEST['NOTE'];
$ORDERNO = $_REQUEST['ORDERNO'];


//check value
$chk = checkValueAtDB($MODULENO,$_REQUEST,'add');
if ($chk['ok']){
	//log
	$logArray = array();
	$logArray_new = array();
	$logArray_old = array();
	//新数据
	$logArray_new['CODE']=urlencode($CODE);
	$logArray_new['NAME']=urlencode($NAME);
	$logArray_new['PASSWD']=urlencode($PASSWD);
	$logArray_new['ROLE']=urlencode($ROLE);
	$logArray_new['ORG']=urlencode($ORG);
	$logArray_new['MOBILE']=urlencode($MOBILE);
	$logArray_new['EMAIL']=urlencode($EMAIL);
	$logArray_new['BANK']=urlencode($BANK);
	$logArray_new['ACCOUNT']=urlencode($ACCOUNT);
	$logArray_new['STAT']=urlencode($STAT);
	$logArray_new['CHECKSTAT']=urlencode($CHECKSTAT);
	$logArray_new['NOTE']=urlencode($NOTE);
	$logArray_new['ORDERNO']=urlencode($ORDERNO);
	
	//旧数据为空
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始新增
	$query = "INSERT INTO SYS_USER(CODE,NAME,PASSWD,ROLE,ORG,MOBILE,EMAIL,BANK,ACCOUNT,STAT,CHECKSTAT,"
	."NOTE,CREATOR,LASTUPD,ORDERNO) VALUES ('$CODE', '$NAME', '$PASSWD', '$ROLE', '$ORG','$MOBILE','$EMAIL',"
	."'$BANK','$ACCOUNT','$STAT','$CHECKSTAT','$NOTE','$login_user','$login_user','$ORDERNO');";
	if(exequery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '保存成功！';
		writeDBLog($login_user, $MODULEOBJ, '新增', urldecode(json_encode($logArray)),$MODULETITLE);
	}
}else{
	$result['success'] = false;
	$result['message'] = $chk['msg'];
}

echo json_encode($result);
?>