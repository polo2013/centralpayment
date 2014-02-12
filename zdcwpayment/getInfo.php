<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];

$result = array();
$result_row = array();
$result_cell = array();
$sumAllTotal = 0.00;

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";

$query = "SELECT * FROM zdcw_payment_master WHERE `NUM`='$NUM'";
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['NUM'] = $ROW['NUM'];
	$result['ORG'] = $ROW['ORG'];
	$result['BILLNUM'] = $ROW['BILLNUM'];
	$result['STAT'] = $ROW['STAT'];
	$result['INPUTTER'] = $ROW['INPUTTER'];
	$result['INPUTTIME'] = ($ROW['INPUTTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['INPUTTIME']);
	$result['CHECKER'] = $ROW['CHECKER'];
	$result['CHECKTIME'] = ($ROW['CHECKTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['CHECKTIME']);
	$result['APPROVER'] = $ROW['APPROVER'];
	$result['APPROVETIME'] = ($ROW['APPROVETIME'] == '0000-00-00 00:00:00' ? '' : $ROW['APPROVETIME']);
	$result['NOTE'] = $ROW['NOTE'];	
	$result['PAYCHECKER'] = $ROW['PAYCHECKER'];
	$result['PAYCHECKTIME'] = ($ROW['PAYCHECKTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['PAYCHECKTIME']);
}


$query_page = "SELECT * FROM zdcw_payment_detail WHERE `NUM`='$NUM' ORDER BY (ITEMNO+0)";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['NUM'] = $row_page['NUM'];
	$result_cell['ITEMNO'] = $row_page['ITEMNO'];
	$result_cell['ORG'] = $row_page['ORG'];
	$result_cell['APPLICANT'] = $row_page['APPLICANT'];
	$result_cell['PAYMENT'] = $row_page['PAYMENT'];
	$result_cell['TOTALAMT'] = $row_page['TOTALAMT'];
	$result_cell['PAYEE'] = $row_page['PAYEE'];
	$result_cell['BANK'] = $row_page['BANK'];
	$result_cell['ACCOUNT'] = $row_page['ACCOUNT'];
	$result_cell['NOTE'] = $row_page['NOTE'];
	$result_cell['PAYSTAT'] = $row_page['PAYSTAT'];
	$result_cell['PAYER'] = $row_page['PAYER'];
	$result_cell['PAYTIME'] = ($row_page['PAYTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['PAYTIME']);

	$sumAllTotal = $sumAllTotal + $row_page['TOTALAMT'];
	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

$result_row = array();
$result_cell = array();
$result_cell['ORG'] = '总计：';
$result_cell['TOTALAMT'] = number_format($sumAllTotal, 2, '.', '');
$result_row[] = $result_cell;
$result['footer'] = $result_row;

echo json_encode($result);

?>