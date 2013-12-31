$(document).ready(function(){
	$('#dg_zdcwpaymentview').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar:'#toolbar_zdcwpaymentview',
		nowrap:false,
		border: true,
		striped: true,
		columns: [[
			{field:'NUM',title:'系统单号',align:'center',width:30,
				formatter: function(value,row,index){
					return '<a href="javascript:void(0)" onclick="actView(\''+value+'\')">'+value+'</a>';
				}
			},
			{field:'ORG',title:'所属机构',align:'center',width:30},
			{field:'BILLNUM',title:'付款汇总表编号',align:'center',width:30},
			{field:'STAT',title:'状态',align:'center',width:20},
			{field:'INPUTTER',title:'录入人',align:'center',width:30},
			{field:'INPUTTIME',title:'录入时间',align:'center',width:30},
			{field:'CHECKER',title:'审核人',align:'center',width:30},
			{field:'CHECKTIME',title:'审核时间',align:'center',width:30},
			{field:'APPROVER',title:'批准人',align:'center',width:30},
			{field:'APPROVETIME',title:'批准时间',align:'center',width:30},
			{field:'NOTE',title:'备注',align:'center',width:10},
		]]
	});
	
	
	$('#btn_search_zdcwpaymentview').linkbutton({
	    iconCls: 'icon-search',
	    plain: false,
	    text: '查询',
	    disabled: false
	});
	
	$.getJSON('../'+modulepath+'/getSearchSelect.php', function(data){
		//alert(JSON.stringify(data));
		//组织
		$('#org_zdcwpaymentview').combobox('loadData', data.allOrg);
		$('#org_zdcwpaymentview').combobox('select', data.myOrg);
		//状态
		$('#stat_zdcwpaymentview').combobox('loadData', data.allStat);
		$('#stat_zdcwpaymentview').combobox('select', data.myStat);
		//录入人、审核人、批准人
		$('#inputter_zdcwpaymentview').combobox('loadData', data.allInputter);
		$('#inputter_zdcwpaymentview').combobox('select', data.myInputter);
		$('#checker_zdcwpaymentview').combobox('loadData', data.allChecker);
		$('#checker_zdcwpaymentview').combobox('select', data.myChecker);
		$('#approver_zdcwpaymentview').combobox('loadData', data.allApprover);
		$('#approver_zdcwpaymentview').combobox('select', data.myApprover);
		
		searchPayment();
	});
});

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

function searchPayment(){
	var isValidate = $(this).form('validate');
	if(isValidate){
    	$.messager.progress();	// display the progress bar
    	$('#search_fm_zdcwpaymentview').form('submit',{
    		url: '../'+modulepath+'/getInfo.php',
    		onSubmit: function(param){
    			return true;
    		},
    		success: function(data){
    			$.messager.progress('close');
    			//alert(data);
    			var dataObj = eval('(' + data + ')');  // change the JSON string to javascript object
    			/**这里会有隐患，如果后台php出错，会导致这个data不是json string，
    			从而无法转换为对象，即：上述eval函数无法执行成功。
    			如果要debug，则直接将data打印出来即可。alert(data);*/
    			//alert(JSON.stringify(dataObj));
    			$('#dg_zdcwpaymentview').datagrid('loadData',dataObj);
    		}
    	});
	}
}