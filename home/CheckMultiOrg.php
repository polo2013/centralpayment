<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];

$result = array();
$result_row = array();
$result_cell = array();
$otherOrgArr = array();

$result['CurrentOrg'] = $login_user_org;

$query = "select * from sys_user where code = '$login_user_code'";
$cursor = exequery($connection,$query);
if($row = mysqli_fetch_array($cursor)){
	$isMore = stripos($row['ORG'],'|');
	if($isMore){
		$result['Multi'] = true;
		$orgArr = explode("|",$row['ORG']);
		$roleArr = explode("|",$row['ROLE']);
		
		foreach ($orgArr as $key => $value){
			if ($value == $login_user_org){
				continue;
			}else{
				$result_cell['ORG'] = $orgArr[$key];
				$result_cell['ROLE'] = $roleArr[$key];
			}
			
			$result_row[] = $result_cell;
		}
		
		
		
		$otherOrgArr['total'] = count($result_row);
		$otherOrgArr['rows'] = $result_row;
		
		$result['OtherOrg'] = $otherOrgArr;
		
	}else{
		$result['Multi'] = false;
	}
}

echo json_encode($result);



?>