<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$CODE = $_REQUEST['CODE'];
$NAME = $_REQUEST['NAME'];
$PARENT = $_REQUEST['PARENT'];
$TEL = $_REQUEST['TEL'];
$FAX = $_REQUEST['FAX'];
$ADDR = $_REQUEST['ADDR'];
$ZIPCODE = $_REQUEST['ZIPCODE'];
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
	$logArray_new['PARENT']=urlencode($PARENT);
	$logArray_new['TEL']=urlencode($TEL);
	$logArray_new['FAX']=urlencode($FAX);
	$logArray_new['ADDR']=urlencode($ADDR);
	$logArray_new['ZIPCODE']=urlencode($ZIPCODE);
	$logArray_new['NOTE']=urlencode($NOTE);
	$logArray_new['ORDERNO']=urlencode($ORDERNO);
	
	//旧数据
	$query = "select * from SYS_ORG where CODE = '$CODE'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$logArray_old['CODE']=urlencode($row['CODE']);
		$logArray_old['NAME']=urlencode($row['NAME']);
		$logArray_old['PARENT']=urlencode($row['PARENT']);
		$logArray_old['TEL']=urlencode($row['TEL']);
		$logArray_old['FAX']=urlencode($row['FAX']);
		$logArray_old['ADDR']=urlencode($row['ADDR']);
		$logArray_old['ZIPCODE']=urlencode($row['ZIPCODE']);
		$logArray_old['NOTE']=urlencode($row['NOTE']);
		$logArray_old['ORDERNO']=urlencode($row['ORDERNO']);
	}
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始更新
	$query = "UPDATE SYS_ORG SET NAME='$NAME',PARENT='$PARENT',TEL='$TEL',FAX='$FAX',ADDR='$ADDR',ZIPCODE='$ZIPCODE',NOTE='$NOTE',LASTUPD='$login_user',LASTUPDTIME=NOW(),ORDERNO='$ORDERNO' WHERE CODE='$CODE'";
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