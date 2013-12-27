<script type="text/javascript">
$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var url_getInfo = "";
	
	ret = ajaxFuncAuthCheck(moduleno,'新增权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-add_sysrole').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'修改权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_sysrole').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'删除权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_sysrole').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_sysrole').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_sysrole',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'角色代码',align:'left',width:30},
			{field:'NAME',title:'角色名称',width:60},
			{field:'ORG',title:'所属机构',width:60},
			{field:'NOTE',title:'备注',align:'center',width:20},
			{field:'ORDERNO',title:'排序',align:'center',width:10}
		]]
	});

	$('#org_sysrole').combobox({
		onSelect: function(rec){
			var newCode = ajaxGetNewCode(moduleno, rec.value);
			$('#code_sysrole').val(newCode);
		}
	});
	
	
});

function newAct(){
	$('#dlg_sysrole').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_sysrole').form('clear');
	$('#name_sysrole').attr("readonly",false);
	$('#org_sysrole').combobox('readonly',false);
	$.getJSON("../public/php/getOrgForSelect.php", function(data){
		//alert(JSON.stringify(data));
		$('#org_sysrole').combobox('loadData', data.allorg);
		$('#org_sysrole').combobox('select', data.myorg);
	});
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
}
function editAct(){
	var row = $('#dg_sysrole').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_sysrole').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_sysrole').form('clear');
		$('#fm_sysrole').form('load',row);
		$('#name_sysrole').attr("readonly",true);
		$('#org_sysrole').combobox('readonly');
		$('#orderno_sysrole').focus();
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_sysrole').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_sysrole').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_sysrole').datagrid('reload');    // reload the user data
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
		$('#btn-remove_sysrole').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysrole').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_sysrole'), '角色名称', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_sysrole'), '排序号', '数字')){
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
	        	    	$('#dlg_sysrole').dialog('close');        // close the dialog
	    				$('#dg_sysrole').datagrid('reload');      // reload the user data
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

</script>

<table id="dg_sysrole"></table>

<div id="toolbar_sysrole">
	<a id="btn-add_sysrole" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_sysrole" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_sysrole" href="javascript:void(0)" onclick="removeAct()"></a>
</div>

<div id="dlg_sysrole" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysrole'">
	<div class="ftitle">内容</div>
	<form id="fm_sysrole" method="post">
		<div class="fitem">
			<label for="code_sysrole">角色代码：</label>
			<input id="code_sysrole" name="CODE" class="easyui-validatebox" readonly>
		</div>
		<div class="fitem">
			<label for="name_sysrole">角色名称：</label>
			<input id="name_sysrole" name="NAME" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="org_sysrole">所属机构：</label>
			<input id="org_sysrole" name="ORG" class="easyui-combobox" data-options="editable:false, panelHeight:'auto'">
		</div>
		<div class="fitem">
			<label for="orderno_sysrole">排序号：</label>
			<input id="orderno_sysrole" name="ORDERNO" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="note_sysrole">备注：</label>
			<textarea id="note_sysrole" name="NOTE" ></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_sysrole">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysrole').dialog('close')">取消</a>
</div>

