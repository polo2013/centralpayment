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
	$whereCondition = ' 1=1 ';
	//读取配置表sys_setting的配置项pay_role
	$payrole = readSetting('public', 'pay_role');
	if ($payrole) {
		if (stripos($login_user_role_origin, $payrole) === false) {
		}else{
			$whereCondition = $whereCondition." AND STAT in ('已批准','付款审核通过','付款中','付款已完成','付款不通过') ";
		}
	}
}else if(getAuthInfo($login_user_role, '010', '查看所属机构单据权')){
	$whereCondition = "SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode'";
}else{
	$whereCondition = "(INPUTTER = '$login_user' OR CHECKER = '$login_user' OR APPROVER = '$login_user')";
}

if($NUM != '')
	$whereCondition .= " AND NUM='".$NUM."'";
if($ORG != '全部')
	$whereCondition .= " AND ORG='".$ORG."'";
if($BILLNUM != '')
	$whereCondition .= " AND BILLNUM='".$BILLNUM."'";
if($STAT != '全部')
	$whereCondition .= " AND STAT='".$STAT."'";
if($INPUTTER != '全部')
	$whereCondition .= " AND INPUTTER='".$INPUTTER."'";
if($INPUTTIMEBEGIN != '')
	$whereCondition .= " AND INPUTTIME>='".$INPUTTIMEBEGIN."'";
if($INPUTTIMEEND != '')
	$whereCondition .= " AND INPUTTIME<='".$INPUTTIMEEND."'";
if($CHECKER != '全部')
	$whereCondition .= " AND CHECKER='".$CHECKER."'";
if($CHECKTIMEBEGIN != '')
	$whereCondition .= " AND CHECKTIME>='".$CHECKTIMEBEGIN."'";
if($CHECKTIMEEND != '')
	$whereCondition .= " AND CHECKTIME<='".$CHECKTIMEEND."'";
if($APPROVER != '全部')
	$whereCondition .= " AND APPROVER='".$APPROVER."'";
if($APPROVETIMEBEGIN != '')
	$whereCondition .= " AND APPROVETIME>='".$APPROVETIMEBEGIN."'";
if($APPROVETIMEEND != '')
	$whereCondition .= " AND APPROVETIME<='".$APPROVETIMEEND."'";

//付款审核流程，特定的人看到特定组织的状态为已批准的单据
$specRole = readSetting('public','pay_check_role');
$specOrg = readSetting('public','pay_check_org');
if($specRole && $specOrg && hasSpec($login_user_role_origin,$specRole)){
	$whereCondition = "(".$whereCondition.") OR ( SUBSTR(`ORG`,2,INSTR(`ORG`,']')-2) in ('".implode("','", explode(",", $specOrg))."') AND STAT = '已批准')";
}

$query = "SELECT COUNT(1) FROM zdcw_payment_master WHERE ".$whereCondition;

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