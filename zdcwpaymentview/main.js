$(document).ready(function(){
	var url_getInfo = "";
	
	url_getInfo = '../'+modulepath+'/getInfo.php';
	$('#dg_zdcwpaymentview').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		nowrap:false,
		pageSize: 15,
		pageList: [15,30],
		border: true,
		striped: true,
		url: url_getInfo,
		columns: [[
			{field:'NUM',title:'系统单号',align:'center',width:30,
				formatter: function(value,row,index){
					return '<a href="javascript:void(0)" onclick="actView(\''+value+'\')">'+value+'</a>';
				}
			},
			{field:'ORG',title:'机构',align:'center',width:30},
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
});

