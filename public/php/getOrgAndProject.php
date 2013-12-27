<?php
include_once("session.php");
$result = array();
$result_item = array();
$result_item_item = array();

$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$result['my'] = $login_user_org;

$orgCode = splitCode($login_user_org);
$query = "SELECT * FROM sys_org WHERE SUBSTR(`CODE`,1,LENGTH('$orgCode')) = '$orgCode' ORDER BY ORDERNO, `CODE`";
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item[] = $result_item_item;
}

$query = "SELECT * FROM biz_project WHERE SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY ORDERNO, `CODE`";
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
	$result_item[] = $result_item_item;
}


$result['all'] = $result_item;
echo json_encode($result);
?>