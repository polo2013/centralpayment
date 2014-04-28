<?php
include_once("../public/php/head.php");
include_once("../public/php/utility.php");

$result = array();
$result_row = array();
$result_cell = array();
$sumAllTotal = 0.00;

$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";

$IMP_FLAG = "";

$query = "SELECT * FROM zdcw_payment_master WHERE `NUM`='$NUM'";
$cursor = exequery($connection,$query);
if($ROW = mysqli_fetch_array($cursor)){
	$IMP_FLAG = $ROW['IMP_FLAG'];
	
	$result['NUM'] = $ROW['NUM'];
	$result['ORG'] = $IMP_FLAG == "" ? splitName($ROW['ORG']) : $ROW['ORG'];
	$result['BILLNUM'] = $ROW['BILLNUM'];
	$result['STAT'] = $ROW['STAT'];
	$result['INPUTTER'] = $IMP_FLAG == "" ? splitName($ROW['INPUTTER']) : $ROW['INPUTTER'];
	$result['INPUTTIME'] = ($ROW['INPUTTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['INPUTTIME']);
	$result['CHECKER'] = $IMP_FLAG == "" ? splitName($ROW['CHECKER']) : $ROW['CHECKER'];
	$result['CHECKTIME'] = ($ROW['CHECKTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['CHECKTIME']);
	$result['APPROVER'] = $IMP_FLAG == "" ? splitName($ROW['APPROVER']) : $ROW['APPROVER'];
	$result['APPROVETIME'] = ($ROW['APPROVETIME'] == '0000-00-00 00:00:00' ? '' : $ROW['APPROVETIME']);
	$result['NOTE'] = $ROW['NOTE'];
	$result['PAYCHECKER'] = $IMP_FLAG == "" ? splitName($ROW['PAYCHECKER']) : $ROW['PAYCHECKER'];
	$result['PAYCHECKTIME'] = ($ROW['PAYCHECKTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['PAYCHECKTIME']);
	$result['PAYIMPORT'] = $ROW['PAYIMPORT'];
	$result['PAYIMPORTTIME'] = ($ROW['PAYIMPORTTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['PAYIMPORTTIME']);
	$result['PAYCONFIRM'] = $ROW['PAYCONFIRM'];
	$result['PAYCONFIRMTIME'] = ($ROW['PAYCONFIRMTIME'] == '0000-00-00 00:00:00' ? '' : $ROW['PAYCONFIRMTIME']);
	$result['IMP_FLAG'] = $ROW['IMP_FLAG'];
}


$query_page = "SELECT * FROM zdcw_payment_detail WHERE `NUM`='$NUM' ORDER BY (ITEMNO+0)";
$cursor_page = exequery($connection,$query_page);
while($row_page = mysqli_fetch_array($cursor_page)){
	$result_cell['NUM'] = $row_page['NUM'];
	$result_cell['ITEMNO'] = $row_page['ITEMNO'];
	$result_cell['ORG'] = $IMP_FLAG == "" ? splitName($row_page['ORG']) : $row_page['ORG'];
	$result_cell['APPLICANT'] = $IMP_FLAG == "" ? splitName($row_page['APPLICANT']) : $row_page['APPLICANT'];
	$result_cell['PAYMENT'] = $row_page['PAYMENT'];
	$result_cell['CURRENCY'] = $row_page['CURRENCY'];
	$result_cell['TOTALAMT'] = $row_page['TOTALAMT'];
	$result_cell['PAYEE'] = $IMP_FLAG == "" ? splitName($row_page['PAYEE']) : $row_page['PAYEE'];
	$result_cell['BANK'] = $row_page['BANK'];
	$result_cell['ACCOUNT'] = $row_page['ACCOUNT'];
	$result_cell['NOTE'] = $row_page['NOTE'];
	$result_cell['PAYSTAT'] = $row_page['PAYSTAT'];
	$result_cell['PAYER'] = $IMP_FLAG == "" ? splitName($row_page['PAYER']) : $row_page['PAYER'];
	$result_cell['PAYTIME'] = ($row_page['PAYTIME'] == '0000-00-00 00:00:00' ? '' : $row_page['PAYTIME']);

	$sumAllTotal = $sumAllTotal + $row_page['TOTALAMT'];
	$result_row[] = $result_cell;
}

$result['ROWS'] = $result_row;

$result_row = array();
$result_cell = array();
//计算各币种金额
$query_distinct = "SELECT CURRENCY, SUM(TOTALAMT) TOTALAMT FROM zdcw_payment_detail WHERE `NUM`='$NUM' GROUP BY CURRENCY ORDER BY (ITEMNO+0)";
$cursor_distinct = exequery($connection,$query_distinct);
$j = 0;
while($row_distinct = mysqli_fetch_array($cursor_distinct)){
	if($j == 0){
		$result_cell['ORG'] = '总计：';
	}ELSE{
		$result_cell['ORG'] = '';
	}
	$result_cell['CURRENCY'] = $row_distinct['CURRENCY'];
	$result_cell['TOTALAMT'] = number_format($row_distinct['TOTALAMT'], 2, '.', '');
	
	$result_row[] = $result_cell;
	$j ++;
}

$result['FOOTER'] = $result_row;

echo "
<style>
	table
	{
		/*border: solid #000 1px;*/
		border-collapse: collapse;
		width: 100%;
	}
	
	table td
	{
		border: solid #000 1px;
		text-align: center;
	}
	
	.printleft{text-align:left !important;}
	.printright{text-align:right !important;}
		
</style>

</head>
<body>
	<table id='dg_print_zdcwpayment_mst'>
	<thead>
		<tr><td colspan='7' style='border:0px;'><h3>付款汇总表</h3></td></tr>
		<tr><td colspan='7' style='padding: 10px 0; border:0px;'><span>组织机构：".$result['ORG']."</span><span style='margin:0 30px'>";
		if ($result['IMP_FLAG'] == "") {
			echo "录入日期：".$result['INPUTTIME'];
		}else{
			echo "导入日期：".$result['PAYIMPORTTIME'];
		}
		echo "</span><span>编号：".$result['BILLNUM']."</span></td></tr>
		<tr><td width='40px'>序号</td><td width='150px'>部门/项目</td><td width='150px'>费用申请人</td><td width='150px'>付款事由</td><td width='80px'>币别</td><td width='80px'>金额</td><td width='200px'>收款人及账号</td><td width='80px'>备注</td></tr>
	</thead>
	<tbody>";
foreach ($result['ROWS'] as  $key => $val){
	echo "<tr><td>".$val['ITEMNO']
	."</td><td>".$val['ORG']
	."</td><td>".$val['APPLICANT']
	."</td><td>".$val['PAYMENT']
	."</td><td>".$val['CURRENCY']
	."</td><td class='printright'>".$val['TOTALAMT']
	."</td><td>".$val['PAYEE']."<br>".$val['BANK']."<br>".$val['ACCOUNT']
	."</td><td>".$val['NOTE']
	."</td></tr>";
}
foreach ($result['FOOTER'] as  $key2 => $val2){
	echo "	<tr><td colspan='2'>".$result['FOOTER'][$key2]['ORG']."</td><td></td><td></td><td>".$result['FOOTER'][$key2]['CURRENCY']."</td><td class='printright'>".$result['FOOTER'][$key2]['TOTALAMT']."</td><td></td><td></td></tr>";
}
echo "	</tbody>
	<tfoot>";
if ($result['IMP_FLAG'] == "") {
	echo "<tr><td colspan='8' style='padding:20px 0 10px 0; border:0px;'><span style='margin-right:50px'>统计：".$result['INPUTTER']."</span><span style='margin-right:50px'>查核：".$result['CHECKER']."</span><span style='margin-right:50px'>批准：".$result['APPROVER']."</span><span>付款审核：".$result['PAYCHECKER']."</span></td></tr>";
}else{
	echo "<tr><td colspan='8' style='padding:20px 0 10px 0; border:0px;'><span style='margin-right:50px'>导入：".$result['PAYIMPORT']."</span><span style='margin-right:50px'>付款确认：".$result['PAYCONFIRM']."</span></td></tr>";
}
		echo "<tr><td colspan='8' class='printright' style='border:0px;'><span tdata='pageNO'>第###页</span><span tdata='pageCount'>共###页</span></td></tr>
	</tfoot>
	</table>
</body>
</html>";

//var_dump($result);

?>