<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$ID = $_REQUEST['ID'];


	//开始删除
	$query = "DELETE FROM zdcw_another_people WHERE id = '$ID';";
	if(exequery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '删除成功！';
	}

echo json_encode($result);
?>