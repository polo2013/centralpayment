<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
//$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role_origin = $_SESSION['LOGIN_USER_ROLE'];
$login_user_role = implode("','", explode(",", $login_user_role_origin));


$result = array();
$result_item = array();
$result_item_item = array();

$STAT = $_REQUEST['STAT'];
$ORG = $_REQUEST['ORG'];


$query = "SELECT B.* FROM SYS_STAT A, SYS_STAT B WHERE A.NAME = '$STAT' AND FIND_IN_SET(A.CODE,B.FIRESTAT) >= 1 ORDER BY B.ORDERNO, B.CODE";
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){	
	$result_item_item['value'] = $row['FIREACT'];
	$result_item_item['text'] = $row['FIREACT'];
	
	switch ($STAT){
		case '录入' :
			if(getAuthInfo($login_user_role, '009', '录入权')){
				if($row['FIREACT'] == '录入暂存'){
					$result_item[] = $result_item_item;
				}
				if($row['FIREACT'] == '提交审核'){
					$result_item[] = $result_item_item;
				}
				if(getAuthInfo($login_user_role, '009', '审核权') && $row['FIREACT'] == '审核通过'){
					$result_item[] = $result_item_item;
				}
			}
			break;
		case '已审核' :
			if(getAuthInfo($login_user_role, '009', '批准权')){
				if($row['FIREACT'] == '批准通过' || $row['FIREACT'] == '批准不通过')
					$result_item[] = $result_item_item;
			}
			if(getAuthInfo($login_user_role, '009', '反审核权')){
				if($row['FIREACT'] == '反审核')
					$result_item[] = $result_item_item;
			}
			break;
		case '已批准' :
			if($login_user_org == $ORG){
				if(getAuthInfo($login_user_role, '009', '反批准权')){
					if($row['FIREACT'] == '反批准')
						$result_item[] = $result_item_item;
				}
			}
			$specOrg = readSetting('public','pay_check_org');
			if ($specOrg && hasSpec($specOrg,splitCode($ORG))) {
				if($row['FIREACT'] == '付款审核通过' || $row['FIREACT'] == '付款审核不通过'){
					$specRole = readSetting('public','pay_check_role');
					if($specRole && hasSpec($login_user_role_origin,$specRole)){
						$result_item[] = $result_item_item;
					}
				}
			}else{
				if(getAuthInfo($login_user_role, '009', '付款权')){
					if($row['FIREACT'] == '付款开始')
						$result_item[] = $result_item_item;
				}
			}
			
			break;
		default:
			$result_item[] = $result_item_item;
	}
}

if(!empty($result_item)){
	$result['hasOperation'] = true;
	$result['operation'] = $result_item;
	$result['firstone'] = $result_item[0]['value'];
	if(count($result_item) == 1){
		$result['onlyOne'] = true;
	}else{
		$result['onlyOne'] = false;
	}
	
	
}else{
	$result['hasOperation'] = false;
	$result['info'] = '无操作';
}


echo json_encode($result);
?>