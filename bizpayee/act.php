<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$CODE = $_REQUEST['CODE'];
$FALG = $_REQUEST['FLAG'];
$ACT = $_REQUEST['ACT'];
$CFMSG = $_REQUEST['CFMSG'];


//log
$logArray = array();
$logArray_new = array();
$logArray_old = array();

//旧数据
$query = "SELECT * FROM BIZ_PAYEE WHERE CODE = '$CODE'";
$cursor = exequery($connection,$query);
if($row = mysqli_fetch_array($cursor)){
	$logArray_old['CODE']=urlencode($row['CODE']);
	$logArray_old['NAME']=urlencode($row['NAME']);
	$logArray_old['ORG']=urlencode($row['ORG']);
	$logArray_old['BANK']=urlencode($row['BANK']);
	$logArray_old['ACCOUNT']=urlencode($row['ACCOUNT']);
	$logArray_old['MOBILE']=urlencode($row['MOBILE']);
	$logArray_old['EMAIL']=urlencode($row['EMAIL']);
	$logArray_old['CONTACTS']=urlencode($row['CONTACTS']);
	$logArray_old['CHECKSTAT']=urlencode($row['CHECKSTAT']);
	$logArray_old['NOTE']=urlencode($row['NOTE']);
	$logArray_old['ORDERNO']=urlencode($row['ORDERNO']);		
}
//新数据
$logArray_new = $logArray_old;
if ($FALG == 'check'){
	$logArray_new['CHECKSTAT']=urlencode($ACT);
	$query = "UPDATE BIZ_PAYEE SET CHECKSTAT='$ACT',LASTUPD='$login_user',LASTUPDTIME=NOW() WHERE CODE='$CODE'";
}

$logArray['OLDVALUE']=$logArray_old;
$logArray['NEWVALUE']=$logArray_new;

//开始更新
if(exequery($connection,$query)){
	$result['success'] = true;
	$result['message'] = $CFMSG.'成功！';
	writeDBLog($login_user, $MODULEOBJ, $CFMSG, urldecode(json_encode($logArray)), $MODULETITLE);
}else{
	$result['success'] = false;
	$result['message'] = $CFMSG.'成功！';
}
echo json_encode($result);
?>