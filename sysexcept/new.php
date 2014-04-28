<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$org = $_REQUEST['org'];
$otype = $_REQUEST['otype'];
$another = $_REQUEST['another'];


$org_code = splitCode($org);
$another_code = splitCode($another);


//开始新增
$query = "INSERT INTO zdcw_another_people(ORG,OTYPE,ANOTHER) VALUES ('$org_code', '$otype', '$another_code');";
if(exequery($connection,$query)){
	$result['success'] = true;
	$result['message'] = '保存成功！';
}

echo json_encode($result);
?>