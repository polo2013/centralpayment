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
		</td>
		<td valign="bottom" colspan="5">
			<span id="searchinfo_zdcwimpfromoa"></span>
		</td>
	</tr>
</table>
</form>
</div>