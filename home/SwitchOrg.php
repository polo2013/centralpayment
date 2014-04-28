<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];

$result = array('success' => false, 'message' => '切换失败！');
$NextOrg = urldecode($_REQUEST['NextOrg']);
$NextRole = urldecode($_REQUEST['NextRole']);

$_SESSION['LOGIN_USER_ORG'] = $NextOrg;
$_SESSION['LOGIN_USER_ROLE'] = $NextRole;

$result['success'] = true;

echo json_encode($result);
?>