$(document).ready(function(){
	//alert(JSON.stringify(allAuth));
	//检查权限
	var isdisable = true;
	var url_getInfo = "";
	
	if(arrSearch('新增权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-add_system').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	if(arrSearch('修改权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_system').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	if(arrSearch('删除权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_system').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});
	
	if(arrSearch('模块权限设置权',allAuth)){isdisable = false;}else{isdisable = true;}
	$('#btn-rights_system').linkbutton({
	    iconCls: 'icon-ok',
	    plain: true,
	    text: '模块权限设置',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_system').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_system',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'代码',align:'left',width:20},
			{field:'NAME',title:'名称',width:30},
			{field:'MENU',title:'菜单',width:30},
			{field:'URL',title:'路径',width:30},
			{field:'OBJ',title:'对象',width:30},
			{field:'NOTE',title:'备注',width:30},
			{field:'ORDERNO',title:'排序',width:15}
			
		]]
	});
	
	$('#dg_system_rights').datagrid({
		iconCls:'icon-edit',
		onClickRow:clickRightsRow,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#tb_system_rights',
		border: true,
		striped: true,
		columns: [[
			{field:'CODE',title:'权限编号',width:20,editor:{
				type:'combobox',
				options:{
					required:true,
					readonly:true,
					hasDownArrow:false
				}
			}},
			{field:'NAME',title:'权限名称',width:20,editor:{
				type:'validatebox',
				options:{
					required:true
				}
			}}
		]]
		
	});


});

function newAct(){
	$('#dlg_system').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_system').form('clear');
	$('#name_system').attr("readonly",false);
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	var newCode = ajaxGetNewCode(moduleno, '001');
	$('#code_system').val(newCode);
}
function editAct(){
	var row = $('#dg_system').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_system').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_system').form('clear');
		$('#name_system').attr("readonly",true);
		$('#menu_system').focus();
		$('#fm_system').form('load',row);
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		/*
		$.messager.show({
			msg: '<h4>请点击数据区，选中一行后执行操作！</h4>',
			showSpeed:100,
			timeout:2000,
			height:80,
			style:{right:'',top:document.body.scrollTop+document.documentElement.scrollTop,bottom:''}
		});
		*/
		$('#btn-edit_system').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_system').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							art.dialog({
				        	    content: result.message,
				        	    ok: function(){
				        	    	$('#dg_system').datagrid('reload');    // reload the user data
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
	}else{
		$('#btn-remove_system').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_system').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_system'), '模块', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#menu_system'), '菜单', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#url_system'), 'URL', '字母')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#obj_system'), '表对象', '字母和下划线')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_system'), '排序号', '数字')){
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
	        	    	$('#dlg_system').dialog('close');        // close the dialog
	    				$('#dg_system').datagrid('reload');      // reload the user data
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
/**********************模块权限**********************/
function showRights(){
	var row = $('#dg_system').datagrid('getSelected');
	if (row){
		$('#dlg_system_rights').dialog('open').dialog('setTitle','模块权限设置').dialog('center').dialog('move',{top:100});
		//alert(row.CODE);
		$('#dg_system_rights').datagrid({
			url: encodeURI('../'+modulepath+'/getRightsInfo.php?MODULENO=['+row.CODE+']'+row.NAME),
			title: '['+row.CODE+']'+row.NAME
		});
	}else{
		$('#btn-rights_system').grumble(missSelectMsg);
	}
}

var editRightsIndex = undefined;
function endEditingRights(){
    if (editRightsIndex == undefined){return true}
    if ($('#dg_system_rights').datagrid('validateRow', editRightsIndex)){
        $('#dg_system_rights').datagrid('endEdit', editRightsIndex);
        editRightsIndex = undefined;
        return true;
    } else {
        return false;
    }
}
function clickRightsRow(rowIndex, rowData){
    if (editRightsIndex != rowIndex){
        if (endEditingRights()){
            $('#dg_system_rights').datagrid('selectRow', rowIndex).datagrid('beginEdit', rowIndex);
            editRightsIndex = rowIndex;
        } else {
            $('#dg_system_rights').datagrid('selectRow', editRightsIndex);
        }
    }
}
function appendRights(){
    if (endEditingRights()){
    	var rows = $('#dg_system_rights').datagrid('getRows');
    	//alert(JSON.stringify(rows));
    	var newCode = "";
    	if(rows.length == 0){
    		var row = $('#dg_system').datagrid('getSelected');
    		newCode = row.CODE + '001';
    	}else{
    		newCode = ajaxGetNewCode('moduleRights',rows[rows.length-1].CODE);
    	}
    	editRightsIndex = rows.length;
        $('#dg_system_rights').datagrid('appendRow',{CODE:newCode,NAME:''});
        $('#dg_system_rights').datagrid('selectRow', editRightsIndex).datagrid('beginEdit', editRightsIndex);
        
    }
}
function removeRights(){
    if (editRightsIndex == undefined){
    	return false;
    }
    $('#dg_system_rights').datagrid('cancelEdit', editRightsIndex).datagrid('deleteRow', editRightsIndex);
    editRightsIndex = undefined;
}
function saveRightsAct(){
	if (endEditingRights()){
	art.dialog({
	    content: '确定要保存这些权限吗？',
	    ok: function(){
	    	var row_rights = $('#dg_system_rights').datagrid('getRows');
	    	var row_sys = $('#dg_system').datagrid('getSelected');
	    	$.post(
	    		'../'+modulepath+'/saveRights.php',
    			{
    			"MODULENO" : encodeURI('moduleRights'),
    	        "MODULEOBJ" : encodeURI("SYS_MODULES_RIGHTS"),
    	        "MODULETITLE" : encodeURI("系统模块权限"),
    	        "MODULES" : encodeURI('['+row_sys.CODE+']'+row_sys.NAME),
    			"RIGHTS": encodeURI(JSON.stringify(row_rights))
    			},
    			function(result){
    				//alert(JSON.stringify(result));
					if (result.success){
						art.dialog({
	    	        	    content: result.message,
	    	        	    ok: function(){
	    	        	    	//$('#dg_system_rights').datagrid('reload');    // reload the user data
	    	        	    	$('#dlg_system_rights').dialog('close'); 
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