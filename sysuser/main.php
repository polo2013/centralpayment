<script src="../sysuser/main.js"></script>
<table id="dg_sysuser"></table>

<div id="toolbar_sysuser">
	<a id="btn-add_sysuser" href="javascript:void(0)" onclick="newAct()"></a>
	|
	<a id="btn-edit_sysuser" href="javascript:void(0)" onclick="editAct()"></a>
	|
	<a id="btn-remove_sysuser" href="javascript:void(0)" onclick="removeAct()"></a>
	|
	<a id="btn-onoff_sysuser" href="javascript:void(0)" onclick="act('onoff')"></a>
	|
	<a id="btn-check_sysuser" href="javascript:void(0)" onclick="act('check')"></a>
	|
	<a id="btn-chpwd_sysuser" href="javascript:void(0)" onclick="act('chpwd')"></a>
	|
	<a id="btn-resetpwd_sysuser" href="javascript:void(0)" onclick="act('resetpwd')"></a>
</div>

<div id="dlg_sysuser" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysuser'">
	<div class="ftitle">内容</div>
	<form id="fm_sysuser" method="post">
		<div class="fitem">
			<label for="code_sysuser">用户名：</label>
			<input id="code_sysuser" name="CODE" class="easyui-validatebox" data-options="required:true,missingMessage:'必填项'" >
		</div>
		<div class="fitem">
			<label for="name_sysuser">姓名：</label>
			<input id="name_sysuser" name="NAME" class="easyui-validatebox" data-options="required:true,missingMessage:'必填项'" >
		</div>
		<div class="fitem">
			<label for="passwd_sysuser">密码：</label>
			<input id="passwd_sysuser" name="PASSWD" type="password" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="passwdcfm_sysuser">确认密码：</label>
			<input id="passwdcfm_sysuser" name="PASSWDCFM" type="password" class="easyui-validatebox"  validType="equals['#passwd_sysuser']" >
		</div>
		<div class="fitem">
			<label for="org_role_sysuser">机构和角色：</label>
			<a id="btn_org_role_sysuser" href="javascript:void(0)" onclick="set_org_role()"></a>
		</div>
		<div class="fitem" id="div_org_role_desc"></div>
		<div class="fitem">
			<input type="hidden" id="org_sysuser" name="ORG">
			<input type="hidden" id="role_sysuser" name="ROLE">
		</div>
		<div class="fitem">
			<label for="mobile_sysuser">手机号码：</label>
			<input id="mobile_sysuser" name="MOBILE" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="email_sysuser">电子邮件：</label>
			<input id="email_sysuser" name="EMAIL" class="easyui-validatebox" data-options="validType:'email'" >
		</div>
		<div class="fitem">
			<label for="bank_sysuser">银行：</label>
			<input id="bank_sysuser" name="BANK" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="account_sysuser">账号：</label>
			<input id="account_sysuser" name="ACCOUNT" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="stat_sysuser">状态：</label>
			<input id="stat_sysuser" name="STAT" class="easyui-combobox" data-options="required:true,editable:false,panelHeight:'auto',data:[{value:'禁用',text:'禁用'},{value:'启用',text:'启用'}]" >
		</div>
		<div class="fitem">
			<label for="checkstat_sysuser">复核状态：</label>
			<input id="checkstat_sysuser" name="CHECKSTAT" class="easyui-combobox" data-options="required:true,editable:false,panelHeight:'auto',data:[{value:'未复核',text:'未复核'},{value:'已复核',text:'已复核'}]" >
		</div>
		<div class="fitem">
			<label for="orderno_sysuser">排序号：</label>
			<input id="orderno_sysuser" name="ORDERNO" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="note_sysuser">备注：</label>
			<textarea id="note_sysuser" name="NOTE"  ></textarea>
		</div>

	</form>
</div>
<div id="dlg-buttons_sysuser">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysuser').dialog('close')">取消</a>
</div>



<div id="dlg_sysuser_chpwd" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysuser_chpwd'">
	<form id="fm_sysuser_chpwd" method="post">
		<div class="fitem">
			<label for="oldpasswd_sysuser">旧密码：</label>
			<input id="oldpasswd_sysuser" name="OLDPASSWD" type="password" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="newpasswd_sysuser">新密码：</label>
			<input id="newpasswd_sysuser" name="NEWPASSWD" type="password" class="easyui-validatebox" >
		</div>
		<div class="fitem">
			<label for="newpasswdcfm_sysuser">确认新密码：</label>
			<input id="newpasswdcfm_sysuser" name="NEWPASSWDCFM" type="password" class="easyui-validatebox"  validType="equals['#newpasswd_sysuser']" >
		</div>
	</form>
</div>
<div id="dlg-buttons_sysuser_chpwd">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="chpwdAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysuser_chpwd').dialog('close')">取消</a>
</div>


<div id="dlg_sysuser_set_org_role" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_sysuser_set_org_role'">
	<div class="ftitle">用户可以兼职，并且在每个机构中可以担任多个角色。</div>
	<form id="fm_sysuser_set_org_role" method="post">
	<div class="ftitle">默认机构和角色</div>
		<div class="fitem">
			<label for="org_sysuser_one">机构1：</label>
			<input id="org_sysuser_one" name="ORG_ONE" class="easyui-combobox" data-options="editable:false, panelHeight:'auto', required:true,missingMessage:'必填项'">
		</div>
		<div class="fitem">
			<label for="role_sysuser_one">角色1：</label>
			<input id="role_sysuser_one" name="ROLE_ONE" class="easyui-combobox" data-options="editable:false, panelHeight:'auto', multiple:true, required:true,missingMessage:'必填项'">
		</div>
	<div class="ftitle">兼职机构和角色</div>
		<div class="fitem">
			<label for="org_sysuser_two">机构2：</label>
			<input id="org_sysuser_two" name="ORG_TWO" class="easyui-combobox" data-options="editable:false, panelHeight:'auto'">
		</div>
		<div class="fitem">
			<label for="role_sysuser_two">角色2：</label>
			<input id="role_sysuser_two" name="ROLE_TWO" class="easyui-combobox" data-options="editable:false, panelHeight:'auto', multiple:true">
		</div>
	</form>
</div>
<div id="dlg-buttons_sysuser_set_org_role">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="set_org_role_act()">确定</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sysuser_set_org_role').dialog('close')">取消</a>
</div>
