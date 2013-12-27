<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$MODULENO = urldecode($_GET['MODULENO']) ? urldecode($_GET['MODULENO']) : "";

$result = array();
$result_row = array();
$result_cell = array();

//数据权限
$query = "SELECT COUNT(1) FROM SYS_MODULES_RIGHTS WHERE MODULES = '$MODULENO' ORDER BY CODE";
//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', '*', $query);
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['CODE'] = $row_page['CODE'];
	$result_cell['NAME'] = $row_page['NAME'];
	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

echo json_encode($result);

?>