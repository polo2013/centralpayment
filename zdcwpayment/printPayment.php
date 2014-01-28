<?php
include_once("../public/php/head.php");
include_once("../public/php/utility.php");

$result = array();
$result_row = array();
$result_cell = array();
$sumAllTotal = 0.00;

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";

$query = "SELECT * FROM zdcw_payment_master WHERE `NUM`='$NUM'";
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$result['NUM'] = $ROW['NUM'];
	$result['ORG'] = $ROW['ORG'];
	$result['BILLNUM'] = $ROW['BILLNUM'];
	$result['STAT'] = $ROW['STAT'];
	$result['INPUTTER'] = $ROW['INPUTTER'];
	$result['INPUTTIME'] = ($ROW['INPUTTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['INPUTTIME']);
	$result['CHECKER'] = $ROW['CHECKER'];
	$result['CHECKTIME'] = ($ROW['CHECKTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['CHECKTIME']);
	$result['APPROVER'] = $ROW['APPROVER'];
	$result['APPROVETIME'] = ($ROW['APPROVETIME'] == '0000-00-00 00:00:00' ? '' : $ROW['APPROVETIME']);
	$result['NOTE'] = $ROW['NOTE'];
}


$query_page = "SELECT * FROM zdcw_payment_detail WHERE `NUM`='$NUM' ORDER BY (ITEMNO+0)";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['NUM'] = $row_page['NUM'];
	$result_cell['ITEMNO'] = $row_page['ITEMNO'];
	$result_cell['ORG'] = $row_page['ORG'];
	$result_cell['APPLICANT'] = $row_page['APPLICANT'];
	$result_cell['PAYMENT'] = $row_page['PAYMENT'];
	$result_cell['TOTALAMT'] = $row_page['TOTALAMT'];
	$result_cell['PAYEE'] = $row_page['PAYEE'];
	$result_cell['BANK'] = $row_page['BANK'];
	$result_cell['ACCOUNT'] = $row_page['ACCOUNT'];
	$result_cell['NOTE'] = $row_page['NOTE'];
	$result_cell['PAYSTAT'] = $row_page['PAYSTAT'];
	$result_cell['PAYER'] = $row_page['PAYER'];
	$result_cell['PAYTIME'] = ($row_page['PAYTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['PAYTIME']);

	$sumAllTotal = $sumAllTotal + $row_page['TOTALAMT'];
	$result_row[] = $result_cell;
}

$result['ROWS'] = $result_row;

$result_row = array();
$result_cell = array();
$result_cell['ORG'] = '总计：';
$result_cell['TOTALAMT'] = number_format($sumAllTotal, 2, '.', '');
$result_row[] = $result_cell;
$result['FOOTER'] = $result_row;

echo "
<style>
#dg_print_zdcwpayment_mst,
#dg_print_zdcwpayment_foot
{width:700px;}

#dg_print_zdcwpayment_dtl
{
	border: solid #000 1px;
	border-collapse:collapse;
	width:700px;
}

#dg_print_zdcwpayment_mst td,
#dg_print_zdcwpayment_foot td
{text-align:center;}

#dg_print_zdcwpayment_dtl td
{
	border: solid #000 1px;
	text-align:center;
}

.printleft{text-align:left !important;}
.printright{text-align:right !important;}

#printtitle{padding-bottom:30px;}
#dg_print_zdcwpayment_foot{margin-top:30px;}
</style>
</head>
<body>
<table id='dg_print_zdcwpayment_mst'>
<tr><td colspan='3' id='printtitle'><h3>付款汇总表</h3></td></tr>
<tr><td class='printleft'>组织机构：".$result['ORG']."</td><td>录入日期：".$result['INPUTTIME']."</td><td class='printright'>编号：".$result['BILLNUM']."</td></tr>
</table>
<table id='dg_print_zdcwpayment_dtl'>
<tr><td width='40px'>序号</td><td width='150px'>部门/项目</td><td width='150px'>费用申请人</td><td width='150px'>付款事由</td><td width='80px'>金额</td><td width='200px'>收款人及账号</td><td width='80px'>备注</td></tr>
";
foreach ($result['ROWS'] as  $key => $val){
	echo "<tr><td>".$val['ITEMNO']
	."</td><td>".$val['ORG']
	."</td><td>".$val['APPLICANT']
	."</td><td>".$val['PAYMENT']
	."</td><td class='printright'>".$val['TOTALAMT']
	."</td><td>".$val['PAYEE']."<br>".$val['BANK']."<br>".$val['ACCOUNT']
	."</td><td>".$val['NOTE']
	."</td></tr>";
}
echo "<tr><td colspan='2'>总计：</td><td></td><td></td><td class='printright'>".$result['FOOTER'][0]['TOTALAMT']."</td><td></td><td></td></tr>";
echo "</table>";
echo "<table id='dg_print_zdcwpayment_foot'>
<tr><td class='printleft'>统计：</td><td class='printleft'>查核：</td><td class='printleft'>批准：</td></tr>
</table>";
echo "</body></html>";

//var_dump($result);

?>