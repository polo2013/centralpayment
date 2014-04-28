<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '删除失败！');
$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
$ROWS = $_REQUEST['ROWS'] ? $_REQUEST['ROWS'] : "";


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
		$query_all = "";
		//解码成数组
		$ROWSARR = json_decode($ROWS,TRUE);
		if($ROWSARR != NULL){
			foreach($ROWSARR as $key => $value){
				$query_all .= "delete from zdcw_payment_detail WHERE NUM = '".$value['NUM']."' AND ITEMNO = '".$value['ITEMNO']."'; ";

				if ($IMP_FLAG == "IMP_FROM_OA") {
					$query_all .= "delete from ZDCW_IMP_FROM_OA_REC where NUM = '".$value['NUM']."' AND ITEMNO = '".$value['ITEMNO']."'; ";
				}
			}

			$queryResult = false;
			$queryResult = exeMutiQuery($connection,$query_all);

			//开始
			if($queryResult){
				$result['success'] = true;
				$result['message'] = '删除成功！';
				writeDBLog($login_user, $MODULEOBJ, '删除', urldecode($ROWS), $MODULETITLE);
			}

		}else{//没有任何明细
			$result['success'] = false;
			$result['message'] = '失败，没有明细！';
		}
	}
}else{
	$result['success'] = false;
	$result['message'] = '没有找到该单据！';
}

echo json_encode($result);
?>