<script type="text/javascript" src="../zdcwimpfromoa/main.js"></script>
<style type="text/css">
#search_tb_zdcwimpfromoa
{
	margin-bottom:0px;	
}
#search_tb_zdcwimpfromoa td
{
	padding-left:20px;
}
#search_fm_zdcwimpfromoa
{
	padding:0px 0px;
}

</style>

<table id="dg_zdcwimpfromoa"></table>

<div id="toolbar_zdcwimpfromoa">
<form id="search_fm_zdcwimpfromoa" method="post" style="padding:5px;height:auto">
<table id="search_tb_zdcwimpfromoa">
	<tr>
		<td><label for="org_zdcwimpfromoa">组织机构：</label></td>
		<td><input id="org_zdcwimpfromoa" name="org" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',required:true"></td>
		<td><label for="flowtype_zdcwimpfromoa">流程类型：</label></td>
		<td><input id="flowtype_zdcwimpfromoa" name="flowtype" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',required:true"></td>
		<td><label for="flowstat_zdcwimpfromoa">流程状态：</label></td>
		<td><input id="flowstat_zdcwimpfromoa" name="flowstat" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',hasDownArrow:false,required:true"></td>
		<td valign="bottom">
			
		</td>
	</tr>
	<tr>
		<td><label for="beginner_zdcwimpfromoa">流程发起人：</label></td>
		<td><input id="beginner_zdcwimpfromoa" name="beginner" class="easyui-validatebox" ></td>
		<td><label for="begintime1_zdcwimpfromoa">流程发起时间：</label></td>
		<td><input id="begintime1_zdcwimpfromoa" name="begintime1" class="easyui-datebox" data-options="formatter:myformatter_Begin,parser:myparser"></td>
		<td>到</td>
		<td><input id="begintime2_zdcwimpfromoa" name="begintime2" class="easyui-datebox" data-options="formatter:myformatter_End,parser:myparser"></td>
		<td valign="bottom">
			<a id="btn_search_zdcwimpfromoa" href="javascript:void(0)" ></a>
			<a id="btn_genpayment_zdcwimpfromoa" href="javascript:void(0)" ></a>
		</td>
	</tr>
	<tr>
		<td valign="bottom" colspan="2">
			<a id="btn_selectall_zdcwimpfromoa" href="javascript:void(0)" onclick="selectall_impfromoa()"></a>
			<a id="btn_unselectall_zdcwimpfromoa" href="javascript:void(0)" onclick="unselectall_impfromoa()"></a>
			<a id="btn_setting_zdcwimpfromoa" href="javascript:void(0)" ></a>
			<a id="btn_DelImpInfo_zdcwimpfromoa" href="javascript:void(0)" ></a>
		</td>
		<td valign="bottom" colspan="5">
			<span id="searchinfo_zdcwimpfromoa"></span>
		</td>
	</tr>
</table>
</form>
</div>


<div id="dlg_setting_zdcwimpfromoa" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_setting_zdcwimpfromoa',shadow:false">
	<table id="dg_setting_zdcwimpfromoa"></table>
</div>
<div id="dlg-buttons_setting_zdcwimpfromoa">
	<a id="btn-save_setting_zdcwimpfromoa" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveSettingsAct()">保存</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_setting_zdcwimpfromoa').dialog('close')">取消</a>
</div>
<div id="tb_setting_zdcwimpfromoa">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendSettings()">新增</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeSettings()">删除</a>
</div>



<div id="dlg_DelImpInfo_zdcwimpfromoa" class="easyui-dialog" data-options="closed:true, modal:true, buttons:'#dlg-buttons_DelImpInfo_zdcwimpfromoa',shadow:false">
	<div style="color:red;font-size:20px;">&nbsp&nbsp&nbsp&nbsp本页面将删除已经导入过的报销流程信息，使得报销流程能够再次被导入，并生成付款汇总表，最终可能会重复付款，请务必确认后再操作！</div>
	<br/>
	<div>
		<form id="DelImpInfo_fm_zdcwimpfromoa" method="post">
		<table style="margin:auto">
			<tr>
				<td><label>OA流程类型ID：</label></td>
				<td><input id="DelImpInfo_flowid_zdcwimpfromoa" name="DelImpInfo_flowid" class="easyui-validatebox" data-options="required:true, missingMessage:'OA报销流程内部ID，如：87'"></td>
			</tr>
			<tr>
				<td><label>OA流程流水号：</label></td>
				<td><input id="DelImpInfo_flowrunid_zdcwimpfromoa" name="DelImpInfo_flowrunid" class="easyui-validatebox" data-options="required:true, missingMessage:'OA报销流程流水号，如：3355'"></td>
			</tr>
			<tr>
				<td><label>付款汇总表单号：</label></td>
				<td><input id="DelImpInfo_num_zdcwimpfromoa" name="DelImpInfo_num" class="easyui-validatebox" data-options="required:true, missingMessage:'由该流程所生成的付款汇总表的单号'"></td>
			</tr>
			<tr>
				<td><label>申请人：</label></td>
				<td><input id="DelImpInfo_applicant_zdcwimpfromoa" name="DelImpInfo_applicant" class="easyui-validatebox" data-options="required:true, missingMessage:'申请人'"></td>
			</tr>
			<tr>
				<td><label>金额：</label></td>
				<td><input id="DelImpInfo_amount_zdcwimpfromoa" name="DelImpInfo_amount" class="easyui-validatebox" data-options="required:true, missingMessage:'金额'"></td>
			</tr>
		</table>
		</form>
	</div>
	<br/>
</div>
<div id="dlg-buttons_DelImpInfo_zdcwimpfromoa">
	<a id="btn-save_DelImpInfo_zdcwimpfromoa" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="saveDelImpInfoAct()">确定</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_DelImpInfo_zdcwimpfromoa').dialog('close')">取消</a>
</div>