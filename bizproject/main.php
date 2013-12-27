<script type="text/javascript">
$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var url_getInfo = "";
	
	ret = ajaxFuncAuthCheck(moduleno,'新增权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-add_bizproject').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'修改权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_bizproject').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'删除权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_bizproject').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});



	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_bizproject').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_bizproject',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'项目代码',align:'left',width:30},
			{field:'NAME',title:'项目名称',width:60},
			{field:'ORG',title:'所属机构',width:60},
			{field:'NOTE',title:'备注',align:'center',width:20},
			{field:'ORDERNO',title:'排序',align:'center',width:10}
		]]
	});

	$('#org_bizproject').combobox({
		onSelect: function(rec){
			var newCode = ajaxGetNewCode(moduleno, rec.value);
			$('#code_bizproject').val(newCode);
		}
	});
	
	
});

function newAct(){
	$('#dlg_bizproject').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_bizproject').form('clear');
	$('#name_bizproject').attr("readonly",false);
	$('#org_bizproject').combobox('readonly',false);
	$.getJSON("../public/php/getOrgForSelect.php", function(data){
		$('#org_bizproject').combobox('loadData', data.allorg);
		$('#org_bizproject').combobox('select', data.myorg);
	});
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
}
function editAct(){
	var row = $('#dg_bizproject').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_bizproject').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_bizproject').form('clear');
		$('#fm_bizproject').form('load',row);
		$('#name_bizproject').attr("readonly",true);
		$('#org_bizproject').combobox('readonly');
		$('#orderno_bizproject').focus();
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_bizproject').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_bizproject').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_bizproject').datagrid('reload');    // reload the user data
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
		$('#btn-remove_bizproject').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_bizproject').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_bizproject'), '项目名称', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_bizproject'), '排序号', '数字')){
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
	        	    	$('#dlg_bizproject').dialog('close');        // close the dialog
	    				$('#dg_bizproject').datagrid('reload');      // reload the user data
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

<table id="dg_bizproject"></table>

<div id="toolbar_bizproject">
	<a id="btn-add_bizproject" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_bizproject" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_bizproject" href="javascript:void(0)" onclick="removeAct()"></a>
</div>

<div id="dlg_bizproject" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_bizproject'">
	<div class="ftitle">内容</div>
	<form id="fm_bizproject" method="post">
		<div class="fitem">
			<label for="code_bizproject">项目代码：</label>
			<input id="code_bizproject" name="CODE" class="easyui-validatebox" readonly>
		</div>
		<div class="fitem">
			<label for="name_bizproject">项目名称：</label>
			<input id="name_bizproject" name="NAME" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="org_bizproject">所属机构：</label>
			<input id="org_bizproject" name="ORG" class="easyui-combobox" data-options="editable:false, panelHeight:'auto'">
		</div>
		<div class="fitem">
			<label for="orderno_bizproject">排序号：</label>
			<input id="orderno_bizproject" name="ORDERNO" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="note_bizproject">备注：</label>
			<textarea id="note_bizproject" name="NOTE" ></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_bizproject">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_bizproject').dialog('close')">取消</a>
</div>

