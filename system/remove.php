<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array();
$CODE = $_REQUEST['CODE'];

//check value
$chk = checkValueAtDB($MODULENO,$_REQUEST,'remove');
if ($chk['ok']){
	//log
	$logArray = array();
	$logArray_new = array();
	$logArray_old = array();
	//新数据为空
	//旧数据
	$query = "SELECT * FROM SYS_MODULES WHERE CODE = '$CODE'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		$logArray_old['CODE']=urlencode($row['CODE']);
		$logArray_old['NAME']=urlencode($row['NAME']);
		$logArray_old['MENU']=urlencode($row['MENU']);
		$logArray_old['URL']=urlencode($row['URL']);
		$logArray_old['NOTE']=urlencode($row['NOTE']);
		$logArray_old['ORDERNO']=urlencode($row['ORDERNO']);
		$logArray_old['OBJ']=urlencode($row['OBJ']);
		
		$MODULEINFO = "[".$row['CODE']."]".$row['NAME'];
		$query_rights = "SELECT * FROM SYS_MODULES_RIGHTS WHERE MODULES = '$MODULEINFO'";
		$cursor_rights = exequery($connection,$query_rights);
		$logArray_old_item = array();
		$logArray_old_item_item = array();
		while ($row_rights = mysqli_fetch_array($cursor_rights)){
			$MODULERIGHTSINFO = "[".$row_rights['CODE']."]".$row_rights['NAME'];
			$logArray_old_item_item['RIGHTS'] = urlencode($MODULERIGHTSINFO);
			
			$query_auth = "SELECT * FROM SYS_AUTH WHERE RIGHTS = '$MODULERIGHTSINFO'";
			$cursor_auth = exequery($connection,$query_auth);
			$logArray_old_item_item2 = array();
			while ($row_auth = mysqli_fetch_array($cursor_auth)){
				$logArray_old_item_item2[] = urlencode($row_auth['ROLE']);
			}
			
			$logArray_old_item_item['AUTH'] = $logArray_old_item_item2;
			$logArray_old_item[] = $logArray_old_item_item;
		}

		$logArray_old['MODULES_RIGHTS_AUTH']=$logArray_old_item;
		
	}
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始删除
	$query = "DELETE A FROM SYS_AUTH A, SYS_MODULES_RIGHTS B, SYS_MODULES C "
	."WHERE A.RIGHTS = CONCAT('[',B.`CODE`,']',B.`NAME`) "
	."AND B.MODULES = CONCAT('[',C.`CODE`,']',C.`NAME`) "
	."AND C.`CODE` = '$CODE'; "
	."DELETE B FROM SYS_MODULES_RIGHTS B, SYS_MODULES C "
	."WHERE B.MODULES = CONCAT('[',C.`CODE`,']',C.`NAME`) "
	."AND C.`CODE` = '$CODE'; "
	."DELETE FROM SYS_MODULES WHERE CODE = '$CODE';";
	
	//开始更新//多条语句一起执行	
	if(exeMutiQuery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '删除成功！';
		writeDBLog($login_user, $MODULEOBJ, '删除', urldecode(json_encode($logArray)), $MODULETITLE);
	}
}else{
	$result['success'] = false;
	$result['message'] = $chk['msg'];
}
echo json_encode($result);
?>