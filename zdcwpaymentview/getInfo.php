<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));

$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$orgCode = splitCode($login_user_org);

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";
$BILLNUM = $_REQUEST['BILLNUM'] ? $_REQUEST['BILLNUM'] : "";
$STAT = $_REQUEST['STAT'] ? $_REQUEST['STAT'] : "";

$INPUTTER = $_REQUEST['INPUTTER'] ? $_REQUEST['INPUTTER'] : "";
$CHECKER = $_REQUEST['CHECKER'] ? $_REQUEST['CHECKER'] : "";
$APPROVER = $_REQUEST['APPROVER'] ? $_REQUEST['APPROVER'] : "";

$INPUTTIMEBEGIN = $_REQUEST['INPUTTIMEBEGIN'] ? $_REQUEST['INPUTTIMEBEGIN'] : "";
$INPUTTIMEEND = $_REQUEST['INPUTTIMEEND'] ? $_REQUEST['INPUTTIMEEND'] : "";
$CHECKTIMEBEGIN = $_REQUEST['CHECKTIMEBEGIN'] ? $_REQUEST['CHECKTIMEBEGIN'] : "";
$CHECKTIMEEND = $_REQUEST['CHECKTIMEEND'] ? $_REQUEST['CHECKTIMEEND'] : "";
$APPROVETIMEBEGIN = $_REQUEST['APPROVETIMEBEGIN'] ? $_REQUEST['APPROVETIMEBEGIN'] : "";
$APPROVETIMEEND = $_REQUEST['APPROVETIMEEND'] ? $_REQUEST['APPROVETIMEEND'] : "";

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

//数据
if(getAuthInfo($login_user_role, '010', '查看所有单据权')){
	$whereCondition = '1=1';
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$whereCondition = "SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode'";
}else{
	$whereCondition = "(INPUTTER = '$login_user' OR CHECKER = '$login_user' OR APPROVER = '$login_user')";
}

$query = "SELECT COUNT(1) FROM zdcw_payment_master WHERE ".$whereCondition;
if($NUM != '')
	$query = $query." AND NUM='".$NUM."'";
if($ORG != '全部')
	$query = $query." AND ORG='".$ORG."'";
if($BILLNUM != '')
	$query = $query." AND BILLNUM='".$BILLNUM."'";
if($STAT != '全部')
	$query = $query." AND STAT='".$STAT."'";
if($INPUTTER != '全部')
	$query = $query." AND INPUTTER='".$INPUTTER."'";
if($INPUTTIMEBEGIN != '')
	$query = $query." AND INPUTTIME>='".$INPUTTIMEBEGIN."'";
if($INPUTTIMEEND != '')
	$query = $query." AND INPUTTIME<='".$INPUTTIMEEND."'";
if($CHECKER != '全部')
	$query = $query." AND CHECKER='".$CHECKER."'";
if($CHECKTIMEBEGIN != '')
	$query = $query." AND CHECKTIME>='".$CHECKTIMEBEGIN."'";
if($CHECKTIMEEND != '')
	$query = $query." AND CHECKTIME<='".$CHECKTIMEEND."'";
if($APPROVER != '全部')
	$query = $query." AND APPROVER='".$APPROVER."'";
if($APPROVETIMEBEGIN != '')
	$query = $query." AND APPROVETIME>='".$APPROVETIMEBEGIN."'";
if($APPROVETIMEEND != '')
	$query = $query." AND APPROVETIME<='".$APPROVETIMEEND."'";


//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', '*', $query)." ORDER BY NUM";
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