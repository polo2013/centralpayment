<script type="text/javascript" src="../zdcwimpfromoa/main.js"></script>
<style type="text/css">
#search_tb_zdcwimpfromoa
{
	margin-bottom:20px;	
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
		<td><label for="org_zdcwimpfromoa">OA组织机构：</label></td>
		<td><input id="org_zdcwimpfromoa" name="org" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="flowtype_zdcwimpfromoa">OA流程类型：</label></td>
		<td><input id="flowtype_zdcwimpfromoa" name="flowtype" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="flowstat_zdcwimpfromoa">OA流程状态：</label></td>
		<td><input id="flowstat_zdcwimpfromoa" name="flowstat" class="easyui-validatebox"></td>
		<td></td>
	</tr>
	<tr>
		<td><label for="beginner_zdcwimpfromoa">OA流程发起人：</label></td>
		<td><input id="beginner_zdcwimpfromoa" name="beginner" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="begintime_zdcwimpfromoa">时间段：</label></td>
		<td><input id="begintime_zdcwimpfromoa" name="begintime" class="easyui-datebox" data-options="formatter:myformatter_Begin,parser:myparser"></td>
		<td>到</td>
		<td><input id="endtime_zdcwimpfromoa" name="endtime" class="easyui-datebox" data-options="formatter:myformatter_End,parser:myparser"></td>
		<td valign="bottom">
			<a id="btn_search_zdcwimpfromoa" href="javascript:void(0)" onClick="searchPayment();"></a>
			<a id="btn_genpayment_zdcwimpfromoa" href="javascript:void(0)" onClick="genPayment();"></a>
		</td>
	</tr>
</table>
</form>
</div>