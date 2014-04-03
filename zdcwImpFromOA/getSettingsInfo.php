<?php
include_once("../public/php/session.php");

$result = array();
$result_row = array();
$result_cell = array();

//数据权限
$query = "SELECT COUNT(1) FROM zdcw_imp_from_oa ORDER BY `ID`";
//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', '*', $query);
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['ORG'] = $row_page['ORG'];
	$result_cell['ORGDESC'] = $row_page['ORGDESC'];
	$result_cell['FLOWID'] = $row_page['FLOWID'];
	$result_cell['FLOWNAME'] = $row_page['FLOWNAME'];
	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

echo json_encode($result);

?>