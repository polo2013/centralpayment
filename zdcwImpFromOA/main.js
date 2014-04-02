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
			{field:'PAYMENT',title:'付款事由',halign:'center',align:'center',width:250},
			{field:'CURRENCY',title:'币别',halign:'center',align:'center',width:60},
			{field:'TOTALAMT',title:'金额',halign:'center',align:'right',width:100},
			{field:'PAYEE',title:'收款人',halign:'center',align:'center',width:100},
			{field:'BANK',title:'银行',halign:'center',align:'center',width:100},
			{field:'ACCOUNT',title:'账号',halign:'center',align:'center',width:180},
			{field:'NOTE',title:'备注',halign:'center',align:'center',width:200,
				styler: function(value,row,index){
					if (row.FLAG == "否"){
						return 'color:red;';
					}
				}
			},
			{field:'FLAG',title:'可导',halign:'center',align:'center',width:40,
				styler: function(value,row,index){
					if (value == "否"){
						return 'color:red;';
					}
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
			//$.messager.progress('close');
			jQuery('body').hideLoading();
			art.dialog({
				content: '连接 OA 服务器通讯失败！',
				ok: true
        	});
		},
		onLoadSuccess:function(data){
			//alert(JSON.stringify(data));
			//$.messager.progress('close');
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
		onClickRow: function(rowIndex, rowData){
			if (rowData.FLAG == "否") {
				$('#dg_zdcwimpfromoa').datagrid('unselectRow', rowIndex);
				$('#dg_zdcwimpfromoa').datagrid('uncheckRow', rowIndex);
			}
        },
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

			$('#btn_setting_zdcwimpfromoa').linkbutton({
			    plain: false,
			    text: '设置',
			    disabled: false
			});
			$("#btn_setting_zdcwimpfromoa").unbind();
			$('#btn_setting_zdcwimpfromoa').bind('click', settingImp);
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
			        	    	genPaymentAction(org, flowtype, 'merge', rows);
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
			        	    	genPaymentAction(org, flowtype, 'nomerge', rows);
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
function genPaymentAction(org, flowtype, is_merge, rows){
	$.post(
		'../'+modulepath+'/genBill.php',
		{
			ORG      : org,
			FLOWTYPE : flowtype,
			MERGE    : is_merge,
			ROWS     : JSON.stringify(rows),
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

function settingImp(){}