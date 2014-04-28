<?php
include_once("../public/php/session.php");
$login_user = $_SESSION['LOGIN_USER'];
$MODULENO = urldecode($_REQUEST['MODULENO']) ? urldecode($_REQUEST['MODULENO']) : "";
$MODULEOBJ = urldecode($_REQUEST['MODULEOBJ']) ? urldecode($_REQUEST['MODULEOBJ']) : "";
$MODULETITLE = urldecode($_REQUEST['MODULETITLE']) ? urldecode($_REQUEST['MODULETITLE']) : "";

$result = array('success' => false, 'message' => '保存失败！');
$MODULES = urldecode($_REQUEST['MODULES']);
$RIGHTS = urldecode($_REQUEST['RIGHTS']);

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
	$query = "select * from SYS_MODULES_RIGHTS where MODULES = '$MODULES'";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$logArray_old_item['CODE'] = urlencode($row['CODE']);
		$logArray_old_item['NAME'] = urlencode($row['NAME']);
		$logArray_old_item['MODULES'] = urlencode($row['MODULES']);
		
		$logArray_old[] = $logArray_old_item;
	}

	//新数据
	$query = "delete from SYS_MODULES_RIGHTS where MODULES = '$MODULES'; ";
	//解码成数组（最里面一层是target数组，中间是每个node，包括根note，最外面是个壳）
	$RIGHTSOBJ = json_decode($RIGHTS,TRUE);
	if($RIGHTSOBJ != NULL){
		foreach($RIGHTSOBJ as $key => $value){
				$CODE = $value['CODE'];
				$NAME = $value['NAME'];
				$logArray_new_item['CODE'] = urlencode($CODE);
				$logArray_new_item['NAME'] = urlencode($NAME);
				$logArray_new_item['NAME'] = urlencode($MODULES);
				
				$logArray_new[] = $logArray_new_item;
				
				$query .= "insert into SYS_MODULES_RIGHTS(CODE,NAME,MODULES,LASTUPD) VALUES ('$CODE', '$NAME', '$MODULES', '$login_user'); ";
			
		}
	}else{//全部删除
	}

	$logArray['OLDVALUE']=$logArray_old;
	$logArray['NEWVALUE']=$logArray_new;
	
	//开始更新//多条语句一起执行
	if(exeMutiQuery($connection,$query)){		
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