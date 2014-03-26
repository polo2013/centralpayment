<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$orgCode = splitCode($login_user_org);

$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";
$FLOWTYPE = $_REQUEST['FLOWTYPE'] ? $_REQUEST['FLOWTYPE'] : "";
$FLOWSTAT = $_REQUEST['FLOWSTAT'] ? $_REQUEST['FLOWSTAT'] : "";
$BEGINNER = $_REQUEST['BEGINNER'] ? $_REQUEST['BEGINNER'] : "";
$BEGINTIME1 = $_REQUEST['BEGINTIME1'] ? $_REQUEST['BEGINTIME1'] : "";
$BEGINTIME2 = $_REQUEST['BEGINTIME2'] ? $_REQUEST['BEGINTIME2'] : "";

if(!isset($_REQUEST['page']) || !intval($_REQUEST['page']) || !isset($_REQUEST['rows']) || !intval($_REQUEST['rows'])){
	$pageNumber = 1;  //对出错进行默认处理:在url参数page不存在时，page不为10进制数时，默认为1
	$pageSize = 10;
}else{
	$pageNumber = $_REQUEST['page'];
	$pageSize = $_REQUEST['rows'];
}
$start = ($pageNumber - 1) * $pageSize; //从数据集第$start条开始取，注意数据集是从0开始的

//数据
$result = array();
$result_row = array();
$result_cell = array();

if ($FLOWTYPE == '87') {  //正大置地报销流程
	//默认条件：未删除、已完成
	$whereCondition = " a.DEL_FLAG = 0 AND a.END_TIME is not null AND a.FLOW_ID = 87 ";
	//组织
	$query = "SELECT `ORGDESC` FROM ZDCW_IMP_FROM_OA WHERE `ID` = ".$ORG;
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$whereCondition .= ' AND a.'.ltrim($row['ORGDESC']);
	}
	//其他条件
	if($BEGINNER != '')
		$whereCondition .= " AND a.BEGIN_USER like '%".$BEGINNER."%' ";
	if($BEGINTIME1 != '')
		$whereCondition .= " AND a.BEGIN_TIME >= '".$BEGINTIME1."' ";
	if($BEGINTIME2 != '')
		$whereCondition .= " AND a.BEGIN_TIME <= '".$BEGINTIME2."' ";
	
	OA_OpenConnection();	
	
	$oa_query = "SELECT COUNT(1) FROM FLOW_RUN a, flow_data_87 b WHERE a.RUN_ID = b.run_id AND ".$whereCondition;
	//writeLog($oa_query, 'ImpFromOA');
	
	//取总数
	$oa_cursor = exequery($OA_connection,$oa_query);
	if($oa_row = mysqli_fetch_array($oa_cursor)){
		$result['total'] = $oa_row[0];
	}else{
		$result['total'] = 0;
	}
	
	//分页
	$query_page = str_replace('COUNT(1)', '*', $oa_query)." ORDER BY b.run_id";
	$query_page .= " LIMIT $start,$pageSize";
	$cursor_page = exequery($OA_connection,$query_page);
	while($row_page = mysqli_fetch_array($cursor_page)){
		$note = "";
		$flag = "是";
		$result_cell = array();
		
		$result_cell['FLOWINFO'] = '['.trim($row_page['run_id']).']'.trim($row_page['run_name']);
		$result_cell['ORG'] = ltrim(rtrim($row_page['data_1'],','));
		$result_cell['APPLICANT'] = trim($row_page['data_2']);
		$result_cell['CURRENCY'] = trim("人民币");
		$result_cell['PAYMENT'] = trim($row_page['data_32']);
		$result_cell['TOTALAMT'] = trim($row_page['data_96']);
		$result_cell['PAYEE'] = trim($row_page['data_28']);
		
		//寻找收款人资料
		$query_payee = "SELECT BANK, ACCOUNT FROM SYS_USER WHERE `NAME` = '".$result_cell['PAYEE']."' UNION SELECT BANK, ACCOUNT FROM BIZ_PAYEE WHERE `NAME` = '".$result_cell['PAYEE']."'";
		$cursor_payee = exequery($connection,$query_payee);
		if($row_payee = mysqli_fetch_array($cursor_payee)){
			$result_cell['BANK'] = trim($row_payee['BANK']);
			$result_cell['ACCOUNT'] = trim($row_payee['ACCOUNT']);
			
			if ($result_cell['BANK'] == "" or $result_cell['ACCOUNT'] == ""){
				$note .= "本系统中该收款人的银行和帐号为空；";
				$flag = "否";
			}
		}else{
			$note .= "本系统没有该收款人；";
			$flag = "否";
		}
		
		
		if ($result_cell['ORG'] == ""){
			$note .= "组织机构为空；";
			$flag = "否";
		}
		if ($result_cell['APPLICANT'] == ""){
			$note .= "申请人为空；";
			$flag = "否";
		}
		if ($result_cell['PAYMENT'] == ""){
			$note .= "付款事由为空；";
			$flag = "否";
		}
		if ($result_cell['TOTALAMT'] == "" or $result_cell['TOTALAMT'] == 0){
			$note .= "金额为空或为零；";
			$flag = "否";
		}
		if ($result_cell['PAYEE'] == ""){
			$note .= "收款人为空；";
			$flag = "否";
		}
		

		$result_cell['NOTE'] = $note;
		$result_cell['FLAG'] = $flag;
		
		$result_row[] = $result_cell;
	}
	
	$result['rows'] = $result_row;
	

}	

echo json_encode($result);

?>