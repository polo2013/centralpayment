<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];

$result = array();
$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";
$ROWS = $_REQUEST['ROWS'] ? $_REQUEST['ROWS'] : "";
$MERGE = $_REQUEST['MERGE'] ? $_REQUEST['MERGE'] : "";
$FLOWTYPE = $_REQUEST['FLOWTYPE'] ? $_REQUEST['FLOWTYPE'] : "";

//解码成数组
$ROWSARR = json_decode($ROWS,TRUE);
if($ROWSARR != NULL){
	//生成单号
	$NUM = get_microsecond();
	//编号
	$para = date('Ymd',time());
	$query = "select LPAD(MAX(`BILLNUM`)+1,LENGTH(`BILLNUM`),'0') NewNumber from zdcw_payment_master where ORG = '$ORG' and SUBSTR(`BILLNUM`,1,8) = '$para'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		if ($row['NewNumber']){
			$BILLNUM = $row['NewNumber'];
		}else{
			$BILLNUM = $para.'0001';
		}
	}else{
		$BILLNUM = $para.'0001';
	}
	//状态
	$TO_STAT = "已导入";
	//录入时间
	$importtime = date('Y-m-d H:i:s', time());
	
	//新数据
	$query = "";
	$queryResult = false;
	$query_rec = "";
	$queryResult_rec = false;
	
	
	//汇总
	$query = "delete from zdcw_payment_master where NUM = '$NUM'; delete from zdcw_payment_detail where NUM = '$NUM'; "
	."insert into zdcw_payment_master(`NUM`,`ORG`,`BILLNUM`,`STAT`,`INPUTTER`,`INPUTTIME`,`CHECKER`,`CHECKTIME`,`APPROVER`,`APPROVETIME`,`NOTE`,`PAYCHECKER`,`PAYCHECKTIME`,`PAYIMPORT`,`PAYIMPORTTIME`,`PAYCONFIRM`,`PAYCONFIRMTIME`,`IMP_FLAG`) "
	."values ('$NUM','$ORG','$BILLNUM','$TO_STAT','','','','','','','','','','$login_user','$importtime','','','IMP_FROM_OA'); ";

	
	//明细
	foreach($ROWSARR as $key => $value){
		$DTL_FLOWINFO = $value['FLOWINFO'];
		$DTL_ITEMNO = (string)($key + 1);
		$DTL_ORG = $value['ORG'];
		$DTL_APPLICANT = $value['APPLICANT'];
		$DTL_PAYMENT = $value['PAYMENT'];
		$DTL_CURRENCY = $value['CURRENCY'];
		$DTL_TOTALAMT = $value['TOTALAMT'];
		$DTL_PAYEE = $value['PAYEE'];
		$DTL_BANK = $value['BANK'];
		$DTL_ACCOUNT = $value['ACCOUNT'];
		$DTL_NOTE = $value['NOTE'];
		$DTL_PAYSTAT = '未付款';
		$DTL_PAYER = '';
		$DTL_PAYTIME = '';

		$query .= "insert into zdcw_payment_detail(`NUM`,`ITEMNO`,`ORG`,`APPLICANT`,`PAYMENT`,`TOTALAMT`,`PAYEE`,`BANK`,`ACCOUNT`,`NOTE`,`PAYSTAT`,`PAYER`,`PAYTIME`,`CURRENCY`) "
		." VALUES ('$NUM','$DTL_ITEMNO','$DTL_ORG','$DTL_APPLICANT','$DTL_PAYMENT','$DTL_TOTALAMT','$DTL_PAYEE','$DTL_BANK','$DTL_ACCOUNT','$DTL_NOTE','$DTL_PAYSTAT','$DTL_PAYER','$DTL_PAYTIME','$DTL_CURRENCY'); ";

		$query_rec .= "delete from ZDCW_IMP_FROM_OA_REC where `FLOWTYPE` = '$FLOWTYPE' AND `FLOWINFO` = '$DTL_FLOWINFO'; "
		."insert into ZDCW_IMP_FROM_OA_REC(`FLOWTYPE`,`FLOWINFO`,`NUM`,`ITEMNO`,`ORG`,`APPLICANT`,`PAYMENT`,`TOTALAMT`,`PAYEE`,`BANK`,`ACCOUNT`,`NOTE`,`CURRENCY`) "
		." VALUES ('$FLOWTYPE','$DTL_FLOWINFO','$NUM','$DTL_ITEMNO','$DTL_ORG','$DTL_APPLICANT','$DTL_PAYMENT','$DTL_TOTALAMT','$DTL_PAYEE','$DTL_BANK','$DTL_ACCOUNT','$DTL_NOTE','$DTL_CURRENCY'); ";
		
		
	}
	
	//开始更新//多条语句一起执行
	$queryResult = exeMutiQuery($connection,$query);
	$queryResult_rec = exeMutiQuery($connection,$query_rec);
	
	if ($MERGE == "merge") {
		
	}
	
	if($queryResult && $queryResult_rec){
		$result['success'] = true;
		$result['message'] = '生成单据成功！单据号：'.$NUM.'。点击确定查看单据。';
		$result['num'] = $NUM;
	}else{
		$result['success'] = false;
		$result['message'] = '生成单据失败！';
	}
}else{//没有任何明细
	$result['success'] = false;
	$result['message'] = '生成单据失败，没有明细！';
}

echo json_encode($result);
?>