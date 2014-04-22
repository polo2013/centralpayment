$(document).ready(function(){
	$('#dg_zdcwimpfromoa').datagrid({
		title: moduletitle,
		pagination: true,
		pageSize: 200,
		pageList: [200,400,600,800],
		rownumbers: true,
		fitColumns: true,
		singleSelect: false,
		toolbar:'#toolbar_zdcwimpfromoa',
		nowrap:false,
		border: true,
		striped: true,
		columns: [[
		    {field:'ck',checkbox:true},
			{field:'FLOWINFO',title:'流程信息',halign:'center',align:'left',width:250},
			{field:'ORG',title:'部门 / 项目',halign:'center',align:'center',width:150},
			{field:'APPLICANT',title:'费用申请人',halign:'center',align:'center',width:100},
			{field:'PAYMENT',title:'付款事由',halign:'center',align:'center',width:250,editor:{
				type:'validatebox',
				options:{
					required:true,
					missingMessage:'必填项'
				}
			}},
			{field:'CURRENCY',title:'币别',halign:'center',align:'center',width:60,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'必填项',
					editable:false,
					hasDownArrow:false,
					panelHeight:'auto'
				}
			}},
			{field:'TOTALAMT',title:'金额',halign:'center',align:'right',width:100,editor:{
				type:'numberbox',
				options:{
					required:true,
					missingMessage:'必填项',
					min:0,
				    precision:2,
				    validType:'not_eq_zero[0]'
				}
			},formatter: function(value,row,index){
				return parseFloat(value).toFixed(2);
			}},
			{field:'PAYEE',title:'收款人',halign:'center',align:'center',width:100,editor:{
				type:'combobox',
				options:{
					required:true,
					missingMessage:'若无选择项，请确认用户或收款人资料已复核',
					editable:false,
					//panelHeight:'auto',
					onChange: function(newValue, oldValue){
						$.getJSON("../public/php/getUser.php", {PARA: "IMPOA_BANK", PARA2: newValue}, function(data){
							//alert(JSON.stringify(data));
							var edbank = $('#dg_zdcwimpfromoa').datagrid('getEditor', {index:editImpFromOAIndex, field:'BANK'});
							$(edbank.target).combobox('setValue', data.bank);
							var edaccount = $('#dg_zdcwimpfromoa').datagrid('getEditor', {index:editImpFromOAIndex, field:'ACCOUNT'});
							$(edaccount.target).combobox('setValue', data.account);
						});
					}
				}
			}},
			{field:'BANK',title:'银行',halign:'center',align:'center',width:100,editor:{
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
			{field:'ACCOUNT',title:'账号',halign:'center',align:'center',width:180,editor:{
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
			{field:'NOTE',title:'备注',halign:'center',align:'center',width:200,
				styler: function(value,row,index){
					if (row.FLAG == "否"){
						return 'color:red;';
					}
				},editor:{
					type:'validatebox'
				}
			},
			{field:'FLAG',title:'可导',halign:'center',align:'center',width:40,
				styler: function(value,row,index){
					if (value == "否"){
						return 'color:red;';
					}
				},editor:{
					type:'validatebox'
				}
			},
		]],
		/*rowStyler: function(index,row){
			if (row.FLAG == "否"){
				return 'background-color: white;'; // return inline style
				// the function can return predefined css class and inline style
				// return {class:'r1', style:{'color:#fff'}};	
			}
		},*/
		onLoadError: function(){
			jQuery('body').hideLoading();
			art.dialog({
				content: '连接 OA 服务器通讯失败！',
				ok: true
        	});
		},
		onLoadSuccess:function(data){
			jQuery('body').hideLoading();
			if (data.rows.length > 0) {
				$('#searchinfo_zdcwimpfromoa').html(data.msg);
				
				for (var i = 0; i < data.rows.length; i++) {
					//根据flag让某些行不可选
					if (data.rows[i].FLAG == "否") {
						$("input[type='checkbox']")[i + 1].disabled = true;
						
					}
				}
				
			}
		},
		onClickRow: clickImpFromOARow,
        onSelectAll: function(rows){
        	for (var i = 0; i < rows.length; i++) {
				if (rows[i].FLAG == "否") {
					$('#dg_zdcwimpfromoa').datagrid('unselectRow', i);
					$('#dg_zdcwimpfromoa').datagrid('uncheckRow', i);
				}
			}
        },
        onCheckAll: function(rows){
        	for (var i = 0; i < rows.length; i++) {
				if (rows[i].FLAG == "否") {
					$('#dg_zdcwimpfromoa').datagrid('unselectRow', i);
					$('#dg_zdcwimpfromoa').datagrid('uncheckRow', i);
				}
			}
        }
	});

	
	$('#dg_setting_zdcwimpfromoa').datagrid({
		iconCls:'icon-edit',
		onClickRow:clickSettingsRow,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#tb_setting_zdcwimpfromoa',
		border: true,
		striped: true,
		columns: [[
			{field:'ORG',title:'组织机构',width:20,editor:{
				type:'validatebox',
				options:{
					required:true
				}
			}},
			{field:'ORGDESC',title:'相应查询条件',width:50,editor:{
				type:'validatebox',
				options:{
					required:true
				}
			}},
			{field:'FLOWID',title:'OA流程内部ID',width:30,editor:{
				type:'validatebox',
				options:{
					required:true
				}
			}},
			{field:'FLOWNAME',title:'OA流程名称',width:30,editor:{
				type:'validatebox',
				options:{
					required:true
				}
			}}
			
		]]
		
	});
	
	$('#btn_selectall_zdcwimpfromoa').linkbutton({
	    plain: false,
	    text: '全选',
	    disabled: false
	});
	$('#btn_unselectall_zdcwimpfromoa').linkbutton({
	    plain: false,
	    text: '取消全选',
	    disabled: false
	});
	
	
	$.getJSON('../'+modulepath+'/getSearchSelect.php', function(data){
		//alert(JSON.stringify(data));
		//组织
		$('#org_zdcwimpfromoa').combobox('loadData', data.allOrg);
		$('#org_zdcwimpfromoa').combobox('select', data.allOrg[0]['value']);
		//流程类型
		$('#flowtype_zdcwimpfromoa').combobox('loadData', data.allFlow);
		$('#flowtype_zdcwimpfromoa').combobox('select', data.allFlow[0]['value']);
		//状态
		$('#flowstat_zdcwimpfromoa').combobox('loadData', data.allStat);
		$('#flowstat_zdcwimpfromoa').combobox('select', data.allStat[0]['value']);
		
		//检查权限
		if(arrSearch('搜索权',allAuth)){
			$('#btn_search_zdcwimpfromoa').linkbutton({
			    iconCls: 'icon-search',
			    plain: false,
			    text: '搜索',
			    disabled: false
			});
			$('#btn_search_zdcwimpfromoa').unbind();
			$('#btn_search_zdcwimpfromoa').bind('click', searchOAFlow);
		}
		if(arrSearch('生成单据权',allAuth)){
			$('#btn_genpayment_zdcwimpfromoa').linkbutton({
			    iconCls: 'icon-ok',
			    plain: false,
			    text: '生成付款汇总表',
			    disabled: false
			});
			$("#btn_genpayment_zdcwimpfromoa").unbind();
			$('#btn_genpayment_zdcwimpfromoa').bind('click', genPaymentFromOA);
		}
		if(arrSearch('设置权',allAuth)){
			$('#btn_setting_zdcwimpfromoa').linkbutton({
			    plain: false,
			    text: '设置',
			    disabled: false
			});
			$("#btn_setting_zdcwimpfromoa").unbind();
			$('#btn_setting_zdcwimpfromoa').bind('click', showSettings);
		}
		if(arrSearch('导入信息删除权',allAuth)){
			$('#btn_DelImpInfo_zdcwimpfromoa').linkbutton({
			    plain: false,
			    text: '导入信息删除',
			    disabled: false
			});
			$("#btn_DelImpInfo_zdcwimpfromoa").unbind();
			$('#btn_DelImpInfo_zdcwimpfromoa').bind('click', showDelImpInfo);
		}
	});

	
});
function selectall_impfromoa(){
	$('#dg_zdcwimpfromoa').datagrid('selectAll');
}
function unselectall_impfromoa(){
	$('#dg_zdcwimpfromoa').datagrid('unselectAll');
}
function myformatter_Begin(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d)+' 00:00:00';
}
function myformatter_End(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d)+' 23:59:59';
}

function myparser(s){
    if (!s) return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0],10);
    var m = parseInt(ss[1],10);
    var d = parseInt(ss[2],10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        return new Date(y,m-1,d);
    } else {
        return new Date();
    }
}

$.extend($.fn.validatebox.defaults.rules, {
    not_eq_zero: {
        validator: function(value, param){
        	if(value == 0){
        		return false;
        	}else{
        		return true;
        	}
        },
        message: '不能为空或为零'
    }
});

function searchOAFlow(){
	var isValidate = $(this).form('validate');
	if(isValidate){
    	//$.messager.progress();	// display the progress bar
		jQuery('body').showLoading();
    	
    	var org = $('#org_zdcwimpfromoa').combobox('getValue');
    	//alert(org);
    	var flowtype = $('#flowtype_zdcwimpfromoa').combobox('getValue');
    	//alert(flowtype);
    	var flowstat = $('#flowstat_zdcwimpfromoa').combobox('getValue');
    	//alert(flowstat);
    	
    	var beginner = $('#beginner_zdcwimpfromoa').val();
    	var begintime1 = $('#begintime1_zdcwimpfromoa').datebox('getText');
    	var begintime2 = $('#begintime2_zdcwimpfromoa').datebox('getText');
    	
    	
    	var url = '../'+modulepath+'/getInfo.php';
    	var param = 'ORG='+org+'&FLOWTYPE='+flowtype+'&FLOWSTAT='+flowstat
    	+'&BEGINNER='+beginner+'&BEGINTIME1='+begintime1+'&BEGINTIME2='+begintime2;
    	
    	$('#dg_zdcwimpfromoa').datagrid({
    		url: encodeURI(url+'?'+param)
    	});
    	
    	//$.messager.progress('close');
	}
}

function genPaymentFromOA(){
	if(! endEditingImpFromOA()){
		art.dialog({
    	    content: alarmImpFromOA,
    	    ok: true,
    	    cancel: function(){cancelEditingImpFromOA();}
    	});
	}else{
		var org = $('#org_zdcwimpfromoa').combobox('getText');
		var flowtype = $('#flowtype_zdcwimpfromoa').combobox('getText');
		var rows = $('#dg_zdcwimpfromoa').datagrid('getSelections');
		//alert(JSON.stringify(rows));
		if (rows.length > 0){
			jQuery('body').showLoading();
			$.post(
				'../'+modulepath+'/checkExistBill.php',
				{
					ORG : org
				},
				function(result){
					//alert(JSON.stringify(result));
					if (result.success){
						if (result.message != ""){
							art.dialog({
				        	    content: result.message,
				        	    ok: function(){
				        	    	//生成单据
				        	    	genPaymentAction(org, flowtype, 'merge', rows, result.exist_bill);
				        	    },
				        	    cancel:function(){
				        	    	jQuery('body').hideLoading();
				        	    }
				        	});
						}else{
							art.dialog({
				        	    content: "确定将所选报销流程生成付款汇总表吗？",
				        	    ok: function(){
				        	    	//生成单据
				        	    	genPaymentAction(org, flowtype, 'nomerge', rows, result.exist_bill);
				        	    },
				        	    cancel:function(){
				        	    	jQuery('body').hideLoading();
				        	    }
				        	});
						}
					}else{
		        	    jQuery('body').hideLoading();
						art.dialog({
			        	    content: result.message,
			        	    ok: true
			        	});
					}
				},
				'json'
			);
		}else{
			$('#btn_genpayment_zdcwimpfromoa').grumble(missSelectMsg);
		}
	}
}
function genPaymentAction(org, flowtype, is_merge, rows, exist_bill){
	$.post(
		'../'+modulepath+'/genBill.php',
		{
			ORG      : org,
			FLOWTYPE : flowtype,
			MERGE    : is_merge,
			ROWS     : JSON.stringify(rows),
			EXIST_BILL : JSON.stringify(exist_bill),
		},
		function(resultdata){
			//alert(JSON.stringify(resultdata));
			if (resultdata.success){
				art.dialog({
	        	    content: resultdata.message,
	        	    ok: function(){
	        	    	//打开单据 
	        	    	actView(resultdata.num);
	        	    }
	        	});
			}else{
				art.dialog({
	        	    content: resultdata.message,
	        	    ok: true
	        	});
			}
			jQuery('body').hideLoading();
		},
		'json'
	);
}


/**********行编辑*********************/
var editImpFromOAIndex = undefined;
var alarmImpFromOA = '修改的数据没有符合要求！<br /><br />确定：继续修改。<br />取消：放弃修改。';

function endEditingImpFromOA(){
    if (editImpFromOAIndex == undefined){return true;}
    if ($('#dg_zdcwimpfromoa').datagrid('validateRow', editImpFromOAIndex)){
    	//修改备注和Flag
    	var roweditors = $('#dg_zdcwimpfromoa').datagrid('getEditors', editImpFromOAIndex);
    	//alert(JSON.stringify(roweditors));
    	$.each( roweditors, function(i, v){
    		switch(v.field)
    		{
    		case 'NOTE':
    			$(v.target).val('导入后修正');
    			break;
    		case 'FLAG':
    			$(v.target).val('是');
    			break;
    		}
    		
    	});
    	
        $('#dg_zdcwimpfromoa').datagrid('endEdit', editImpFromOAIndex);
        editImpFromOAIndex = undefined;
        return true;
    } else {
    	return false;
    }
}
function cancelEditingImpFromOA(){
	//alert(editImpFromOAIndex);
	$('#dg_zdcwimpfromoa').datagrid('cancelEdit', editImpFromOAIndex);
	$("input[type='checkbox']")[editImpFromOAIndex + 1].disabled = true;
	editImpFromOAIndex = undefined;

}

function clickImpFromOARow(rowIndex, rowData){
	//alert(JSON.stringify(rowData));
	if ((rowData.FLAG == "否") || (rowData.FLAG == "是" && rowData.NOTE == "导入后修正")) {
		//alert(rowIndex);
		//alert(editImpFromOAIndex);
		if (editImpFromOAIndex != rowIndex){
			if (endEditingImpFromOA()){
				$('#dg_zdcwimpfromoa').datagrid('selectRow', rowIndex).datagrid('beginEdit', rowIndex);
				editImpFromOAIndex = rowIndex;
				setImpFromOAGridData('edit');
			} else {
				art.dialog({
		    	    content: alarmImpFromOA,
		    	    ok: true,
		    	    cancel: function(){cancelEditingImpFromOA();}
		    	});
			}
			
		}
		
	}else{
		if(! endEditingImpFromOA()){
			art.dialog({
	    	    content: alarmImpFromOA,
	    	    ok: true,
	    	    cancel: function(){cancelEditingImpFromOA();}
	    	});
			
		}
	}
	$('#dg_zdcwimpfromoa').datagrid('unselectRow', rowIndex);
	$('#dg_zdcwimpfromoa').datagrid('uncheckRow', rowIndex);
}

function setImpFromOAGridData(flag){
	var roweditors = $('#dg_zdcwimpfromoa').datagrid('getEditors', editImpFromOAIndex);
	//alert(JSON.stringify(roweditors));
	$.each( roweditors, function(i, v){
		switch(v.field)
		{
		case 'CURRENCY':
			$.getJSON("../public/php/getCurrency.php", function(data){
				$(v.target).combobox('loadData', data.all);
				//$(v.target).combobox('select', v.oldHtml);
			});
			break;
		case 'TOTALAMT':
			$(v.target).css({ "text-align":"right" });
			break;
		case 'PAYEE':	
			$.getJSON("../public/php/getUser.php", {PARA: "IMPOA_PAYEE", PARA2:""}, function(data){
				$(v.target).combobox('loadData', data.all);
				//$(v.target).combobox('select', v.oldHtml);
				
			});
			break;
		case 'NOTE':
			$(v.target).attr("readonly","readonly");
			break;
		case 'FLAG':
			$(v.target).attr("readonly","readonly");
			break;

		}
		
	});
	//结束赋值
}


/******************end**********************/

/******************模块选项**********************/
function showSettings(){
	$('#dlg_setting_zdcwimpfromoa').dialog('open').dialog('setTitle','设置').dialog('center').dialog('move',{top:100});
	$('#dg_setting_zdcwimpfromoa').datagrid({
		url: encodeURI('../'+modulepath+'/getSettingsInfo.php')
	});
}

var editSettingsIndex = undefined;

function endEditingSettings(){
    if (editSettingsIndex == undefined){return true}
    if ($('#dg_setting_zdcwimpfromoa').datagrid('validateRow', editSettingsIndex)){
        $('#dg_setting_zdcwimpfromoa').datagrid('endEdit', editSettingsIndex);
        editSettingsIndex = undefined;
        return true;
    } else {
        return false;
    }
}
function clickSettingsRow(rowIndex, rowData){
    if (editSettingsIndex != rowIndex){
        if (endEditingSettings()){
            $('#dg_setting_zdcwimpfromoa').datagrid('selectRow', rowIndex).datagrid('beginEdit', rowIndex);
            editSettingsIndex = rowIndex;
        } else {
            $('#dg_setting_zdcwimpfromoa').datagrid('selectRow', editSettingsIndex);
        }
    }
}
function appendSettings(){
    if (endEditingSettings()){
    	var rows = $('#dg_setting_zdcwimpfromoa').datagrid('getRows');
    	//alert(JSON.stringify(rows));
    	editSettingsIndex = rows.length;
        $('#dg_setting_zdcwimpfromoa').datagrid('appendRow',{ORG:'',ORGDESC:'',FLOWID:'',FLOWNAME:''});
        $('#dg_setting_zdcwimpfromoa').datagrid('selectRow', editSettingsIndex).datagrid('beginEdit', editSettingsIndex);
        
    }
}
function removeSettings(){
    if (editSettingsIndex == undefined){
    	return false;
    }
    $('#dg_setting_zdcwimpfromoa').datagrid('cancelEdit', editSettingsIndex).datagrid('deleteRow', editSettingsIndex);
    editSettingsIndex = undefined;
}
function saveSettingsAct(){
	if (endEditingSettings()){
	art.dialog({
	    content: '确定要保存这些设置吗？',
	    ok: function(){
	    	var row_Settings = $('#dg_setting_zdcwimpfromoa').datagrid('getRows');
	    	$.post(
	    		'../'+modulepath+'/saveSettings.php',
    			{
    			"Settings": encodeURI(JSON.stringify(row_Settings))
    			},
    			function(result){
    				//alert(JSON.stringify(result));
					if (result.success){
						art.dialog({
	    	        	    content: result.message,
	    	        	    ok: function(){
	    	        	    	//$('#dg_setting_zdcwimpfromoa').datagrid('reload');    // reload the user data
	    	        	    	$('#dlg_setting_zdcwimpfromoa').dialog('close'); 
	    		        	}
	    	        	});
					} else {
						art.dialog({
			        	    content: result.message,
			        	    ok: true
			        	});
					}
    			}, 
    			"json"
    		);
	    },
	    cancel: true
	});
	}
}
/******************模块选项**end********************/


/******************删除导入信息**********************/
function showDelImpInfo(){
	$('#dlg_DelImpInfo_zdcwimpfromoa').dialog('open').dialog('setTitle','删除导入信息').dialog('center').dialog('move',{top:100});
	$('#DelImpInfo_fm_zdcwimpfromoa').form('clear');
}
function saveDelImpInfoAct(){
	var isValidate = $('#DelImpInfo_fm_zdcwimpfromoa').form('validate');
	if(isValidate){
		art.dialog({
		    content: '确定要删除该流程的导入信息吗？',
		    ok: function(){
		    	var flowid = $('#DelImpInfo_flowid_zdcwimpfromoa').val();
		    	var flowrunid = $('#DelImpInfo_flowrunid_zdcwimpfromoa').val();
		    	var num = $('#DelImpInfo_num_zdcwimpfromoa').val();
		    	var applicant = $('#DelImpInfo_applicant_zdcwimpfromoa').val();
		    	var amount = $('#DelImpInfo_amount_zdcwimpfromoa').val();

		    	$.post(
		    		'../'+modulepath+'/DelImpInfo.php',
	    			{
		    			flowid: encodeURI(flowid),
		    			flowrunid: encodeURI(flowrunid),
		    			num: encodeURI(num),
		    			applicant: encodeURI(applicant),
		    			amount: encodeURI(amount),
	    			},
	    			function(result){
	    				//alert(JSON.stringify(result));
						if (result.success){
							art.dialog({
		    	        	    content: result.message,
		    	        	    ok: function(){
		    	        	    	$('#dlg_DelImpInfo_zdcwimpfromoa').dialog('close'); 
		    		        	}
		    	        	});
						} else {
							art.dialog({
				        	    content: result.message,
				        	    ok: true
				        	});
						}
	    			}, 
	    			"json"
	    		);
		    },
		    cancel: true
		});
	}
}
/******************删除导入信息end**********************/