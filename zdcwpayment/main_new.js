$(document).ready(function(){
	if(paymentViewMode == "new"){
		//alert(JSON.stringify(allAuth));
		//单号
		$('#num_zdcwpayment').val(new Date().Format("yyyyMMddhhmmssS"));
		//组织
		$.getJSON("../public/php/getOrgForSelect.php", function(data){
			$('#org_zdcwpayment').combobox('loadData', data.allorg);
			$('#org_zdcwpayment').combobox('select', data.myorg);
		});
		//编号
		var partCode = new Date().Format("yyyyMMdd");
		var newCode = ajaxGetNewCode('009', partCode);
		$('#billnum_zdcwpayment').val(newCode);
		//状态
		$('#stat_zdcwpayment').val("录入");
		//录入人
		$.getJSON("../public/php/getUser.php", {PARA: "ME", PARA2:""}, function(data){
			$('#inputter_zdcwpayment').val(data.me);
		});
		//录入时间
		$('#inputtime_zdcwpayment').val(new Date().Format("yyyy-MM-dd hh:mm:ss"));
		//操作
		$.getJSON('../'+modulepath+'/getOperation.php', {STAT: "录入"}, function(data){
			//alert(JSON.stringify(data));
			if(data.hasOperation){
				if(data.onlyOne){$('#operation_zdcwpayment').combobox({hasDownArrow:false});} //这句话必须要在最前面，否则submit的时候就没有值
				$('#operation_zdcwpayment').combobox('loadData', data.operation);
				$('#operation_zdcwpayment').combobox('select', data.firstone);
			}else{
				$('#operation_zdcwpayment').combobox({hasDownArrow:false});
				$('#operation_zdcwpayment').combobox('select', data.info);
			}
		});
		
		//toolbar
		$('#btn1_zdcwpayment').linkbutton({
			iconCls:'icon-add',
			plain:true,
			text:'新增一行',
			disabled: true			
		});
		$('#btn2_zdcwpayment').linkbutton({
			iconCls:'icon-remove',
			plain:true,
			text:'删除一行',
			disabled: true
		});
		$('#btn3_zdcwpayment').linkbutton({
			iconCls:'icon-ok',
			plain:true,
			text:'录入完成',
			disabled: true
		});
		
		$('#dg_zdcwpayment').datagrid({
			toolbar: '#tb_dg_zdcwpayment',
		})
		.datagrid('hideColumn','PAYSTAT')
		.datagrid('hideColumn','PAYER')
		.datagrid('hideColumn','PAYTIME');
		
		if(arrSearch('录入权',allAuth)){
			$('#btn_save_zdcwpayment').linkbutton('enable');
			$('#btn_save_zdcwpayment').unbind();
			$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
			$('#btn1_zdcwpayment').linkbutton('enable');
			$("#btn1_zdcwpayment").unbind();
			$('#btn1_zdcwpayment').bind('click', appendPayment);
			$('#btn2_zdcwpayment').linkbutton('enable');
			$("#btn2_zdcwpayment").unbind();
			$('#btn2_zdcwpayment').bind('click', removePayment);
			$('#btn3_zdcwpayment').linkbutton('enable');
			$("#btn3_zdcwpayment").unbind();
			$('#btn3_zdcwpayment').bind('click', endEditingPayment);
			
			$('#dg_zdcwpayment').datagrid({
				onClickRow:clickPaymentRow,
			});
			
			var footer = $('#dg_zdcwpayment').datagrid('getFooterRows');
			if(footer == undefined){
				$('#dg_zdcwpayment').datagrid('reloadFooter',[{"ORG":"总计：","TOTALAMT":'0.00'}]);
			}
		}
	}
});