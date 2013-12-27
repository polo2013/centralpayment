var authArr = new Array();
$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var url_getInfo = "";
	
	ret = ajaxFuncAuthCheck(moduleno,'新增权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-add_bizpayee').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'修改权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_bizpayee').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'删除权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_bizpayee').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno+'&AUTH=1';

	ret = ajaxFuncAuthCheck(moduleno,'复核权');
	if(ret == true){isdisable = false; authArr[1] = true;}else{isdisable = true; authArr[1] = false;}
	$('#btn-check_bizpayee').linkbutton({
	    iconCls: 'icon-tip',
	    plain: true,
	    text: '复核收款人信息',
	    disabled: isdisable
	});
	
	$('#dg_bizpayee').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		nowrap:false,
		singleSelect: true,
		toolbar: '#toolbar_bizpayee',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'代码',align:'left',width:100},
			{field:'NAME',title:'名称',width:150},
			{field:'ORG',title:'所属机构',width:150},
			{field:'BANK',title:'银行',align:'center',width:100},
			{field:'ACCOUNT',title:'账号',align:'center',width:150},
			{field:'MOBILE',title:'手机号码',align:'center',width:100},
			{field:'EMAIL',title:'电子邮件',align:'center',width:100},
			{field:'CONTACTS',title:'联系人',align:'center',width:80},
			{field:'CHECKSTAT',title:'复核状态',align:'center',width:80},
			{field:'NOTE',title:'备注',align:'center',width:50},
			{field:'ORDERNO',title:'排序',align:'center',width:30},
		]]
	});
	$('#org_bizpayee').combobox({
		onSelect: function(rec){
			var newCode = ajaxGetNewCode(moduleno, rec.value);
			$('#code_bizpayee').val(newCode);
		}
	});
	
});

function newAct(){
	$('#dlg_bizpayee').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_bizpayee').form('clear');
	$('#name_bizpayee').attr("readonly",false);
	$('#org_bizpayee').combobox('readonly',false);
	$('#checkstat_bizpayee').combobox('select', '未复核');
	if(authArr[1] == true){$('#checkstat_bizpayee').combobox('readonly',false);}else{$('#checkstat_bizpayee').combobox('readonly',true);}
	$.getJSON("../public/php/getOrgForSelect.php", function(data){
		$('#org_bizpayee').combobox('loadData', data.allorg);
		$('#org_bizpayee').combobox('select', data.myorg);
	});
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
}
function editAct(){
	var row = $('#dg_bizpayee').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_bizpayee').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_bizpayee').form('clear');
		$('#fm_bizpayee').form('load',row);
		$('#name_bizpayee').attr("readonly",true);
		$('#org_bizpayee').combobox('readonly');
		$('#bank_bizpayee').focus();
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_bizpayee').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_bizpayee').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_bizpayee').datagrid('reload');    // reload the user data
						} else {
							art.dialog({
				        	    content: result.message,
				        	    ok: true
				        	});
						}
					},
					'json'
				);
			}
		});
	}else{
		$('#btn-remove_bizpayee').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_bizpayee').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_bizpayee'), '名称', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#bank_bizpayee'), '银行', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#account_bizpayee'), '账号', '数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#mobile_bizpayee'), '手机号', '手机号')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#contacts_bizpayee'), '联系人', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_bizpayee'), '排序号', '数字')){
					$.messager.progress('close');
					return false;
				}
				
				return true;
			}else{
				$.messager.progress('close');
				return false;
			}
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
	        	    	$('#dlg_bizpayee').dialog('close');        // close the dialog
	    				$('#dg_bizpayee').datagrid('reload');      // reload the user data
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
}

function act(flag){
	var row = $('#dg_bizpayee').datagrid('getSelected');
	if (row){
		if(flag == 'check' && row.CHECKSTAT == "已复核"){
			art.dialog({content:'该收款人已经是复核状态，不需要复核！', ok: true});
		}else{
			var act = '';
			var cfmsg = '';
			if(flag == 'check'){
				if(row.CHECKSTAT != "已复核"){act = '已复核'; cfmsg = '复核';}
			}
			$.messager.confirm('Confirm','您确定要&nbsp<font color="red">'+cfmsg+'</font>&nbsp该收款人资料吗？',function(r){
				if (r){
					$.post(
						'../'+modulepath+'/act.php',
						{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle,FLAG:flag,ACT:act,CFMSG:cfmsg},
						function(result){
							//alert(JSON.stringify(result));
							if (result.success){
								art.dialog({
					        	    content: result.message,
					        	    ok: function(){
					        	    	$('#dg_bizpayee').datagrid('reload');    // reload the user data
					        	    }
					        	});
							} else {
								art.dialog({
					        	    content: result.message,
					        	    ok: true
					        	});
							}
						},
						'json'
					);
				}
			});
		}
	}else{
		if(flag == 'check')
			$('#btn-check_bizpayee').grumble(missSelectMsg);
	}
}