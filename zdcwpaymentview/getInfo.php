<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];

$result = array();
$result_row = array();
$result_cell = array();

if(!isset($_REQUEST['page']) || !intval($_REQUEST['page']) || !isset($_REQUEST['rows']) || !intval($_REQUEST['rows'])){
	$pageNumber = 1;  //对出错进行默认处理:在url参数page不存在时，page不为10进制数时，默认为1
	$pageSize = 10;
}else{
	$pageNumber = $_REQUEST['page'];
	$pageSize = $_REQUEST['rows'];
}
$start = ($pageNumber - 1) * $pageSize; //从数据集第$start条开始取，注意数据集是从0开始的

//数据权限
$orgcode = splitCode($login_user_org);
$query = "SELECT COUNT(1) FROM zdcw_payment_master WHERE SUBSTR(`ORG`,2, LENGTH('$orgcode'))='$orgcode' ORDER BY NUM";

if(getAuthInfo($login_user_role, '010', '查看所有单据权'))
	$query = "SELECT COUNT(1) FROM zdcw_payment_master ORDER BY NUM";

//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', '*', $query);
$query_page .= " LIMIT $start,$pageSize";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['NUM'] = $row_page['NUM'];
	$result_cell['ORG'] = $row_page['ORG'];
	$result_cell['BILLNUM'] = $row_page['BILLNUM'];
	$result_cell['STAT'] = $row_page['STAT'];
	$result_cell['INPUTTER'] = $row_page['INPUTTER'];
	$result_cell['INPUTTIME'] = ($row_page['INPUTTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['INPUTTIME']);
	$result_cell['CHECKER'] = $row_page['CHECKER'];
	$result_cell['CHECKTIME'] = ($row_page['CHECKTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['CHECKTIME']);
	$result_cell['APPROVER'] = $row_page['APPROVER'];
	$result_cell['APPROVETIME'] = ($row_page['APPROVETIME'] == '0000-00-00 00:00:00' ? '' : $row_page['APPROVETIME']);
	$result_cell['NOTE'] = $row_page['NOTE'];

	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

echo json_encode($result);

?>