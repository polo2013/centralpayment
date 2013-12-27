<?php
include_once("session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user = $_SESSION['LOGIN_USER'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];

$result = array();
$ModuleNo = $_REQUEST['ModuleNo'];
switch($ModuleNo)
{
	case '009':
		$query = "select * from SYS_MODULES WHERE `CODE` = '$ModuleNo' ";
		$cursor = exequery($connection,$query);
		if ($row = mysqli_fetch_array($cursor)){
			$result['ModuleNo'] = $row['CODE'];
			$result['ModuleName'] = $row['NAME'];
			$result['ModuleUrl'] = $row['URL'];
			$result['ModuleObj'] = $row['OBJ'];
		}
		break;		
}

echo json_encode($result);
?>