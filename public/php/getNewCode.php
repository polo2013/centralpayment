<?php
include_once("session.php");
$result = array();
$module = $_POST['module'];
//对于有些模块来说为默认值，有些是根据此参数来计算newCode
$para = $_POST['para'];

$login_user_org = $_SESSION['LOGIN_USER_ORG'];

if($module == "003" or $module == "004" or $module == "007" or $module == "008" ){
	$orgcode = splitCode($para);
}


//编号
switch ($module){
	case "001":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from SYS_MODULES ";
		break;
	case "moduleRights":
		$query = "select LPAD(MAX('$para')+1,LENGTH('$para'),'0') NewNumber ";
		break;
	case "002":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from SYS_STAT ";
		break;
	case "003":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from SYS_ORG "
		."where SUBSTR(`CODE`,1,LENGTH('$orgcode')) = '$orgcode' "
		."and LENGTH(`CODE`) = LENGTH('$orgcode')+3 ";
		break;
	case "004":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from SYS_ROLE "
		."where SUBSTR(`CODE`,1,LENGTH('$orgcode')) = '$orgcode' "
		."and LENGTH(`CODE`) = LENGTH('$orgcode')+3 ";
		break;
	case "007":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from BIZ_PROJECT "
		."where SUBSTR(`CODE`,1,LENGTH('$orgcode')) = '$orgcode' "
		."and LENGTH(`CODE`) = LENGTH('$orgcode')+3 ";
		break;
	case "008":
		$query = "select LPAD(MAX(`CODE`)+1,LENGTH(`CODE`),'0') NewNumber from BIZ_PAYEE "
		."where SUBSTR(`CODE`,1,LENGTH('$orgcode')) = '$orgcode' "
		."and LENGTH(`CODE`) = LENGTH('$orgcode')+3 ";
		break;
	case "009":
		$query = "select LPAD(MAX(`BILLNUM`)+1,LENGTH(`BILLNUM`),'0') NewNumber from zdcw_payment_master where ORG = '$login_user_org' and SUBSTR(`BILLNUM`,1,8) = '$para'";
		break;
	default:
		echo "No number";
};

$cursor = exequery($connection,$query);
if($row = mysqli_fetch_array($cursor)){
	if ($row['NewNumber']){
		$result['newNumber'] = $row['NewNumber'];
	}else{
		if($module == "003" or $module == "004" or $module == "007" or $module == "008" ){
			$result['newNumber'] = $orgcode.'001';
		}else if($module == "009"){
			$result['newNumber'] = $para.'0001';
		}else{
			$result['newNumber'] = $para;
		}
	}
}else{
	$result['newNumber'] = $para;
}

echo json_encode($result);
?>