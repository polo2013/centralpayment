<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$CODE = $_REQUEST['CODE'];
$NAME = $_REQUEST['NAME'];
$ORG = $_REQUEST['ORG'];
$BANK = $_REQUEST['BANK'];
$ACCOUNT = $_REQUEST['ACCOUNT'];
$MOBILE = $_REQUEST['MOBILE'];
$EMAIL = $_REQUEST['EMAIL'];
$CONTACTS = $_REQUEST['CONTACTS'];
$CHECKSTAT = $_REQUEST['CHECKSTAT'];
$NOTE = $_REQUEST['NOTE'];
$ORDERNO = $_REQUEST['ORDERNO'];

//check value
$chk = checkValueAtDB($MODULENO,$_REQUEST,'edit');
if ($chk['ok']){
	//log
	$logArray = array();
	$logArray_new = array();
	$logArray_old = array();
	//新数据
	$logArray_new['CODE']=urlencode($CODE);
	$logArray_new['NAME']=urlencode($NAME);
	$logArray_new['ORG']=urlencode($ORG);
	$logArray_new['MOBILE']=urlencode($MOBILE);
	$logArray_new['EMAIL']=urlencode($EMAIL);
	$logArray_new['BANK']=urlencode($BANK);
	$logArray_new['ACCOUNT']=urlencode($ACCOUNT);
	$logArray_new['CONTACTS']=urlencode($CONTACTS);
	$logArray_new['CHECKSTAT']=urlencode($CHECKSTAT);
	$logArray_new['NOTE']=urlencode($NOTE);
	$logArray_new['ORDERNO']=urlencode($ORDERNO);
	
	//旧数据
	$query = "select * from BIZ_PAYEE where CODE = '$CODE'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$logArray_old['CODE']=urlencode($row['CODE']);
		$logArray_old['NAME']=urlencode($row['NAME']);
		$logArray_old['ORG']=urlencode($row['ORG']);
		$logArray_old['MOBILE']=urlencode($row['MOBILE']);
		$logArray_old['EMAIL']=urlencode($row['EMAIL']);
		$logArray_old['BANK']=urlencode($row['BANK']);
		$logArray_old['ACCOUNT']=urlencode($row['ACCOUNT']);
		$logArray_old['CONTACTS']=urlencode($row['CONTACTS']);
		$logArray_old['CHECKSTAT']=urlencode($row['CHECKSTAT']);
		$logArray_old['NOTE']=urlencode($row['NOTE']);
		$logArray_old['ORDERNO']=urlencode($row['ORDERNO']);
	}
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始更新
	$query = "UPDATE BIZ_PAYEE SET NAME='$NAME',ORG='$ORG',MOBILE='$MOBILE',"
	."EMAIL='$EMAIL',BANK='$BANK',ACCOUNT='$ACCOUNT',CONTACTS='$CONTACTS',CHECKSTAT='$CHECKSTAT',"
	."NOTE='$NOTE',LASTUPD='$login_user',LASTUPDTIME=NOW(),ORDERNO='$ORDERNO' WHERE CODE='$CODE'";
	if(exequery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '保存成功！';
		writeDBLog($login_user, $MODULEOBJ, '修改', urldecode(json_encode($logArray)), $MODULETITLE);
	}
}else{
	$result['success'] = false;
	$result['message'] = $chk['msg'];
}
echo json_encode($result);
?>