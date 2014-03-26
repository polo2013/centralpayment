<?php
include_once("../public/php/session.php");
$result = array();
$login_user = $_SESSION['LOGIN_USER'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));

$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$orgCode = splitCode($login_user_org);

if(getAuthInfo($login_user_role, '011', '查看权')){	
	//组织
	$result_item = array();
	$result_item_item = array();
	$query = "SELECT `ID`, `ORG` FROM ZDCW_IMP_FROM_OA ORDER BY `ID` desc";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result_item_item['value'] = $row['ID'];
		$result_item_item['text'] = $row['ORG'];
		$result_item[] = $result_item_item;
	}
	$result['allOrg'] = $result_item;

	//流程
	$result_item = array();
	$result_item_item = array();
	$query = "SELECT distinct `FLOWID`, `FLOWNAME` FROM ZDCW_IMP_FROM_OA ORDER BY `ID`";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result_item_item['value'] = $row['FLOWID'];
		$result_item_item['text'] = $row['FLOWNAME'].'（OA流程ID_'.$row['FLOWID'].'）';
		$result_item[] = $result_item_item;
	}
	$result['allFlow'] = $result_item;

	$result_item = array();
	$result_item_item = array();
	$result_item_item['value'] = '已完成';
	$result_item_item['text'] = '已完成';
	$result_item[] = $result_item_item;
	$result['allStat'] = $result_item;
}

echo json_encode($result);

?>