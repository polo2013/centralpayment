<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = $_REQUEST['MODULENO'] ? $_REQUEST['MODULENO'] : "";
$MODULEOBJ = $_REQUEST['MODULEOBJ'] ? $_REQUEST['MODULEOBJ'] : "";
$MODULETITLE = $_REQUEST['MODULETITLE'] ? $_REQUEST['MODULETITLE'] : "";

$result = array('success' => false, 'message' => '保存失败！');
$ROLE = $_REQUEST['ROLE'];
$AUTHDTL = $_REQUEST['AUTHDTL'];

//check value
$chk = checkValueAtDB($MODULENO,$_REQUEST,'save');
if ($chk['ok']){

	//log
	$logArray = array();
	$logArray_new_item = array();
	$logArray_new = array();
	$logArray_old_item = array();
	$logArray_old = array();

	//旧数据
	$query = "select * from SYS_AUTH where ROLE = '$ROLE'";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$logArray_old_item[] = urlencode($row['RIGHTS']);
	}
	$logArray_old['ROLE'] = urlencode($ROLE);
	$logArray_old['RIGHTS'] = $logArray_old_item;
	
	//新数据
	$query = "delete from SYS_AUTH where ROLE = '$ROLE'; ";
	//解码成数组（最里面一层是target数组，中间是每个node，包括根note，最外面是个壳）
	$AUTHDTLOBJ = json_decode($AUTHDTL,TRUE);
	if($AUTHDTLOBJ != NULL){
		foreach($AUTHDTLOBJ as $key => $value){
			if(strlen($value['id']) != 3){  //非root节点，即为叶子节点
				$authdtldtl = $value['text'];
				$logArray_new_item[] = urlencode($authdtldtl);
				$query .= "insert into SYS_AUTH(ROLE,RIGHTS,LASTUPD) VALUES ('$ROLE', '$authdtldtl', '$login_user'); ";
			}
		}
	}else{//没有任何权限被选中
	}
	$logArray_new['ROLE'] = urlencode($ROLE);
	$logArray_new['RIGHTS'] = $logArray_new_item;
	
	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始更新//多条语句一起执行
	/*
	writeLog($query, 'info');
	if(mysqli_multi_query($connection,$query)){
		do {
			mysqli_next_result($connection);
			$rt = mysqli_store_result($connection);
			//如果仅仅是insert等没有结果集的操作，mysqli_store_result返回false，则mysqli_free_result无法接受。
			//mysqli_free_result($rt);
			
		} while (mysqli_more_results($connection));
		
		
		$result['success'] = true;
		$result['message'] = '保存成功！';
		writeDBLog($login_user, $MODULEOBJ, '修改', urldecode(json_encode($logArray)), $MODULETITLE);
	}
	*/
	if (exeMutiQuery($connection,$query)){
		$result['success'] = true;
		$result['message'] = '保存成功！';
		writeDBLog($login_user, $MODULEOBJ, '修改', urldecode(json_encode($logArray)), $MODULETITLE);
	}
}else{
	$result['success'] = false;
	$result['message'] = $chk['msg'];
}
echo json_encode($result);
?>