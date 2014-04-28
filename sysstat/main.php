<script type="text/javascript" src="../sysstat/main.js"></script>
<table id="dg_sysstat"></table>

<div id="toolbar_sysstat">
	<a id="btn-add_sysstat" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_sysstat" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_sysstat" href="javascript:void(0)" onclick="removeAct()"></a>
</div>

<div id="dlg_sysstat" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysstat'">
	<div class="ftitle">内容</div>
	<form id="fm_sysstat" method="post">
		<div class="fitem">
			<label for="code_sysstat">代码：</label>
			<input id="code_sysstat" name="CODE" class="easyui-validatebox" readonly>
		</div>
		<div class="fitem">
			<label for="name_sysstat">状态：</label>
			<input id="name_sysstat" name="NAME" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="fireact_sysstat">来源动作：</label>
			<input id="fireact_sysstat" name="FIREACT" class="easyui-validatebox" data-options="required:true, missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="firestat_sysstat">来源状态：</label>
			<input id="firestat_sysstat" name="FIRESTAT" class="easyui-validatebox">
		</div>
		<div class="fitem">
			<label for="orderno_sysstat">排序号：</label>
			<input id="orderno_sysstat" name="ORDERNO" class="easyui-validatebox">
		</div>
	</form>
</div>
<div id="dlg-buttons_sysstat">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysstat').dialog('close')">取消</a>
</div>

