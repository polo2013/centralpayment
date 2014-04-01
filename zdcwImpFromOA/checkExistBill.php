<?php
include_once("../public/php/session.php");

$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";

$result = array();

if ($ORG != ""){
	$query = "select * from zdcw_payment_master where ORG = '".$ORG."' AND IMP_FLAG = 'IMP_FROM_OA' AND STAT in ('已导入','付款确认不通过')";
	$cursor = exequery($connection,$query);
	if ($row = mysqli_fetch_array($cursor)){
		$msg = "本系统中，在 “".$ORG."” 有如下单据可以与本次结果进行合并：<br /><br /><table border='1' style='text-align:center;' cellpadding='5'><tr><td>单据号</td><td>状态</td><td>导入人</td><td>导入时间</td></tr>";
		
		do {
			$msg .= "<tr><td>".$row['NUM']."</td><td>".$row['STAT']."</td><td>".$row['PAYIMPORT']."</td><td>".$row['PAYIMPORTTIME']."</td></tr>";
		} while ($row = mysqli_fetch_array($cursor));
		
		$msg .= "</table><br />是否继续？";
		$success = true;
	}else{
		$msg = "";
		$success = true;
	}
}else{
	$msg = "操作失败，请稍后重试！";
	$success = false;
}
$result['success'] = $success;
$result['message'] = $msg;
echo json_encode($result);
?>