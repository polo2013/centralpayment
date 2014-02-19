<?php
include_once("session.php");
$result = array();
$result_item = array();
$result_item_item = array();

$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$result['my'] = $login_user_org;

$orgCode = splitCode($login_user_org);

//取额外可以显示的部门
$another = array();
$query = "select * from zdcw_another_people WHERE `ORG` = '$orgCode' and OTYPE = 'org'";
$cursor = exequery($connection,$query);
while ($row = mysqli_fetch_array($cursor)){
	$another[] = $row;
}
//end
$another_str = '';
if ($another) {
	foreach ($another as $val){
		$another_str .= $val['ANOTHER']."','";
	}
	$another_str = rtrim($another_str,"','");
}

$query = "SELECT * FROM sys_org WHERE SUBSTR(`CODE`,1,LENGTH('$orgCode')) = '$orgCode' OR `CODE` in ('".$another_str."') ORDER BY ORDERNO, `CODE`";
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