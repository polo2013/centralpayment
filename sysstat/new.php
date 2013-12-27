<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$CODE = $_REQUEST['CODE'];
$NAME = $_REQUEST['NAME'];
$FIREACT = $_REQUEST['FIREACT'];
$FIRESTAT = $_REQUEST['FIRESTAT'];
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
	$logArray_new['FIREACT']=urlencode($FIREACT);
	$logArray_new['FIRESTAT']=urlencode($FIRESTAT);
	$logArray_new['ORDERNO']=urlencode($ORDERNO);

	//旧数据为空
	
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始新增
	$query = "INSERT INTO SYS_STAT(CODE,NAME,FIREACT,FIRESTAT,CREATOR,LASTUPD,ORDERNO) VALUES ('$CODE', '$NAME', '$FIREACT','$FIRESTAT', '$login_user','$login_user','$ORDERNO');";
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