$(document).ready(function(){
	//检查权限
	var isdisable = true;
	var url_getInfo = "";

	if(arrSearch('新增权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-add_sysexcept').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});
	if(arrSearch('修改权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_sysexcept').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});
	if(arrSearch('删除权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_sysexcept').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_sysexcept').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_sysexcept',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'ID',title:'ID',align:'left',width:20},
			{field:'ORG',title:'申请部门代码',align:'left',width:20},
			{field:'ORG_NAME',title:'申请部门名称',width:30},
			{field:'OTYPE',title:'例外类型',width:30},
			{field:'ANOTHER',title:'代码',width:30},
			{field:'ANOTHER_NAME',title:'名称',width:15}
		]]
	});
	
	
	$('#otype_sysexcept').combobox({
		onSelect: function(rec){
			if(rec.value == "org"){
				$.getJSON("../public/php/getOrgForSelect.php", function(data){
					//alert(JSON.stringify(data));
					$('#another_sysexcept').combobox('loadData', data.allorg);
					$('#another_sysexcept').combobox('select', data.myorg);
				});
			}else{
				$.getJSON("../public/php/getUserForSelect.php", function(data){
					//alert(JSON.stringify(data));
					$('#another_sysexcept').combobox('loadData', data.all);
					$('#another_sysexcept').combobox('select', data.my);
				});
			}
		}
	});
});

function newAct(){
	$('#dlg_sysexcept').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_sysexcept').form('clear');
	$('#org_sysexcept').combobox('readonly',false);
	$.getJSON("../public/php/getOrgForSelect.php", function(data){
		//alert(JSON.stringify(data));
		$('#org_sysexcept').combobox('loadData', data.allorg);
		$('#org_sysexcept').combobox('select', data.myorg);
	});
	
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;

}
function editAct(){
	var row = $('#dg_sysexcept').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_sysexcept').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_sysexcept').form('clear');
		
		//$('#fm_sysexcept').form('load',row);
		
		$.getJSON("../public/php/getOrgForSelect.php", function(data){
			//alert(JSON.stringify(data));
			$('#org_sysexcept').combobox('loadData', data.allorg);
			
		});
		
		
		submit_url = '../'+modulepath+'/edit.php?id='+row.ID+'&MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_sysexcept').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_sysexcept').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{ID:row.ID,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_sysexcept').datagrid('reload');    // reload the user data
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
		$('#btn-remove_sysexcept').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysexcept').form('submit',{
		url: submit_url,
		onSubmit: function(){
				return true;
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
	        	    	$('#dlg_sysexcept').dialog('close');        // close the dialog
	    				$('#dg_sysexcept').datagrid('reload');      // reload the user data
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

