$(document).ready(function(){
	//公用表格
	$('#dg_zdcwpayment').datagrid({
		title: moduletitle,
		iconCls:'icon-edit',
		rownumbers:false,
		nowrap:false,
		singleSelect: true,
		border: true,
		striped: true,
		showFooter: true,
		fitColumns: true,
		columns: [[
		    {field:'ITEMNO',title:'',halign:'center',align:'center',width:30,
		    	styler:function(value,row,index){
		    		return 'background-color:#efefef';
		    	}
			},
			{field:'ck',checkbox:true,hidden:true},
			{field:'ORG',title:'部门 / 项目',halign:'center',align:'center',width:200,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					panelHeight:'auto'
				}
			}},
			{field:'APPLICANT',title:'费用申请人',halign:'center',align:'center',width:200,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					panelHeight:'auto',
					onSelect: function(rec){
						var ed = $('#dg_zdcwpayment').datagrid('getEditor', {index:editPaymentIndex, field:'PAYEE'});
							$(ed.target).combobox('setValue', rec.value);
					}
				}
			}},
			{field:'PAYMENT',title:'付款事由',halign:'center',align:'center',width:150,editor:{
				type:'validatebox',
				options:{
					required:true,
					missingMessage:'必填项',
				}
			}},
			{field:'TOTALAMT',title:'金额',halign:'center',align:'right',width:150,editor:{
				type:'numberbox',
				options:{
					required:true,
					missingMessage:'必填项',
					min:0,
				    precision:2,
				    //groupSeparator:',',
				    //prefix:'￥'
				}
			},formatter: function(value,row,index){
				return parseFloat(value).toFixed(2);
			}},
			{field:'PAYEE',title:'收款人',halign:'center',align:'center',width:200,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					panelHeight:'auto',
					onChange: function(newValue, oldValue){
						$.getJSON("../public/php/getUser.php", {PARA: "BANK", PARA2: newValue}, function(data){
							//alert(JSON.stringify(data));
							var edbank = $('#dg_zdcwpayment').datagrid('getEditor', {index:editPaymentIndex, field:'BANK'});
							$(edbank.target).combobox('setValue', data.bank);
							var edaccount = $('#dg_zdcwpayment').datagrid('getEditor', {index:editPaymentIndex, field:'ACCOUNT'});
							$(edaccount.target).combobox('setValue', data.account);
						});
					}
				}
			}},
			{field:'BANK',title:'银行',halign:'center',align:'center',width:200,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					panelHeight:'auto',
					hasDownArrow:false,
					readonly:true
				}
			}},
			{field:'ACCOUNT',title:'账号',halign:'center',align:'center',width:250,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					panelHeight:'auto',
					hasDownArrow:false,
					readonly:true
				}
			}},
			{field:'NOTE',title:'备注',halign:'center',align:'center',width:100,editor:'text'},
			{field:'PAYSTAT',title:'付款状态',halign:'center',align:'center',width:100,
				styler: function(value,row,index){
					if (value == '已付款'){
						return {style:'color:green;font-weight:bold;'};
					}else if(value == '已取消付款'){
						return {style:'color:purple;font-weight:bold;'};
					}else{
						return {style:'color:red;font-weight:bold;'};
					}
				}
			},
			{field:'PAYER',title:'操作人',halign:'center',align:'center',width:80},
			{field:'PAYTIME',title:'操作时间',halign:'center',align:'center',width:120}
		]]
	});
	$('#btn_save_zdcwpayment').linkbutton({
	    iconCls: 'icon-save',
	    plain: false,
	    text: '保存',
	    disabled: true
	});
});

/**********行编辑*********************/
var editPaymentIndex = undefined;

function endEditingPayment(){
    if (editPaymentIndex == undefined){return true;}
    if ($('#dg_zdcwpayment').datagrid('validateRow', editPaymentIndex)){
        $('#dg_zdcwpayment').datagrid('endEdit', editPaymentIndex);
        editPaymentIndex = undefined;

        updateGridFooter();
        return true;
    } else {
    	$('#tb_dg_zdcwpayment').grumble({
    		text: '当前数据还未编辑结束!', 
    		angle: 300,
    		distance: 40, 
    		showAfter: 100,
    		hideAfter: 3000
    	});
        return false;
    }
}
function updateGridFooter(){
	var roweditors = $('#dg_zdcwpayment').datagrid('getRows');
	//alert(alert(JSON.stringify(roweditors)));
	var sumTotalamt = 0.00;
	$.each( roweditors, function(i, v){
		sumTotalamt = sumTotalamt + parseFloat(v.TOTALAMT);
	});
	var rows = $('#dg_zdcwpayment').datagrid('getFooterRows');
	rows[0]['TOTALAMT'] = sumTotalamt.toFixed(2);
	$('#dg_zdcwpayment').datagrid('reloadFooter');
}

function clickPaymentRow(rowIndex, rowData){
	//alert(JSON.stringify(rowData));
	if (rowData.PAYSTAT == '未付款'){
		if (editPaymentIndex != rowIndex){
			if (endEditingPayment()){
				$('#dg_zdcwpayment').datagrid('selectRow', rowIndex).datagrid('beginEdit', rowIndex);
				editPaymentIndex = rowIndex;
				setGridData('edit');
			} else {
				$('#dg_zdcwpayment').datagrid('selectRow', editPaymentIndex);
			}
		}
	}else{
		endEditingPayment();
	}
}
function appendPayment(){
	if (endEditingPayment()){
		var rows = $('#dg_zdcwpayment').datagrid('getRows');
		//alert(JSON.stringify(rows));
		editPaymentIndex = rows.length;
		var rownum = (editPaymentIndex+1).toString();
		$('#dg_zdcwpayment').datagrid('appendRow',{
			ITEMNO: rownum,
			ORG:'',
			APPLICANT:'',
			PAYMENT:'',
			TOTALAMT:0.00,
			PAYEE:'',
			BANK:'',
			ACCOUNT:'',
			NOTE:'',
			PAYSTAT:'未付款',
			PAYER:'',
			PAYTIME:''
		});      
        $('#dg_zdcwpayment').datagrid('selectRow', editPaymentIndex).datagrid('beginEdit', editPaymentIndex);

        setGridData('new');
    }
	var footer = $('#dg_zdcwpayment').datagrid('getFooterRows');
	if(footer == undefined){
		$('#dg_zdcwpayment').datagrid('reloadFooter',[{"ORG":"总计：","TOTALAMT":'0.00'}]);
	}
}

function setGridData(flag){
	//开始赋可选值和默认值
	//取得一行row的所有editors
	var roweditors = $('#dg_zdcwpayment').datagrid('getEditors', editPaymentIndex);
	//alert(JSON.stringify(roweditors));
	$.each( roweditors, function(i, v){
		switch(v.field)
		{
		case 'ORG':
			$.getJSON("../public/php/getOrgAndProject.php", function(data){
				$(v.target).combobox('loadData', data.all);
				if(flag == 'new')
					$(v.target).combobox('select', data.my);
			});
			break;
		case 'APPLICANT':	
			$.getJSON("../public/php/getUser.php", {PARA: "APPLICANT", PARA2:""}, function(data){
				$(v.target).combobox('loadData', data.all);
			});
			break;
		case 'TOTALAMT':
			$(v.target).css({ "text-align":"right" });
			break;
		case 'PAYEE':	
			$.getJSON("../public/php/getUser.php", {PARA: "PAYEE", PARA2:""}, function(data){
				$(v.target).combobox('loadData', data.all);
			});
			break;
		case 'PAYSTAT':
			$(v.target).css({ "text-align":"center"});
			$(v.target).attr("readonly","readonly");        		
			break;
		case 'PAYER':
			$(v.target).css({ "text-align":"center"});
			$(v.target).attr("readonly","readonly");
			break;
		case 'PAYTIME':
			$(v.target).css({ "text-align":"center"});
			$(v.target).attr("readonly","readonly");
			break;
		}
		
	});
	//结束赋值
}
function removePayment(){
    if (editPaymentIndex == undefined){
    	$('#btn2_zdcwpayment').grumble(missSelectMsg);
    	return false;
    }
    $('#dg_zdcwpayment').datagrid('cancelEdit', editPaymentIndex).datagrid('deleteRow', editPaymentIndex);
    editPaymentIndex = undefined;
}


/*******单元格编辑*目前无用***********/
function endEditingPaymentCell(){
    if (editPaymentIndex == undefined){return true;}
    $('#dg_zdcwpayment').datagrid('endEdit', editPaymentIndex);
    editPaymentIndex = undefined;
    return true;
}
function clickPaymentRowCell(rowIndex, field, value){
	if(field == 'NOTE'){
		if (editPaymentIndex != rowIndex){
			if (endEditingPaymentCell()){
				$('#dg_zdcwpayment').datagrid('selectRow', rowIndex).datagrid('editCell', {index:rowIndex,field:field});
				editPaymentIndex = rowIndex;				
			} else {
				$('#dg_zdcwpayment').datagrid('selectRow', editPaymentIndex);
			}
		}
	}
}
$.extend($.fn.datagrid.methods, {
    editCell: function(jq,param){
        return jq.each(function(){
            var fields = $(this).datagrid('getColumnFields',true).concat($(this).datagrid('getColumnFields'));
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor1 = col.editor;
                if (fields[i] != param.field){
                    col.editor = null;
                }
            }
            $(this).datagrid('beginEdit', param.index);
            for(var i=0; i<fields.length; i++){
                var col = $(this).datagrid('getColumnOption', fields[i]);
                col.editor = col.editor1;
            }
        });
    }
});


/********保存提交*****************/
function savePaymentAct(){
var isValidate = $('#fm_zdcwpayment').form('validate');
if(isValidate && checkValue($('#billnum_zdcwpayment'), '付款汇总表编号', '付款汇总表')){
	if (endEditingPayment()){
	art.dialog({
	    content: '确定要保存吗？',
	    ok: function(){
			$.messager.progress();	// display the progress bar
			$('#fm_zdcwpayment').form('submit',{
				url: '../'+modulepath+'/savePayment.php',
				onSubmit: function(param){
					
					
					var paymentRows = $('#dg_zdcwpayment').datagrid('getRows');
					//alert(JSON.stringify(paymentRows));
	    			param.MODULENO = moduleno;
	    	        param.MODULEOBJ = moduleobj;
	    	        param.MODULETITLE = moduletitle;
	    	        param.PAYMENTROWS = JSON.stringify(paymentRows);
	    	        param.FLAG = paymentViewMode;
					return true;
				},
				success: function(data){
					$.messager.progress('close');
					//alert(data);
					var dataObj = eval('(' + data + ')');  // change the JSON string to javascript object
					/**这里会有隐患，如果后台php出错，会导致这个data不是json string，
					从而无法转换为对象，即：上述eval函数无法执行成功。
					如果要debug，则直接将data打印出来即可。alert(data);*/
			        if (dataObj.success){
			        	art.dialog({
			        	    content: dataObj.message,
			        	    ok: function(){
			        	    	$('#tt').tabs('getTab',moduletitle).panel('refresh');
				        	}
			        	});				
					} else {
						art.dialog({
			        	    content: dataObj.message,
			        	    ok: function(){
				        	}
			        	});
					}
				}
			});
	    },
	    cancel: true
	});
	}
}
}

function singlePayAction(event){
	//alert(JSON.stringify(event));
	//console.warn( JSON.stringify(event) );
	var cfmsg = '';
	var warningmsg = '';
	var titlemsg = '';
	var flag = event.data.flag;
	var row = $('#dg_zdcwpayment').datagrid('getSelected');
	if (row){
		if(row.PAYSTAT != '未付款'){
			if(flag == 'singlePay' && row.PAYSTAT == '已付款'){
				warningmsg = '第 '+row.ITEMNO+' 行已经付过款，请勿重复操作！';
			}
			if(flag == 'singlePay' && row.PAYSTAT == '已取消付款'){
				warningmsg = '第 '+row.ITEMNO+' 行已被取消付款，不能付款！';
			}
			if(flag == 'cancelSinglePay' && row.PAYSTAT == '已取消付款'){
				warningmsg = '第 '+row.ITEMNO+' 行已经取消付款，请勿重复操作！';
			}
			if(flag == 'cancelSinglePay' && row.PAYSTAT == '已付款'){
				warningmsg = '第 '+row.ITEMNO+' 行已经付过款，不能取消付款！';
			}

			art.dialog({
			    content: warningmsg,
			    ok: true
			});
		}else{
			if(flag == 'singlePay'){
				titlemsg = '付款信息确认：';
				cfmsg = '<table>'
					+'<tr><td>收款人：</td><td>'+row.PAYEE+'</td></tr>'
					+'<tr><td>收款银行：</td><td>'+row.BANK+'</td></tr>'
					+'<tr><td>收款账户：</td><td>'+row.ACCOUNT+'</td></tr>'
					+'<tr><td>金额：</td><td>'+row.TOTALAMT+'</td></tr>'
					+'</table>'
					+'</br>确定付款吗？';
			}
			if(flag == 'cancelSinglePay'){
				titlemsg = '取消付款信息：';
				cfmsg = '<table>'
					+'<tr><td>收款人：</td><td>'+row.PAYEE+'</td></tr>'
					+'<tr><td>收款银行：</td><td>'+row.BANK+'</td></tr>'
					+'<tr><td>收款账户：</td><td>'+row.ACCOUNT+'</td></tr>'
					+'<tr><td>金额：</td><td>'+row.TOTALAMT+'</td></tr>'
					+'</table>'
					+'</br>确定取消付款吗？';
			}
			
			art.dialog({
				title: titlemsg,
	    	    content: cfmsg,
	    	    ok: function(){
					$.post(
						'../'+modulepath+'/singlePayAction.php',
						{
							MODULENO:moduleno,
							MODULEOBJ:moduleobj,
							MODULETITLE:moduletitle,
							NUM:row.NUM,
							ITEMNO:row.ITEMNO,
							FLAG:flag
						},
						function(result){
							//alert(JSON.stringify(result));
							if (result.success){
								art.dialog({
					        	    content: result.message,
					        	    ok: function(){
					        	    	$('#tt').tabs('getTab',moduletitle).panel('refresh');
					        	    }
					        	});
							}else{
								art.dialog({
					        	    content: result.message,
					        	    ok: true
					        	});
							}
						},
						'json'
					);
				},
				cancel: true
			});
		}
	}else{
		if(flag == 'singlePay')
			$('#btn1_zdcwpayment').grumble(missSelectMsg);
		if(flag == 'cancelSinglePay')
			$('#btn2_zdcwpayment').grumble(missSelectMsg);
		
	}
}
function sumPayAction(event){
	//alert(JSON.stringify(event));
	var cfmsg = '';
	var titlemsg = '';
	var flag = event.data.flag;
	var account = $(event.currentTarget).attr("account");
	var groups = event.view.groupview.groups;
	var rows = undefined;
	for(var i=0; i<groups.length; i++){
		if(groups[i].value == account){
			rows = groups[i].rows;
			break;
		}
	}
	//alert(JSON.stringify(rows));
	var isok = true;
	if(flag == 'sumPay'){
		titlemsg = '合并付款信息确认：';
		var sumTotal = 0;
		for(var i=0; i<rows.length; i++){
			if(rows[i].PAYSTAT != '未付款'){
				art.dialog({
				    content: '合并付款的申请必须均为未付款状态！如其中有未付款，请单笔付款！',
				    ok: true
				});
				isok = false;
				break;
			}else{
				sumTotal = sumTotal + parseFloat(rows[i].TOTALAMT);
			}
		}
		cfmsg = '<table>'
			+'<tr><td>收款人：</td><td>'+rows[0].PAYEE+'</td></tr>'
			+'<tr><td>收款银行：</td><td>'+rows[0].BANK+'</td></tr>'
			+'<tr><td>收款账户：</td><td>'+rows[0].ACCOUNT+'</td></tr>'
			+'<tr><td>合并金额：</td><td>'+sumTotal.toFixed(2)+'</td></tr>'
			+'</table>'
			+'</br>确定合并付款吗？';
	}
	
	if(isok){
		art.dialog({
			title: titlemsg,
    	    content: cfmsg,
    	    ok: function(){
				$.post(
					'../'+modulepath+'/sumPayAction.php',
					{
						MODULENO:moduleno,
						MODULEOBJ:moduleobj,
						MODULETITLE:moduletitle,
						ROWS:JSON.stringify(rows),
						FLAG:flag
					},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							art.dialog({
				        	    content: result.message,
				        	    ok: function(){
				        	    	$('#tt').tabs('getTab',moduletitle).panel('refresh');
				        	    }
				        	});
						}else{
							art.dialog({
				        	    content: result.message,
				        	    ok: true
				        	});
						}
					},
					'json'
				);
			},
			cancel: true
		});
	
	}
	
}
/*****修改备注*****************************/
function singleNoteEdit(){
	var row = $('#dg_zdcwpayment').datagrid('getSelected');
	if (row){
		art.dialog.promptNote('备注信息：', row.NOTE, function (val) {
			$.post(
					'../'+modulepath+'/singleNoteEdit.php',
					{
						MODULENO:moduleno,
						MODULEOBJ:moduleobj,
						MODULETITLE:moduletitle,
						NUM:row.NUM,
						ITEMNO:row.ITEMNO,
						NOTE:val
					},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							art.dialog({
				        	    content: result.message,
				        	    ok: function(){
				        	    	$('#tt').tabs('getTab',moduletitle).panel('refresh');
				        	    }
				        	});
						}else{
							art.dialog({
				        	    content: result.message,
				        	    ok: true
				        	});
						}
					},
					'json'
				);
		});
	}else{
		$('#btn3_zdcwpayment').grumble(missSelectMsg);
	}
}

artDialog.promptNote = function (content, value, yes) {
    value = value || '';
    var input;
    return artDialog({
    	title: content,
        fixed: true,
        lock: true,
        opacity: .1,
        content: [
            '<div>',
                '<input value="',
                    value,
                '" style="width:18em;padding:6px 4px" />',
            '</div>'
            ].join(''),
        init: function () {
            input = this.DOM.content.find('input')[0];
            input.select();
            input.focus();
        },
        ok: function (here) {
            return yes && yes.call(this, input.value, here);
        },
        cancel: true
    });
};
