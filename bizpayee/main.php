<script src="../bizpayee/main.js"></script>

<table id="dg_bizpayee"></table>

<div id="toolbar_bizpayee">
	<a id="btn-add_bizpayee" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_bizpayee" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_bizpayee" href="javascript:void(0)" onclick="removeAct()"></a>
	|
	<a id="btn-check_bizpayee" href="javascript:void(0)" onclick="act('check')"></a>
</div>

<div id="dlg_bizpayee" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_bizpayee'">
	<div class="ftitle">内容</div>
	<form id="fm_bizpayee" method="post">
		<div class="fitem">
			<label for="code_bizpayee">编码：</label>
			<input id="code_bizpayee" name="CODE" class="easyui-validatebox" readonly >
		</div>
		<div class="fitem">
			<label for="name_bizpayee">名称：</label>
			<input id="name_bizpayee" name="NAME" class="easyui-validatebox" data-options="required:true,missingMessage:'必填项'" >
		</div>
		<div class="fitem">
			<label for="org_bizpayee">所属机构：</label>
			<input id="org_bizpayee" name="ORG" class="easyui-combobox"  data-options="editable:false, panelHeight:'auto'" >
		</div>
		<div class="fitem">
			<label for="bank_bizpayee">银行：</label>
			<input id="bank_bizpayee" name="BANK" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="account_bizpayee">账号：</label>
			<input id="account_bizpayee" name="ACCOUNT" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="mobile_bizpayee">手机号码：</label>
			<input id="mobile_bizpayee" name="MOBILE" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="email_bizpayee">电子邮件：</label>
			<input id="email_bizpayee" name="EMAIL" class="easyui-validatebox" data-options="validType:'email'" >
		</div>
		<div class="fitem">
			<label for="contacts_bizpayee">联系人：</label>
			<input id="contacts_bizpayee" name="CONTACTS" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="checkstat_bizpayee">复核状态：</label>
			<input id="checkstat_bizpayee" name="CHECKSTAT" class="easyui-combobox" data-options="required:true,editable:false,panelHeight:'auto',data:[{value:'未复核',text:'未复核'},{value:'已复核',text:'已复核'}]" >
		</div>
		<div class="fitem">
			<label for="orderno_bizpayee">排序号：</label>
			<input id="orderno_bizpayee" name="ORDERNO" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="note_bizpayee">备注：</label>
			<textarea id="note_bizpayee" name="NOTE" ></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_bizpayee">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_bizpayee').dialog('close')">取消</a>
</div>

