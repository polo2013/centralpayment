$(document).ready(function(){
	console.info(JSON.stringify(allAuth)+'ready');
	//检查权限
	var isdisable = true;
	var url_getInfo = "";

	if(arrSearch('新增权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-add_sysstat').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	if(arrSearch('修改权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_sysstat').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	if(arrSearch('删除权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_sysstat').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_sysstat').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_sysstat',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'代码',align:'left',width:20},
			{field:'NAME',title:'状态',width:30},
			{field:'FIREACT',title:'来源动作',width:30},
			{field:'FIRESTAT',title:'来源状态',width:30},
			{field:'ORDERNO',title:'排序',width:15}
		]]
	});
});

function newAct(){
	$('#dlg_sysstat').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_sysstat').form('clear');
	$('#name_sysstat').attr("readonly",false);
	$('#fireact_sysstat').attr("readonly",false);

	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	var newCode = ajaxGetNewCode(moduleno, '001');	
	$('#code_sysstat').val(newCode);
}
function editAct(){
	var row = $('#dg_sysstat').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_sysstat').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_sysstat').form('clear');
		$('#name_sysstat').attr("readonly",true);
		$('#fireact_sysstat').attr("readonly",true);
		$('#firestat_sysstat').focus();
		
		$('#fm_sysstat').form('load',row);
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_sysstat').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_sysstat').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_sysstat').datagrid('reload');    // reload the user data
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
		$('#btn-remove_sysstat').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysstat').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_sysstat'), '状态', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#fireact_sysstat'), '来源动作', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#firestat_sysstat'), '来源状态', '来源状态')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_sysstat'), '排序号', '数字')){
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
			        如果要debug，则直接将data打印出来即可。alert(data);
			*/
	        if (dataObj.success){
	        	art.dialog({
	        	    content: dataObj.message,
	        	    ok: function(){
	        	    	$('#dlg_sysstat').dialog('close');        // close the dialog
	    				$('#dg_sysstat').datagrid('reload');      // reload the user data
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

