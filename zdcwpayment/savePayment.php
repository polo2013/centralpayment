<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";
$BILLNUM = $_REQUEST['BILLNUM'] ? $_REQUEST['BILLNUM'] : "";

$INPUTTER = $_REQUEST['INPUTTER'] ? $_REQUEST['INPUTTER'] : "";
$CHECKER = $_REQUEST['CHECKER'] ? $_REQUEST['CHECKER'] : "";
$APPROVER = $_REQUEST['APPROVER'] ? $_REQUEST['APPROVER'] : "";
$PAYCHECKER = $_REQUEST['PAYCHECKER'] ? $_REQUEST['PAYCHECKER'] : "";

$INPUTTIME = $_REQUEST['INPUTTIME'] ? $_REQUEST['INPUTTIME'] : "";
$CHECKTIME = $_REQUEST['CHECKTIME'] ? $_REQUEST['CHECKTIME'] : "";
$APPROVETIME = $_REQUEST['APPROVETIME'] ? $_REQUEST['APPROVETIME'] : "";
$PAYCHECKTIME = $_REQUEST['PAYCHECKTIME'] ? $_REQUEST['PAYCHECKTIME'] : "";

$NOTE = $_REQUEST['NOTE'] ? $_REQUEST['NOTE'] : "";
$STAT = $_REQUEST['STAT'] ? $_REQUEST['STAT'] : "";
$OPERATION = $_REQUEST['OPERATION'] ? $_REQUEST['OPERATION'] : "";

$PAYMENTROWS = $_REQUEST['PAYMENTROWS'] ? $_REQUEST['PAYMENTROWS'] : "";
$FLAG = $_REQUEST['FLAG'] ? $_REQUEST['FLAG'] : "";
$result = array('success' => false, 'message' => '保存失败！');

if ($OPERATION == '无操作') {
	$result['success'] = false;
	$result['message'] = '无操作！';
}else{

//改变stat
$TO_STAT = getNextSTAT($OPERATION);
//操作人和时间

switch ($OPERATION)
{
	case "提交审核":
		$INPUTTER = $login_user;
		$INPUTTIME = date('Y-m-d H:i:s');
		break;
	case "审核不通过":
	case "审核通过":
		$CHECKER = $login_user;
		$CHECKTIME = date('Y-m-d H:i:s');
		break;
	case "批准不通过":
	case "批准通过":
		$APPROVER = $login_user;
		$APPROVETIME = date('Y-m-d H:i:s');
		break;
	case "付款审核不通过":
	case "付款审核通过":
		$PAYCHECKER = $login_user;
		$PAYCHECKTIME = date('Y-m-d H:i:s');
		break;
}

//check value
$chk = checkValueAtDB($MODULENO,$_REQUEST,$FLAG);
if ($chk['ok']){
	//log
	$logArray = array();
	$logArray_new = array();
	$logArray_old = array();
	
	//旧数据
	$logArray_old_item = array();
	$query = "select * from zdcw_payment_master where NUM = '$NUM'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$logArray_old_item['NUM']=urlencode($row['NUM']);
		$logArray_old_item['ORG']=urlencode($row['ORG']);
		$logArray_old_item['BILLNUM']=urlencode($row['BILLNUM']);
		$logArray_old_item['STAT']=urlencode($row['STAT']);
		$logArray_old_item['INPUTTER']=urlencode($row['INPUTTER']);
		$logArray_old_item['INPUTTIME']=urlencode($row['INPUTTIME']);
		$logArray_old_item['CHECKER']=urlencode($row['CHECKER']);
		$logArray_old_item['CHECKTIME']=urlencode($row['CHECKTIME']);
		$logArray_old_item['APPROVER']=urlencode($row['APPROVER']);
		$logArray_old_item['APPROVETIME']=urlencode($row['APPROVETIME']);
		$logArray_old_item['NOTE']=urlencode($row['NOTE']);
		$logArray_old_item['PAYCHECKER']=urlencode($row['PAYCHECKER']);
		$logArray_old_item['PAYCHECKTIME']=urlencode($row['PAYCHECKTIME']);
	}
	$logArray_old['MASTER'] = $logArray_old_item;
	
	$logArray_old_item = array();
	$logArray_old_item_item = array();
	$query = "select * from zdcw_payment_detail where NUM = '$NUM'";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$logArray_old_item_item['NUM']=urlencode($row['NUM']);
		$logArray_old_item_item['ITEMNO']=urlencode($row['ITEMNO']);
		$logArray_old_item_item['ORG']=urlencode($row['ORG']);
		$logArray_old_item_item['APPLICANT']=urlencode($row['APPLICANT']);
		$logArray_old_item_item['PAYMENT']=urlencode($row['PAYMENT']);
		$logArray_old_item_item['CURRENCY']=urlencode($row['CURRENCY']);
		$logArray_old_item_item['TOTALAMT']=urlencode($row['TOTALAMT']);
		$logArray_old_item_item['PAYEE']=urlencode($row['PAYEE']);
		$logArray_old_item_item['BANK']=urlencode($row['BANK']);
		$logArray_old_item_item['ACCOUNT']=urlencode($row['ACCOUNT']);
		$logArray_old_item_item['NOTE']=urlencode($row['NOTE']);
		$logArray_old_item_item['PAYSTAT']=urlencode($row['PAYSTAT']);
		$logArray_old_item_item['PAYER']=urlencode($row['PAYER']);
		$logArray_old_item_item['PAYTIME']=urlencode($row['PAYTIME']);
		
		$logArray_old_item[] = $logArray_old_item_item;
	}
	$logArray_old['DETAIL'] = $logArray_old_item;
	
	//新数据
	$query = "";
	$queryResult = false;

	$query = "delete from zdcw_payment_master where NUM = '$NUM'; delete from zdcw_payment_detail where NUM = '$NUM'; "
	."insert into zdcw_payment_master(`NUM`,`ORG`,`BILLNUM`,`STAT`,`INPUTTER`,`INPUTTIME`,`CHECKER`,`CHECKTIME`,`APPROVER`,`APPROVETIME`,`NOTE`,`PAYCHECKER`,`PAYCHECKTIME`) "
	."values ('$NUM','$ORG','$BILLNUM','$TO_STAT','$INPUTTER','$INPUTTIME','$CHECKER','$CHECKTIME','$APPROVER','$APPROVETIME','$NOTE','$PAYCHECKER','$PAYCHECKTIME'); ";

	//解码成数组
	$PAYMENTROWSARR = json_decode($PAYMENTROWS,TRUE);
	if($PAYMENTROWSARR != NULL){
		foreach($PAYMENTROWSARR as $key => $value){
			$DTL_ITEMNO = $value['ITEMNO'];
			$DTL_ORG = $value['ORG'];
			$DTL_APPLICANT = $value['APPLICANT'];
			$DTL_PAYMENT = $value['PAYMENT'];
			$DTL_CURRENCY = $value['CURRENCY'];
			$DTL_TOTALAMT = $value['TOTALAMT'];
			$DTL_PAYEE = $value['PAYEE'];
			$DTL_BANK = $value['BANK'];
			$DTL_ACCOUNT = $value['ACCOUNT'];
			$DTL_NOTE = $value['NOTE'];
			$DTL_PAYSTAT = $value['PAYSTAT'];
			$DTL_PAYER = $value['PAYER'];
			$DTL_PAYTIME = $value['PAYTIME'];

			$query .= "insert into zdcw_payment_detail(`NUM`,`ITEMNO`,`ORG`,`APPLICANT`,`PAYMENT`,`TOTALAMT`,`PAYEE`,`BANK`,`ACCOUNT`,`NOTE`,`PAYSTAT`,`PAYER`,`PAYTIME`,`CURRENCY`) " 
			." VALUES ('$NUM','$DTL_ITEMNO','$DTL_ORG','$DTL_APPLICANT','$DTL_PAYMENT','$DTL_TOTALAMT','$DTL_PAYEE','$DTL_BANK','$DTL_ACCOUNT','$DTL_NOTE','$DTL_PAYSTAT','$DTL_PAYER','$DTL_PAYTIME','$DTL_CURRENCY'); ";
			
		}
		
		//开始更新//多条语句一起执行
		$queryResult = exeMutiQuery($connection,$query);
		
	}else{//没有任何明细
		$result['success'] = false;
		$result['message'] = '单据明细不允许为空!';
	}

	
	if($queryResult){
		//log
		$logArray_new_item = array();
		$query = "select * from zdcw_payment_master where NUM = '$NUM'";
		$cursor = exequery($connection,$query);
		if($row = mysqli_fetch_array($cursor)){
			$logArray_new_item['NUM']=urlencode($row['NUM']);
			$logArray_new_item['ORG']=urlencode($row['ORG']);
			$logArray_new_item['BILLNUM']=urlencode($row['BILLNUM']);
			$logArray_new_item['STAT']=urlencode($row['STAT']);
			$logArray_new_item['INPUTTER']=urlencode($row['INPUTTER']);
			$logArray_new_item['INPUTTIME']=urlencode($row['INPUTTIME']);
			$logArray_new_item['CHECKER']=urlencode($row['CHECKER']);
			$logArray_new_item['CHECKTIME']=urlencode($row['CHECKTIME']);
			$logArray_new_item['APPROVER']=urlencode($row['APPROVER']);
			$logArray_new_item['APPROVETIME']=urlencode($row['APPROVETIME']);
			$logArray_new_item['NOTE']=urlencode($row['NOTE']);
			$logArray_new_item['PAYCHECKER']=urlencode($row['PAYCHECKER']);
			$logArray_new_item['PAYCHECKTIME']=urlencode($row['PAYCHECKTIME']);
		}
		$logArray_new['MASTER'] = $logArray_new_item;
		
		$logArray_new_item = array();
		$logArray_new_item_item = array();
		$query = "select * from zdcw_payment_detail where NUM = '$NUM'";
		$cursor = exequery($connection,$query);
		while($row = mysqli_fetch_array($cursor)){
			$logArray_new_item_item['NUM']=urlencode($row['NUM']);
			$logArray_new_item_item['ITEMNO']=urlencode($row['ITEMNO']);
			$logArray_new_item_item['ORG']=urlencode($row['ORG']);
			$logArray_new_item_item['APPLICANT']=urlencode($row['APPLICANT']);
			$logArray_new_item_item['PAYMENT']=urlencode($row['PAYMENT']);
			$logArray_new_item_item['CURRENCY']=urlencode($row['CURRENCY']);
			$logArray_new_item_item['TOTALAMT']=urlencode($row['TOTALAMT']);
			$logArray_new_item_item['PAYEE']=urlencode($row['PAYEE']);
			$logArray_new_item_item['BANK']=urlencode($row['BANK']);
			$logArray_new_item_item['ACCOUNT']=urlencode($row['ACCOUNT']);
			$logArray_new_item_item['NOTE']=urlencode($row['NOTE']);
			$logArray_new_item_item['PAYSTAT']=urlencode($row['PAYSTAT']);
			$logArray_new_item_item['PAYER']=urlencode($row['PAYER']);
			$logArray_new_item_item['PAYTIME']=urlencode($row['PAYTIME']);
			
			$logArray_new_item[] = $logArray_new_item_item;
		}
		$logArray_new['DETAIL'] = $logArray_new_item;
		
		$logArray['OLDVALUE']=$logArray_old;
		$logArray['NEWVALUE']=$logArray_new;
	
		writeDBLog($login_user, $MODULEOBJ, $OPERATION, urldecode(json_encode($logArray)), $MODULETITLE);
		$result['success'] = true;
		$result['message'] = '保存成功！';
	}
	
}else{
	$result['success'] = false;
	$result['message'] = $chk['msg'];
}
}
echo json_encode($result);
?>