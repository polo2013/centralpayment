<script type="text/javascript" src="../system/main.js"></script>

<table id="dg_system"></table>

<div id="toolbar_system">
	<a id="btn-add_system" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_system" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_system" href="javascript:void(0)" onclick="removeAct()"></a>
	|
	<a id="btn-rights_system" href="javascript:void(0)" onclick="showRights()"></a>
</div>

<div id="dlg_system" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_system'">
	<div class="ftitle">内容</div>
	<form id="fm_system" method="post">
		<div class="fitem">
			<label for="code_system">代码：</label>
			<input id="code_system" name="CODE" class="easyui-validatebox" readonly>
		</div>
		<div class="fitem">
			<label for="name_system">模块：</label>
			<input id="name_system" name="NAME" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="menu_system">菜单：</label>
			<input id="menu_system" name="MENU" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="url_system">路径：</label>
			<input id="url_system" name="URL" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="obj_system">表对象：</label>
			<input id="obj_system" name="OBJ" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="orderno_system">排序号：</label>
			<input id="orderno_system" name="ORDERNO" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="note_system">备注：</label>
			<textarea id="note_system" name="NOTE"></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_system">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_system').dialog('close')">取消</a>
</div>



<div id="dlg_system_rights" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_system_rights',shadow:false">
	<table id="dg_system_rights"></table>
</div>
<div id="dlg-buttons_system_rights">
	<a id="btn-save_system_rights" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveRightsAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_system_rights').dialog('close')">取消</a>
</div>
<div id="tb_system_rights">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendRights()">新增</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeRights()">删除</a>
</div>
