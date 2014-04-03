<?php
include_once("../public/php/session.php");

$result = array('success' => false, 'message' => '保存失败！');
$Settings = urldecode($_REQUEST['Settings']);

$query = "delete from zdcw_imp_from_oa; ";
//解码成数组（最里面一层是target数组，中间是每个node，包括根note，最外面是个壳）
$SettingsOBJ = json_decode($Settings,TRUE);
if($SettingsOBJ != NULL){
	foreach($SettingsOBJ as $key => $value){
		$ORG = $value['ORG'];
		$ORGDESC = $value['ORGDESC'];
		$FLOWID = $value['FLOWID'];
		$FLOWNAME = $value['FLOWNAME'];

		$query .= "insert into zdcw_imp_from_oa(ORG,ORGDESC,FLOWID,FLOWNAME) VALUES ('$ORG', '$ORGDESC', '$FLOWID', '$FLOWNAME'); ";
		
	}
}else{//全部删除
}

	
//开始更新//多条语句一起执行
if(exeMutiQuery($connection,$query)){		
	$result['success'] = true;
	$result['message'] = '保存成功！';
	
}

echo json_encode($result);
?>