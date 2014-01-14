<?php
include_once("../public/php/head.php");
include_once("../public/php/session.php");
$NUM = $_REQUEST['NUM'] ? $_REQUEST['NUM'] : "";
?>

<style>
#dg_print_zdcwpayment{
	border: 1px solid;
}
</style>

<script type="text/javascript">
$(window).load(function (){ 
	

	$.getJSON("../zdcwpayment/getInfo.php", {NUM: '<?=$NUM?>'}, function(data){
		//alert(JSON.stringify(data));
		var trHTML = '';
		trHTML = "<tr><td>序号</td><td>部门\/项目</td><td>费用申请人</td><td>付款事由</td><td>金额</td><td>收款人及账号</td><td>备注</td></tr>";
		$('#dg_print_zdcwpayment').append(trHTML);
		trHTML = "<tr><td>"+data.ROWS[0].ITEMNO+"</td><td>"+data.ROWS[0].ORG+"</td><td>"
		+data.ROWS[0].APPLICANT+"</td><td>"+data.ROWS[0].PAYMENT+"</td><td>"+data.ROWS[0].TOTALAMT
		+"</td><td>"+data.ROWS[0].PAYEE+"</td><td>"+data.ROWS[0].NOTE+"</td></tr>"
		$('#dg_print_zdcwpayment').append(trHTML);
	});
});
</script>

</head>
<body>
	<table id="dg_print_zdcwpayment"></table>
</body>
</html>