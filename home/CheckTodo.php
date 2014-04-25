<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));

$result = array();
$result_row = array();
$result_detail = array();
$result_cell = array();

$orgcode = splitCode($login_user_org);
$hasOrgRight = getAuthInfo($login_user_role, '010', '查看所属机构单据权');
$hasAllRight = getAuthInfo($login_user_role, '010', '查看所有单据权');

$whereCondition = " (INPUTTER = '$login_user' OR CHECKER = '$login_user' OR APPROVER = '$login_user' OR PAYCHECKER = '$login_user' OR PAYIMPORT = '$login_user' OR PAYCONFIRM = '$login_user') ";

if($hasOrgRight)
	$whereCondition = " (SUBSTR(`ORG`,2, LENGTH('$orgcode'))='$orgcode') ";
if($hasAllRight){
	$whereCondition = " (1=1) ";
	//读取配置表sys_setting的配置项pay_role
	$payrole = readSetting('public', 'pay_role');
	if ($payrole) {
		if (stripos($login_user_role_origin, $payrole) === false) {
		}else{
			$whereCondition = " (STAT in ('已批准','付款审核通过','付款中','付款已完成','付款不通过')) ";
		}
	}
}

//付款审核流程，特定的人看到特定组织的状态为已批准的单据
$specRole = readSetting('public','pay_check_role');
$specOrg = readSetting('public','pay_check_org');
if($specRole && $specOrg && hasSpec($login_user_role_origin,$specRole)){
	$whereCondition = "(".$whereCondition." OR ( SUBSTR(`ORG`,2,INSTR(`ORG`,']')-2) in ('".implode("','", explode(",", $specOrg))."') AND STAT in ('已批准','付款审核通过','付款中','付款已完成','付款不通过')))";
}

//导入单据查看权
if(getAuthInfo($login_user_role, '010', '导入单据查看权')){
	$whereCondition = "(".$whereCondition." OR ( IMP_FLAG = 'IMP_FROM_OA'))";
}

$query = "SELECT STAT, COUNT(1) CNT FROM zdcw_payment_master WHERE ".$whereCondition." GROUP BY STAT ORDER BY CNT";

$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	unset($result_row);
	unset($result_detail);
	$result_row['STAT'] = $row['STAT'];
	$result_row['CNT'] = $row['CNT'];
	
	$query2 = "SELECT * FROM zdcw_payment_master WHERE ".$whereCondition." AND STAT = '".$row['STAT']."' ORDER BY NUM";
	$cursor2 = exequery($connection,$query2);
	while($row2 = mysqli_fetch_array($cursor2)){
		$result_cell['NUM'] = $row2['NUM'];
		$result_cell['BILLNUM'] = $row2['BILLNUM'];
		
		$query3 = "SELECT CURRENCY, SUM(TOTALAMT) TOTALAMT FROM zdcw_payment_detail WHERE NUM = '".$row2['NUM']."' GROUP BY CURRENCY";
		$cursor3 = exequery($connection,$query3);
		$j = 0;
		while($row3 = mysqli_fetch_array($cursor3)){
			if ($j == 0) {
				$result_cell['TOTALAMT'] = $row3['CURRENCY'].'：'.$row3['TOTALAMT'];
			}else{
				$result_cell['TOTALAMT'] = '多币种';
			}
			$j ++;
		}

		$result_detail[] = $result_cell;
	}
	
	$result_row['DETAIL'] = $result_detail;
	
	$result[] = $result_row;
}

echo json_encode($result);
?>