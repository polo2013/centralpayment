<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];

$result = array('success' => false, 'message' => '操作失败！');

$flowid = urldecode($_REQUEST['flowid']);
$flowrunid = urldecode($_REQUEST['flowrunid']);
$num = urldecode($_REQUEST['num']);
$applicant = urldecode($_REQUEST['applicant']);
$amount = urldecode($_REQUEST['amount']);

$flowtype = '报销流程（OA流程ID_'.$flowid.'）';
$flowinfo = '['.$flowrunid.']';
$wherestr = "flowtype = '$flowtype' and flowinfo like '$flowinfo%' and num = '$num' and applicant = '$applicant' and totalamt = $amount; ";
$logstr = "flowtype = $flowid and flowinfo = $flowrunid and num = $num and applicant = $applicant and totalamt = $amount ";

$query = "select * from zdcw_imp_from_oa_rec where ".$wherestr;
$cursor = exequery($connection,$query);
if ($row = mysqli_fetch_array($cursor)){
	
	writeDBLog($login_user, '删除导入信息', '删除导入信息', $logstr, '删除导入信息');
	
	$query_del = "delete from zdcw_imp_from_oa_rec where ".$wherestr;
	if(exequery($connection,$query_del)){
		$result['success'] = true;
		$result['message'] = '删除该流程的导入信息成功！';
	}else{
		$result['success'] = false;
		$result['message'] = '删除该流程的导入信息失败！';
	}
}else{
	$result['success'] = false;
	$result['message'] = '没有找到该流程的导入信息！';
}

echo json_encode($result);
?>