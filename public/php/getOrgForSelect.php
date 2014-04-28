<?php
include_once("session.php");
$result = array();
$result_item = array();
$result_item_item = array();

$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$result['myorg'] = $login_user_org;
/**
* 可选的组织 包括 当前用户所属组织，及其下属组织。by zhoucb
*/
$orgCode = splitCode($login_user_org);
$query = "SELECT * FROM sys_org WHERE SUBSTR(`CODE`,1,LENGTH('$orgCode')) = '$orgCode' ORDER BY ORDERNO, `CODE`";

$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item[] = $result_item_item;
}
$result['allorg'] = $result_item;
echo json_encode($result);
?>