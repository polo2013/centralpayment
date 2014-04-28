<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$CODE = $_REQUEST['CODE'];
$NAME = $_REQUEST['NAME'];
$MENU = $_REQUEST['MENU'];
$URL = $_REQUEST['URL'];
$NOTE = $_REQUEST['NOTE'];
$ORDERNO = $_REQUEST['ORDERNO'];
$OBJ = $_REQUEST['OBJ'];


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
	$logArray_new['MENU']=urlencode($MENU);
	$logArray_new['URL']=urlencode($URL);
	$logArray_new['NOTE']=urlencode($NOTE);
	$logArray_new['ORDERNO']=urlencode($ORDERNO);
	$logArray_new['OBJ']=urlencode($OBJ);
	//旧数据为空
	
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始新增
	$query = "INSERT INTO SYS_MODULES(CODE,NAME,MENU,URL,NOTE,CREATOR,LASTUPD,ORDERNO,OBJ) VALUES ('$CODE', '$NAME', '$MENU', '$URL','$NOTE','$login_user','$login_user','$ORDERNO','$OBJ');";
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