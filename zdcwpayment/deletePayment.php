<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";

$result = array('success' => false, 'message' => '保存失败！');

//check value
$IMP_FLAG = "";
$query = "select * from zdcw_payment_master where NUM = '$NUM'";
$cursor = exequery($connection,$query);
if($row = mysqli_fetch_array($cursor)){
	$IMP_FLAG = $row['IMP_FLAG'];
	if (($row['INPUTTER'] != $login_user && $IMP_FLAG == "") || ($row['PAYIMPORT'] != $login_user && $IMP_FLAG == "IMP_FROM_OA")) {
		$result['success'] = false;
		$result['message'] = '只能删除自己录入或导入的单据！';
	}else{
		//log
		$logArray = array();
		$logArray_new = array();
		$logArray_old = array();
		//旧数据
		$logArray_old_item = array();
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
		$logArray_old['MASTER'] = $logArray_old_item;
		
		$logArray_old_item = array();
		$logArray_old_item_item = array();
		$query_detail = "select * from zdcw_payment_detail where NUM = '$NUM'";
		$cursor_detail = exequery($connection,$query_detail);
		while($row_detail = mysqli_fetch_array($cursor_detail)){
			$logArray_old_item_item['NUM']=urlencode($row_detail['NUM']);
			$logArray_old_item_item['ITEMNO']=urlencode($row_detail['ITEMNO']);
			$logArray_old_item_item['ORG']=urlencode($row_detail['ORG']);
			$logArray_old_item_item['APPLICANT']=urlencode($row_detail['APPLICANT']);
			$logArray_old_item_item['PAYMENT']=urlencode($row_detail['PAYMENT']);
			$logArray_old_item_item['CURRENCY']=urlencode($row_detail['CURRENCY']);
			$logArray_old_item_item['TOTALAMT']=urlencode($row_detail['TOTALAMT']);
			$logArray_old_item_item['PAYEE']=urlencode($row_detail['PAYEE']);
			$logArray_old_item_item['BANK']=urlencode($row_detail['BANK']);
			$logArray_old_item_item['ACCOUNT']=urlencode($row_detail['ACCOUNT']);
			$logArray_old_item_item['NOTE']=urlencode($row_detail['NOTE']);
			$logArray_old_item_item['PAYSTAT']=urlencode($row_detail['PAYSTAT']);
			$logArray_old_item_item['PAYER']=urlencode($row_detail['PAYER']);
			$logArray_old_item_item['PAYTIME']=urlencode($row_detail['PAYTIME']);
			
			$logArray_old_item[] = $logArray_old_item_item;
		}
		$logArray_old['DETAIL'] = $logArray_old_item;

		$logArray['OLDVALUE']=$logArray_old;

		
		$query_del = "delete from zdcw_payment_detail where NUM = '$NUM'; delete from zdcw_payment_master where NUM = '$NUM'; ";
		
		if ($IMP_FLAG == "IMP_FROM_OA") {
			$query_del .= "delete from ZDCW_IMP_FROM_OA_REC where NUM = '$NUM'; ";
		}
		
		//开始删除//多条语句一起执行
		$queryResult = exeMutiQuery($connection,$query_del);
		if($queryResult){
			writeDBLog($login_user, $MODULEOBJ, '删除', urldecode(json_encode($logArray)), $MODULETITLE);
			$result['success'] = true;
			$result['message'] = '删除成功！';
		}
	}
	
}else{
	$result['success'] = false;
	$result['message'] = '没有找到该单据！';
}


echo json_encode($result);
?>