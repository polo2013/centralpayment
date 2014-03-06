<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$id = $_REQUEST['id'];
$org = $_REQUEST['org'];
$otype = $_REQUEST['otype'];
$another = $_REQUEST['another'];


$org_code = splitCode($org);
$another_code = splitCode($another);


	
	//开始更新
	$query = "UPDATE zdcw_another_people SET ORG='$org_code',OTYPE='$otype',ANOTHER='$another_code' WHERE id='$id'";
	if(exequery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '保存成功！';
	}

echo json_encode($result);
?>