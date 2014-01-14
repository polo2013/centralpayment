<?php
include_once("../public/php/session.php");
$result = array();
$login_user = $_SESSION['LOGIN_USER'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$orgCode = splitCode($login_user_org);

//如果有查看所有单据权，则当前项默认为空，下拉列表包含所有项。否则只有当前机构及其下属机构的内容。
//机构
$result_item = array();
$result_item_item = array();
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$result_item_item['value'] = '全部';
	$result_item_item['text'] = '全部';
	$result_item[] = $result_item_item;
	$result['myOrg'] = '全部';
	$query = "SELECT distinct `ORG` FROM zdcw_payment_master ORDER BY `ORG` DESC";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result_item_item['value'] = $row['ORG'];
		$result_item_item['text'] = $row['ORG'];
		$result_item[] = $result_item_item;
	}
	$result['allOrg'] = $result_item;
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$result['myOrg'] = $login_user_org;
	$query = "SELECT distinct `ORG` FROM zdcw_payment_master WHERE SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY `ORG` DESC";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result_item_item['value'] = $row['ORG'];
		$result_item_item['text'] = $row['ORG'];
		$result_item[] = $result_item_item;
	}
	$result['allOrg'] = $result_item;
}else{
	$result['myOrg'] = $login_user_org;
	$result['allOrg'] = $result_item;
}

//状态
$result_item = array();
$result_item_item = array();
$result_item_item['value'] = '全部';
$result_item_item['text'] = '全部';
$result_item[] = $result_item_item;
$result['myStat'] = '全部';
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$query = "SELECT distinct b.`NAME` STAT FROM zdcw_payment_master a, sys_stat b WHERE a.STAT = b.`NAME` ORDER BY B.`CODE`";
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$query = "SELECT distinct b.`NAME` STAT FROM zdcw_payment_master a, sys_stat b WHERE a.STAT = b.`NAME` and SUBSTR(a.`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY B.`CODE`";
}else{
	$query = "SELECT distinct b.`NAME` STAT FROM zdcw_payment_master a, sys_stat b WHERE a.STAT = b.`NAME` and (a.INPUTTER = '$login_user' OR a.CHECKER = '$login_user' OR a.APPROVER = '$login_user') ORDER BY B.`CODE`";
}
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = $row['STAT'];
	$result_item_item['text'] = $row['STAT'];
	$result_item[] = $result_item_item;
}
$result['allStat'] = $result_item;


//录入人
$result_item = array();
$result_item_item = array();
$result_item_item['value'] = '全部';
$result_item_item['text'] = '全部';
$result_item[] = $result_item_item;
$result['myInputter'] = '全部';
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$query = "SELECT distinct INPUTTER FROM zdcw_payment_master ORDER BY INPUTTER";
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$query = "SELECT distinct INPUTTER FROM zdcw_payment_master WHERE SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY INPUTTER";
}else{
	$query = "SELECT '$login_user' INPUTTER";
}
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = $row['INPUTTER'];
	$result_item_item['text'] = $row['INPUTTER'];
	$result_item[] = $result_item_item;
}
$result['allInputter'] = $result_item;

//审核人
$result_item = array();
$result_item_item = array();
$result_item_item['value'] = '全部';
$result_item_item['text'] = '全部';
$result_item[] = $result_item_item;
$result['myChecker'] = '全部';
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$query = "SELECT distinct CHECKER FROM zdcw_payment_master WHERE CHECKER != '' ORDER BY CHECKER";
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$query = "SELECT distinct CHECKER FROM zdcw_payment_master WHERE CHECKER != '' AND SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY CHECKER";
}else{
	$query = "SELECT '$login_user' CHECKER";
}
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = $row['CHECKER'];
	$result_item_item['text'] = $row['CHECKER'];
	$result_item[] = $result_item_item;
}
$result['allChecker'] = $result_item;

//批准人
$result_item = array();
$result_item_item = array();
$result_item_item['value'] = '全部';
$result_item_item['text'] = '全部';
$result_item[] = $result_item_item;
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$query = "SELECT distinct APPROVER FROM zdcw_payment_master WHERE APPROVER != '' ORDER BY APPROVER";
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$query = "SELECT distinct APPROVER FROM zdcw_payment_master WHERE APPROVER != '' AND SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY APPROVER";
}else{
	$query = "SELECT '$login_user' APPROVER";
}
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = $row['APPROVER'];
	$result_item_item['text'] = $row['APPROVER'];
	$result_item[] = $result_item_item;
}
$result['myApprover'] = '全部';
$result['allApprover'] = $result_item;



echo json_encode($result);
?>