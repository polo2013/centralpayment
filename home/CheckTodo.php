<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];

$result = array();
$result_row = array();
$result_detail = array();
$result_cell = array();

$orgcode = splitCode($login_user_org);
$hasOrgRight = getAuthInfo($login_user_role, '010', '查看所属机构单据权');
$hasAllRight = getAuthInfo($login_user_role, '010', '查看所有单据权');

$query = "SELECT A.STAT, COUNT(1) CNT FROM zdcw_payment_master A, sys_stat B WHERE A.STAT = B.NAME AND (INPUTTER = '$login_user' OR CHECKER = '$login_user' OR APPROVER = '$login_user') GROUP BY A.STAT ORDER BY B.ORDERNO,B.CODE";
if($hasOrgRight)
	$query = "SELECT A.STAT, COUNT(1) CNT FROM zdcw_payment_master A, sys_stat B WHERE A.STAT = B.NAME AND SUBSTR(A.`ORG`,2, LENGTH('$orgcode'))='$orgcode' GROUP BY A.STAT ORDER BY B.ORDERNO, B.CODE";
if($hasAllRight)
	$query = "SELECT A.STAT, COUNT(1) CNT FROM zdcw_payment_master A, sys_stat B WHERE A.STAT = B.NAME GROUP BY A.STAT ORDER BY B.ORDERNO,B.CODE";

$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	unset($result_row);
	unset($result_detail);
	$query2 = "SELECT * FROM zdcw_payment_master WHERE (INPUTTER = '$login_user' OR CHECKER = '$login_user' OR APPROVER = '$login_user') AND STAT = '".$row['STAT']."' ORDER BY NUM";
	if($hasOrgRight)
		$query2 = "SELECT * FROM zdcw_payment_master WHERE SUBSTR(`ORG`,2, LENGTH('$orgcode'))='$orgcode' AND STAT = '".$row['STAT']."' ORDER BY NUM";
	if($hasAllRight)
		$query2 = "SELECT * FROM zdcw_payment_master WHERE STAT = '".$row['STAT']."' ORDER BY NUM";
	
	$result_row['STAT'] = $row['STAT'];
	$result_row['CNT'] = $row['CNT'];
	
	$cursor2 = exequery($connection,$query2);
	while($row2 = mysqli_fetch_array($cursor2)){
		$result_cell['NUM'] = $row2['NUM'];
		$result_cell['BILLNUM'] = $row2['BILLNUM'];
		
		$query3 = "SELECT SUM(TOTALAMT) TOTALAMT FROM zdcw_payment_detail WHERE NUM = '".$row2['NUM']."'";
		$cursor3 = exequery($connection,$query3);
		if($row3 = mysqli_fetch_array($cursor3)){
			$result_cell['TOTALAMT'] = $row3['TOTALAMT'];
		}else{
			$result_cell['TOTALAMT'] = '';
		}

		$result_detail[] = $result_cell;
	}
	
	$result_row['DETAIL'] = $result_detail;
	
	$result[] = $result_row;
}

echo json_encode($result);
?>