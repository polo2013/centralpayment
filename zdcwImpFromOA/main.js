$(document).ready(function(){
	$('#dg_zdcwimpfromoa').datagrid({
		title: moduletitle,
		pagination: true,
		pageSize: 10,
		pageList: [10,20],
		rownumbers: true,
		fitColumns: true,
		singleSelect: false,
		toolbar:'#toolbar_zdcwimpfromoa',
		nowrap:false,
		border: true,
		striped: true,
		columns: [[
		    {field:'ck',checkbox:true},
			{field:'OAFLOW',title:'OA流程',align:'center',width:200},
			{field:'ORG',title:'部门 / 项目',halign:'center',align:'center',width:200},
			{field:'APPLICANT',title:'费用申请人',halign:'center',align:'center',width:200},
			{field:'PAYMENT',title:'付款事由',halign:'center',align:'center',width:150},
			{field:'TOTALAMT',title:'金额',halign:'center',align:'right',width:150},
			{field:'PAYEE',title:'收款人',halign:'center',align:'center',width:200},
			{field:'BANK',title:'银行',halign:'center',align:'center',width:200},
			{field:'ACCOUNT',title:'账号',halign:'center',align:'center',width:250},
			{field:'NOTE',title:'备注',halign:'center',align:'center',width:100},
		]]
	});
	
	
	$('#btn_search_zdcwimpfromoa').linkbutton({
	    iconCls: 'icon-search',
	    plain: false,
	    text: '搜索',
	    disabled: false
	});
	
	$('#btn_genpayment_zdcwimpfromoa').linkbutton({
	    iconCls: 'icon-ok',
	    plain: false,
	    text: '生成付款汇总表',
	    disabled: false
	});
	/*
	$.getJSON('../'+modulepath+'/getSearchSelect.php', function(data){
		//alert(JSON.stringify(data));
		//组织
		$('#org_zdcwimpfromoa').combobox('loadData', data.allOrg);
		$('#org_zdcwimpfromoa').combobox('select', data.myOrg);
		//状态
		$('#stat_zdcwimpfromoa').combobox('loadData', data.allStat);
		$('#stat_zdcwimpfromoa').combobox('select', data.myStat);
		//录入人、审核人、批准人
		$('#inputter_zdcwimpfromoa').combobox('loadData', data.allInputter);
		$('#inputter_zdcwimpfromoa').combobox('select', data.myInputter);
		$('#checker_zdcwimpfromoa').combobox('loadData', data.allChecker);
		$('#checker_zdcwimpfromoa').combobox('select', data.myChecker);
		$('#approver_zdcwimpfromoa').combobox('loadData', data.allApprover);
		$('#approver_zdcwimpfromoa').combobox('select', data.myApprover);
		
	});
	*/
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
    	
    	var num = $('#num_zdcwimpfromoa').val();
    	var org = $('#org_zdcwimpfromoa').combobox('getText');
    	var billnum = $('#billnum_zdcwimpfromoa').val();
    	var stat = $('#stat_zdcwimpfromoa').combobox('getText');
    	var inputter = $('#inputter_zdcwimpfromoa').combobox('getText');
    	var inputtimebegin = $('#inputtimebegin_zdcwimpfromoa').datebox('getText');
    	var inputtimeend = $('#inputtimeend_zdcwimpfromoa').datebox('getText');
    	var checker = $('#checker_zdcwimpfromoa').combobox('getText');
    	var checktimebegin = $('#checktimebegin_zdcwimpfromoa').datebox('getText');
    	var checktimeend = $('#checktimeend_zdcwimpfromoa').datebox('getText');
    	var approver = $('#approver_zdcwimpfromoa').combobox('getText');
    	var approvetimebegin = $('#approvetimebegin_zdcwimpfromoa').datebox('getText');
    	var approvetimeend = $('#approvetimeend_zdcwimpfromoa').datebox('getText');
    	
    	var url = '../'+modulepath+'/getInfo.php';
    	var param = 'NUM='+num+'&ORG='+org+'&BILLNUM='+billnum+'&STAT='+stat
    	+'&INPUTTER='+inputter+'&INPUTTIMEBEGIN='+inputtimebegin+'&INPUTTIMEEND='+inputtimeend
    	+'&CHECKER='+checker+'&CHECKTIMEBEGIN='+checktimebegin+'&CHECKTIMEEND='+checktimeend
    	+'&APPROVER='+approver+'&APPROVETIMEBEGIN='+approvetimebegin+'&APPROVETIMEEND='+approvetimeend;
    	
    	$('#dg_zdcwimpfromoa').datagrid({
    		url: encodeURI(url+'?'+param)
    	});
    	$.messager.progress('close');
    	//$('#dg_zdcwimpfromoa').datagrid('reload');
    	
	}
	
	
}