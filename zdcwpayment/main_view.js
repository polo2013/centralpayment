$(document).ready(function(){
	if(paymentViewMode == "view"){
		//alert(viewNum);
		$.getJSON('../'+modulepath+'/getInfo.php', {NUM: viewNum}, function(data){
			//alert(JSON.stringify(data));
			//单号
			$('#num_zdcwpayment').val(data.NUM);
			//组织
			$('#org_zdcwpayment').combobox({hasDownArrow: false, readonly: true});
			$('#org_zdcwpayment').combobox('select', data.ORG);
			//编号
			$('#billnum_zdcwpayment').validatebox('disableValidation');
			$('#billnum_zdcwpayment').attr("readonly","readonly");
			$('#billnum_zdcwpayment').val(data.BILLNUM);
			//状态
			$('#stat_zdcwpayment').val(data.STAT);
			//录入人
			$('#inputter_zdcwpayment').val(data.INPUTTER);
			//录入时间
			$('#inputtime_zdcwpayment').val(data.INPUTTIME);
			//审核人
			$('#checker_zdcwpayment').val(data.CHECKER);
			//审核时间
			$('#checktime_zdcwpayment').val(data.CHECKTIME);
			//批准人
			$('#approver_zdcwpayment').val(data.APPROVER);
			//批准时间
			$('#approvetime_zdcwpayment').val(data.APPROVETIME);
			//备注
			$('#note_zdcwpayment').val(data.NOTE);
			
			//操作
			$.getJSON('../'+modulepath+'/getOperation.php', {STAT: data.STAT}, function(data_oper){
				//alert(JSON.stringify(data_oper));
				if(data_oper.hasOperation){
					if(data_oper.onlyOne){$('#operation_zdcwpayment').combobox({hasDownArrow:false});}  //这句话必须要在最前面，否则submit的时候就没有值
					$('#operation_zdcwpayment').combobox('loadData', data_oper.operation);
					$('#operation_zdcwpayment').combobox('select', data_oper.firstone);
				}else{
					$('#operation_zdcwpayment').combobox({hasDownArrow:false});
					$('#operation_zdcwpayment').combobox('select', data_oper.info);
				}
			});
			
			$('#dg_zdcwpayment').datagrid({
				data: data,
			});
			
			/*****根据状态显示表格****************************************************************/
			if(data.STAT == '审核不通过' || data.STAT == '批准不通过' || data.STAT == '付款不通过'){
				//toolbar
				$('#btn1_zdcwpayment').linkbutton({
					iconCls:'icon-ok',
					plain:true,
					text:'修改完成',
					disabled: true
				});
				$('#dg_zdcwpayment').datagrid({
					toolbar: '#tb_dg_zdcwpayment',
				});
				//检查权限
				if(arrSearch('修改权',allAuth)){
					$('#btn_save_zdcwpayment').linkbutton('enable');
					$('#btn_save_zdcwpayment').unbind();
					$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
					$('#btn1_zdcwpayment').linkbutton('enable');
					$("#btn1_zdcwpayment").unbind();
					$('#btn1_zdcwpayment').bind('click', endEditingPayment);
					
					$('#dg_zdcwpayment').datagrid({
						title: moduletitle+'<font style="color:red;">（点击未付款的数据可进行修改）</font>',
						onClickRow:clickPaymentRow,
					});
				}
			}else if(data.STAT == '待审核' || data.STAT == '重新审核'){
				if(arrSearch('审核权',allAuth)){
					$('#btn_save_zdcwpayment').linkbutton('enable');
					$('#btn_save_zdcwpayment').unbind();
					$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
				}
			}else if(data.STAT == '已审核待批准' || data.STAT == '重新批准'){
				if(arrSearch('批准权',allAuth) || arrSearch('反审核权',allAuth)){
					$('#btn_save_zdcwpayment').linkbutton('enable');
					$('#btn_save_zdcwpayment').unbind();
					$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
				}
			}else if(data.STAT == '已批准待付款'){
				if(arrSearch('付款权',allAuth) || arrSearch('反批准权',allAuth)){
					$('#btn_save_zdcwpayment').linkbutton('enable');
					$('#btn_save_zdcwpayment').unbind();
					$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
				}
			}else if(data.STAT == '付款中'){
				//toolbar
				$('#btn1_zdcwpayment').linkbutton({
					iconCls:'icon-ok',
					text:'单笔付款',
					disabled: true
				});
				$('#btn2_zdcwpayment').linkbutton({
					iconCls:'icon-cancel',
					text:'取消付款',
					disabled: true
				});
				$('#btn3_zdcwpayment').linkbutton({
					iconCls:'icon-edit',
					text:'修改备注',
					disabled: true
				});
				$('#dg_zdcwpayment').datagrid({
					onSelect: rowPublicAct,
					toolbar: '#tb_dg_zdcwpayment',
					view:groupview,
	                groupField:'ACCOUNT',
	                groupFormatter:function(value,rows){
	                	var sumTotal = 0;
	                	for(var i=0; i<rows.length; i++){
	                		sumTotal = sumTotal + parseFloat(rows[i].TOTALAMT);
	                	}
	                	var sumStr = '<span style="color:blue;display:inline-block;width:190px;">分组合计：'+sumTotal.toFixed(2)+'</span>';
	                	var btnStr = '<a id="sumpay_'+value+'" account="'+value+'" href="javascript:void(0)"></a>';
	                	//alert(JSON.stringify(rows));
	                	return sumStr+btnStr;
	                },
				}).datagrid('showColumn','ck');
				$("a[id^='sumpay_']").linkbutton({
				    iconCls: 'icon-sum',
				    text:'合并付款',
				    disabled: true
				});
				
				//检查权限
				if(arrSearch('付款权',allAuth)){
					$('#btn_save_zdcwpayment').linkbutton('enable');
					$('#btn_save_zdcwpayment').unbind();
					$('#btn_save_zdcwpayment').bind('click', savePaymentAct);
					$('#btn1_zdcwpayment').linkbutton('enable');
					$("#btn1_zdcwpayment").unbind();
					$('#btn1_zdcwpayment').bind('click',{flag:'singlePay'},singlePayAction);
					$("a[id^='sumpay_']").linkbutton('enable');
					$("a[id^='sumpay_']").unbind();
					$("a[id^='sumpay_']").bind('click',{flag:'sumPay'},sumPayAction);
				}
				if(arrSearch('取消付款权',allAuth)){
					$('#btn2_zdcwpayment').linkbutton('enable');
					$("#btn2_zdcwpayment").unbind();
					$('#btn2_zdcwpayment').bind('click',{flag:'cancelSinglePay'},singlePayAction);
				}
				if(arrSearch('备注权',allAuth)){
					$('#btn3_zdcwpayment').linkbutton('enable');
					$("#btn3_zdcwpayment").unbind();
					$('#btn3_zdcwpayment').bind('click', singleNoteEdit);
				}
				/*
				$('#dg_zdcwpayment').datagrid({
					onClickCell: clickPaymentRowCell,
					//发现一旦编辑，则会导致分组合并上的按钮失效。
					//可能是endedit导致的问题，目前无解。
					//通过增加一个修改备注的按钮来解决。
				});
				*/
			}
			
			if(arrSearch('打印权',allAuth)){
				$('#btn_print_zdcwpayment').linkbutton({
				    iconCls: 'icon-print',
				    plain: false,
				    text: '打印',
				    disabled: true
				});
				$('#btn_print_zdcwpayment').linkbutton('enable');
				$('#btn_print_zdcwpayment').unbind();
				$('#btn_print_zdcwpayment').bind('click', printPaymentAct);
			}
			
		});
		
	}
});