<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$ROWS = $_REQUEST['ROWS'] ? $_REQUEST['ROWS'] : "";
$FLAG = $_REQUEST['FLAG'] ? $_REQUEST['FLAG'] : "";

if($FLAG == 'mutiPay'){$TOSTAT = '已付款'; $ACT = '付款'; }
if($FLAG == 'mutiCancelPay'){$TOSTAT = '已取消付款'; $ACT = '取消付款';}

//log
$logArray = array();
$logArray_new = array();
$logArray_new_item = array();
$logArray_old = array();
$logArray_old_item = array();

$query_all = "";
//解码成数组
$ROWSARR = json_decode($ROWS,TRUE);
if($ROWSARR != NULL){
	foreach($ROWSARR as $key => $value){
		//旧数据
		$query = "select * from zdcw_payment_detail where NUM = '".$value['NUM']."' AND ITEMNO = '".$value['ITEMNO']."'";
		$cursor = exequery($connection,$query);
		if($row = mysqli_fetch_array($cursor)){
			$logArray_old_item['NUM']=urlencode($row['NUM']);
			$logArray_old_item['ITEMNO']=urlencode($row['ITEMNO']);
			$logArray_old_item['ORG']=urlencode($row['ORG']);
			$logArray_old_item['APPLICANT']=urlencode($row['APPLICANT']);
			$logArray_old_item['PAYMENT']=urlencode($row['PAYMENT']);
			$logArray_old_item['CURRENCY']=urlencode($row['CURRENCY']);
			$logArray_old_item['TOTALAMT']=urlencode($row['TOTALAMT']);
			$logArray_old_item['PAYEE']=urlencode($row['PAYEE']);
			$logArray_old_item['BANK']=urlencode($row['BANK']);
			$logArray_old_item['ACCOUNT']=urlencode($row['ACCOUNT']);
			$logArray_old_item['NOTE']=urlencode($row['NOTE']);
			$logArray_old_item['PAYSTAT']=urlencode($row['PAYSTAT']);
			$logArray_old_item['PAYER']=urlencode($row['PAYER']);
			$logArray_old_item['PAYTIME']=urlencode($row['PAYTIME']);

			$logArray_old[] = $logArray_old_item;
		}
		
		$query_all .= "UPDATE zdcw_payment_detail SET PAYSTAT='$TOSTAT', PAYER='$login_user', PAYTIME=NOW() WHERE NUM = '".$value['NUM']."' AND ITEMNO = '".$value['ITEMNO']."'; ";

	}
}else{//没有任何明细
	$result['success'] = false;
	$result['message'] = '失败，没有明细！';
}

$queryResult = false;
$queryResult = exeMutiQuery($connection,$query_all);


//开始付款
if($queryResult){
	if($ROWSARR != NULL){
		foreach($ROWSARR as $key => $value){
			//新数据
			$query = "select * from zdcw_payment_detail where NUM = '".$value['NUM']."' AND ITEMNO = '".$value['ITEMNO']."'";
			$cursor = exequery($connection,$query);
			if($row = mysqli_fetch_array($cursor)){
				$logArray_new_item['NUM']=urlencode($row['NUM']);
				$logArray_new_item['ITEMNO']=urlencode($row['ITEMNO']);
				$logArray_new_item['ORG']=urlencode($row['ORG']);
				$logArray_new_item['APPLICANT']=urlencode($row['APPLICANT']);
				$logArray_new_item['PAYMENT']=urlencode($row['PAYMENT']);
				$logArray_new_item['CURRENCY']=urlencode($row['CURRENCY']);
				$logArray_new_item['TOTALAMT']=urlencode($row['TOTALAMT']);
				$logArray_new_item['PAYEE']=urlencode($row['PAYEE']);
				$logArray_new_item['BANK']=urlencode($row['BANK']);
				$logArray_new_item['ACCOUNT']=urlencode($row['ACCOUNT']);
				$logArray_new_item['NOTE']=urlencode($row['NOTE']);
				$logArray_new_item['PAYSTAT']=urlencode($row['PAYSTAT']);
				$logArray_new_item['PAYER']=urlencode($row['PAYER']);
				$logArray_new_item['PAYTIME']=urlencode($row['PAYTIME']);

				$logArray_new[] = $logArray_new_item;
			}
		}
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