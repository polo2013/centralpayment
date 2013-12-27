<script type="text/javascript">
$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var url_getInfo = "";
	
	ret = ajaxFuncAuthCheck(moduleno,'新增权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-add_sysorg').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'修改权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-edit_sysorg').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'删除权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-remove_sysorg').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});



	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno;
	$('#dg_sysorg').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		singleSelect: true,
		toolbar: '#toolbar_sysorg',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'机构代码',align:'left',width:30},
			{field:'NAME',title:'机构名称',width:60},
			{field:'PARENT',title:'上级机构',width:60},
			{field:'TEL',title:'电话',align:'center',width:40},
			{field:'FAX',title:'传真',align:'center',width:40},
			{field:'ADDR',title:'地址',width:60},
			{field:'ZIPCODE',title:'邮编',width:15},
			{field:'NOTE',title:'备注',align:'center',width:20},
			{field:'ORDERNO',title:'排序',align:'center',width:10}
		]]
	});

	$('#parent_sysorg').combobox({
		onSelect: function(rec){
			var newCode = ajaxGetNewCode(moduleno, rec.value);
			$('#code_sysorg').val(newCode);
		}
	});
	
	
});

function newAct(){
	$('#dlg_sysorg').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_sysorg').form('clear');
	$('#name_sysorg').attr("readonly",false);
	$('#parent_sysorg').combobox('readonly',false);
	//var newCode = ajaxGetNewCode(moduleno);
	//$('#code_sysorg').val(newCode);
	$.getJSON("../public/php/getOrgForSelect.php", function(data){
		//alert(JSON.stringify(data));
		$('#parent_sysorg').combobox('loadData', data.allorg);
		$('#parent_sysorg').combobox('select', data.myorg);
	});
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
}
function editAct(){
	var row = $('#dg_sysorg').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_sysorg').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_sysorg').form('clear');
		$('#fm_sysorg').form('load',row);
		$('#parent_sysorg').combobox('readonly');
		$('#name_sysorg').attr("readonly",true);
		$('#tel_sysorg').focus();
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		//art.dialog({content: missSelectMsg, ok: true});
		$('#btn-edit_sysorg').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_sysorg').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_sysorg').datagrid('reload');    // reload the user data
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
		$('#btn-remove_sysorg').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysorg').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#name_sysorg'), '机构名称', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#tel_sysorg'), '电话', '数字、中划线')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#fax_sysorg'), '传真', '数字、中划线')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#addr_sysorg'), '地址', '汉字、字母、数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#zipcode_sysorg'), '邮编', '数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_sysorg'), '排序号', '数字')){
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
	        	    	$('#dlg_sysorg').dialog('close');        // close the dialog
	    				$('#dg_sysorg').datagrid('reload');      // reload the user data
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

<table id="dg_sysorg"></table>

<div id="toolbar_sysorg">
	<a id="btn-add_sysorg" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_sysorg" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_sysorg" href="javascript:void(0)" onclick="removeAct()"></a>
</div>

<div id="dlg_sysorg" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysorg'">
	<div class="ftitle">内容</div>
	<form id="fm_sysorg" method="post">
		<div class="fitem">
			<label for="code_sysorg">机构代码：</label>
			<input id="code_sysorg" name="CODE" class="easyui-validatebox" readonly>
		</div>
		<div class="fitem">
			<label for="name_sysorg">机构名称：</label>
			<input id="name_sysorg" name="NAME" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="parent_sysorg">上级机构：</label>
			<input id="parent_sysorg" name="PARENT" class="easyui-combobox" data-options="editable:false, panelHeight:'auto'">
		</div>
		<div class="fitem">
			<label for="tel_sysorg">电话：</label>
			<input id="tel_sysorg" name="TEL" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="fax_sysorg">传真：</label>
			<input id="fax_sysorg" name="FAX" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="addr_sysorg">地址：</label>
			<input id="addr_sysorg" name="ADDR" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="zipcode_sysorg">邮编：</label>
			<input id="zipcode_sysorg" name="ZIPCODE" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="orderno_sysorg">排序号：</label>
			<input id="orderno_sysorg" name="ORDERNO" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="note_sysorg">备注：</label>
			<textarea id="note_sysorg" name="NOTE" ></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_sysorg">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysorg').dialog('close')">取消</a>
</div>

