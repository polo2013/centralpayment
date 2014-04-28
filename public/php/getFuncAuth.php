<?php
include_once("session.php");
$result = array();
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));

$module = $_POST['module'];
$auth = $_POST['auth'];

if ($auth == 'allauth'){
	//取得全部权限
	$result['allAuth'] = getAuthInfo($login_user_role, $module, $auth);
}else{
	//单个功能权限
	$has_right = false;
	$has_right = getAuthInfo($login_user_role, $module, $auth);
	$result['success'] = $has_right;
}
echo json_encode($result);
?>