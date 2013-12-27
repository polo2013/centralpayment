<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$ROLE = urldecode($_REQUEST['ROLE']) ? urldecode($_REQUEST['ROLE']) : $login_user_role;

$result = array();
$result_root = array();

//root
$query_root = "SELECT DISTINCT A.`CODE` CODE, B.MODULES MODULES FROM SYS_MODULES A, SYS_MODULES_RIGHTS B "
."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.MODULES "
."ORDER BY A.`CODE`";
$cursor_root = exequery($connection,$query_root);
while($row_root = mysqli_fetch_array($cursor_root)){
	$result_root['id'] = $row_root['CODE'];
	$result_root['text'] = $row_root['MODULES'];
	
	//leaf
	
	$result_leaf = array();
	$query_leaf = "SELECT `CODE` CODE, CONCAT('[',`CODE`,']',`NAME`) RIGHTS FROM SYS_MODULES_RIGHTS WHERE MODULES = '".$row_root['MODULES']."' ORDER BY `CODE`";
	$cursor_leaf = exequery($connection,$query_leaf);
	while($row_leaf = mysqli_fetch_array($cursor_leaf)){
		$result_leaf_item = array();
		
		$result_leaf_item['id'] = $row_leaf['CODE'];
		$result_leaf_item['text'] = $row_leaf['RIGHTS'];
		
		$query_auth = "SELECT * FROM SYS_AUTH WHERE ROLE = '$ROLE' AND RIGHTS = '".$row_leaf['RIGHTS']."'";
		$cursor_auth = exequery($connection,$query_auth);
		if($row_auth = mysqli_fetch_array($cursor_auth)){
			$result_leaf_item['checked'] = true;
			$result_leaf_item['iconCls'] = 'icon-ok';
		}else{
			$result_leaf_item['checked'] = false;
			$result_leaf_item['iconCls'] = '';
		}
		
		$result_leaf[] = $result_leaf_item;
		
	}
	if(count($result_leaf) != 0){
		$result_root['children'] = $result_leaf;
		$result_root['state'] = 'closed';
		$result_root['iconCls'] = 'icon-add';
	}else{
		$result_root['children'] = '';
		$result_root['state'] = 'open';
		$result_root['iconCls'] = '';
	}
	$result[] = $result_root;
	
}

echo json_encode($result);

?>