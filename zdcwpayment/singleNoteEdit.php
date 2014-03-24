<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
$ITEMNO = $_REQUEST['ITEMNO'] ? $_REQUEST['ITEMNO'] : "";
$NOTE = $_REQUEST['NOTE'] ? $_REQUEST['NOTE'] : "";

$ACT = '更新备注';

//log
$logArray = array();
$logArray_new = array();
$logArray_old = array();
//旧数据
$query = "select * from zdcw_payment_detail where NUM = '$NUM' AND ITEMNO = '$ITEMNO'";
$cursor = exequery($connection,$query);
if($row = mysqli_fetch_array($cursor)){
	$logArray_old['NUM']=urlencode($row['NUM']);
	$logArray_old['ITEMNO']=urlencode($row['ITEMNO']);
	$logArray_old['ORG']=urlencode($row['ORG']);
	$logArray_old['APPLICANT']=urlencode($row['APPLICANT']);
	$logArray_old['PAYMENT']=urlencode($row['PAYMENT']);
	$logArray_old['CURRENCY']=urlencode($row['CURRENCY']);
	$logArray_old['TOTALAMT']=urlencode($row['TOTALAMT']);
	$logArray_old['PAYEE']=urlencode($row['PAYEE']);
	$logArray_old['BANK']=urlencode($row['BANK']);
	$logArray_old['ACCOUNT']=urlencode($row['ACCOUNT']);
	$logArray_old['NOTE']=urlencode($row['NOTE']);
	$logArray_old['PAYSTAT']=urlencode($row['PAYSTAT']);
	$logArray_old['PAYER']=urlencode($row['PAYER']);
	$logArray_old['PAYTIME']=urlencode($row['PAYTIME']);
}

//开始付款
$query = "UPDATE zdcw_payment_detail SET NOTE='$NOTE' WHERE NUM = '$NUM' AND ITEMNO = '$ITEMNO'";
if(exequery($connection,$query)){

	//新数据
	$query = "select * from zdcw_payment_detail where NUM = '$NUM' AND ITEMNO = '$ITEMNO'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$logArray_new['NUM']=urlencode($row['NUM']);
		$logArray_new['ITEMNO']=urlencode($row['ITEMNO']);
		$logArray_new['ORG']=urlencode($row['ORG']);
		$logArray_new['APPLICANT']=urlencode($row['APPLICANT']);
		$logArray_new['PAYMENT']=urlencode($row['PAYMENT']);
		$logArray_new['CURRENCY']=urlencode($row['CURRENCY']);
		$logArray_new['TOTALAMT']=urlencode($row['TOTALAMT']);
		$logArray_new['PAYEE']=urlencode($row['PAYEE']);
		$logArray_new['BANK']=urlencode($row['BANK']);
		$logArray_new['ACCOUNT']=urlencode($row['ACCOUNT']);
		$logArray_new['NOTE']=urlencode($row['NOTE']);
		$logArray_new['PAYSTAT']=urlencode($row['PAYSTAT']);
		$logArray_new['PAYER']=urlencode($row['PAYER']);
		$logArray_new['PAYTIME']=urlencode($row['PAYTIME']);
	}
	
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	$result['success'] = true;
	$result['message'] = $ACT.'成功！';
	writeDBLog($login_user, $MODULEOBJ, $ACT, urldecode(json_encode($logArray)), $MODULETITLE);
	
}else{
	$result['success'] = false;
	$result['message'] = $ACT.'失败！';
}

echo json_encode($result);
?>