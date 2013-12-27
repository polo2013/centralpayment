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
$chk = checkValueAtDB($MODULENO,$_REQUEST,'add');
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
	
	//旧数据为空
	
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始新增
	$query = "INSERT INTO SYS_ORG(CODE,NAME,PARENT,TEL,FAX,ADDR,ZIPCODE,NOTE,CREATOR,LASTUPD,ORDERNO) VALUES ('$CODE', '$NAME', '$PARENT', '$TEL','$FAX','$ADDR','$ZIPCODE','$NOTE','$login_user','$login_user','$ORDERNO');";
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