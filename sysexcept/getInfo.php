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
$query = "
SELECT COUNT(1)
FROM zdcw_another_people a
LEFT JOIN sys_org b ON a.org = b.`CODE`
LEFT JOIN sys_org c ON a.another = c.`CODE` and a.otype='org'
LEFT JOIN sys_user d ON a.another = d.`CODE` and a.otype='people'
 ORDER BY a.ID";
//取总数
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['total'] = $ROW[0];
}else{
	$result['total'] = 0;
}
//分页
$query_page = str_replace('COUNT(1)', 'a.*, b.`NAME` ORG_NAME, c.`NAME` ANOTHER_ORG, d.`NAME` ANOTHER_PEOPLE', $query);
$query_page .= " LIMIT $start,$pageSize";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['ID'] = $row_page['ID'];
	$result_cell['ORG'] = $row_page['ORG'];
	$result_cell['ORG_NAME'] = $row_page['ORG_NAME'];
	$result_cell['OTYPE'] = $row_page['OTYPE'] == "org" ? "部门" : "用户";
	$result_cell['ANOTHER'] = $row_page['ANOTHER'];
	$result_cell['ANOTHER_NAME'] = $row_page['OTYPE'] == "org" ? $row_page['ANOTHER_ORG'] : $row_page['ANOTHER_PEOPLE'];
	$result_row[] = $result_cell;
}

$result['rows'] = $result_row;

echo json_encode($result);

?>