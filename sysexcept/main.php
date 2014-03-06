<script type="text/javascript" src="../sysexcept/main.js"></script>
<table id="dg_sysexcept"></table>

<div id="toolbar_sysexcept">
	<a id="btn-add_sysexcept" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_sysexcept" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_sysexcept" href="javascript:void(0)" onclick="removeAct()"></a>
</div>

<div id="dlg_sysexcept" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysexcept'">
	<div class="ftitle">内容</div>
	<form id="fm_sysexcept" method="post">
		<div class="fitem">
			<label for="org_sysexcept">申请组织：</label>
			<input id="org_sysexcept" name="org" class="easyui-combobox" data-options="required:true, editable:false">
		</div>
		<div class="fitem">
			<label for="otype_sysexcept">例外类型：</label>
			<input id="otype_sysexcept" name="otype" class="easyui-combobox" data-options="required:true, editable:false, panelHeight:'auto', data: [{text:'用户', value:'people'},{text:'部门', value:'org'}]">
		</div>
		<div class="fitem">
			<label for="another_sysexcept">用户或部门：</label>
			<input id="another_sysexcept" name="another" class="easyui-combobox" data-options="required:true, editable:false">
		</div>
	</form>
</div>
<div id="dlg-buttons_sysexcept">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysexcept').dialog('close')">取消</a>
</div>

