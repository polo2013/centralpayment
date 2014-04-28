<?php
include_once("session.php");
$result = array();
$result_item = array();
$result_item_item = array();

//$login_user_org = $_SESSION['LOGIN_USER_ORG'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
//$result['myrole'] = $login_user_role;
/**
* 可选的角色 包括 本机构以及下属机构的角色。by zhoucb
*/
//$orgcode = splitCode($login_user_org);
//$query = "SELECT * FROM sys_role WHERE SUBSTR(`ORG`,2, LENGTH('$orgcode'))='$orgcode' ORDER BY ORDERNO, `CODE`";
$query = "SELECT * FROM sys_role ORDER BY ORDERNO, `CODE`";
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item[] = $result_item_item;
}
$result['myrole'] = $result_item[0]['value'];
$result['allrole'] = $result_item;
echo json_encode($result);
?>