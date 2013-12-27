<?php
include_once("../public/php/session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];
$MODULENO = $_GET['MODULENO'] ? $_GET['MODULENO'] : "";

$result = array();
$result_row = array();
$result_cell = array();

if(!isset($_REQUEST['page']) || !intval($_REQUEST['page']) || !isset($_REQUEST['rows']) || !intval($_REQUEST['rows'])){
	$pageNumber = 1;  //对出错进行默认处理:在url参数page不存在时，page不为10进制数时，默认为1
	$pageSize = 10;
}else{
	$pageNumber = $_REQUEST['page'];
	$pageSize = $_REQUEST['rows'];
}
$start = ($pageNumber - 1) * $pageSize; //从数据集第$start条开始取，注意数据集是从0开始的

//数据权限
//在有查看权的情况下，可以查看本机构以及下属机构。
$orgcode = splitCode($login_user_org);
$query = "SELECT COUNT(1) FROM SYS_ORG WHERE SUBSTR(`CODE`,1, LENGTH('$orgcode'))='$orgcode' ORDER BY ORDERNO, CODE ";
//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', '*', $query);
$query_page .= " LIMIT $start,$pageSize";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['CODE'] = $row_page['CODE'];
	$result_cell['NAME'] = $row_page['NAME'];
	$result_cell['PARENT'] = $row_page['PARENT'];
	$result_cell['TEL']  = $row_page['TEL'];
	$result_cell['FAX'] = $row_page['FAX'];
	$result_cell['ADDR'] = $row_page['ADDR'];
	$result_cell['ZIPCODE'] = $row_page['ZIPCODE'];
	$result_cell['NOTE'] = $row_page['NOTE'];
	$result_cell['ORDERNO'] = $row_page['ORDERNO'];
	
	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

echo json_encode($result);

?>