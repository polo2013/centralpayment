<script type="text/javascript" src="../zdcwpaymentview/main.js"></script>
<style type="text/css">
#search_tb_zdcwpaymentview
{
	margin-bottom:20px;	
}
#search_tb_zdcwpaymentview td
{
	padding-left:20px;
}
#search_fm_zdcwpaymentview
{
	padding:0px 0px;
}

</style>

<table id="dg_zdcwpaymentview"></table>

<div id="toolbar_zdcwpaymentview">
<form id="search_fm_zdcwpaymentview" method="post" style="padding:5px;height:auto">
<table id="search_tb_zdcwpaymentview">
	<tr>
		<td><label for="num_zdcwpaymentview">系统单号：</label></td>
		<td><input id="num_zdcwpaymentview" name="NUM" class="easyui-validatebox"></td>
		<td><label for="org_zdcwpaymentview">所属机构：</label></td>
		<td><input id="org_zdcwpaymentview" name="ORG" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
	</tr>
	<tr>
		<td><label for="billnum_zdcwpaymentview">付款汇总表编号：</label></td>
		<td><input id="billnum_zdcwpaymentview" name="BILLNUM" class="easyui-validatebox"></td>
		<td><label for="stat_zdcwpaymentview">状态：</label></td>
		<td><input id="stat_zdcwpaymentview" name="STAT" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
	</tr>
	<tr>
		<td><label for="inputter_zdcwpaymentview">录入人：</label></td>
		<td><input id="inputter_zdcwpaymentview" name="INPUTTER" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="inputtimebegin_zdcwpaymentview">录入时间：</label></td>
		<td><input id="inputtimebegin_zdcwpaymentview" name="INPUTTIMEBEGIN" class="easyui-datebox" data-options="formatter:myformatter_Begin,parser:myparser"></td>
		<td>到</td>
		<td><input id="inputtimeend_zdcwpaymentview" name="INPUTTIMEEND" class="easyui-datebox" data-options="formatter:myformatter_End,parser:myparser"></td>
	</tr>
	<tr>
		<td><label for="checker_zdcwpaymentview">审核人：</label></td>
		<td><input id="checker_zdcwpaymentview" name="CHECKER" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="checktimebegin_zdcwpaymentview">审核时间：</label></td>
		<td><input id="checktimebegin_zdcwpaymentview" name="CHECKTIMEBEGIN" class="easyui-datebox" data-options="formatter:myformatter_Begin,parser:myparser"></td>
		<td>到</td>
		<td><input id="checktimeend_zdcwpaymentview" name="CHECKTIMEEND" class="easyui-datebox" data-options="formatter:myformatter_End,parser:myparser"></td>
	</tr>
	<tr>
		<td><label for="approver_zdcwpaymentview">批准人：</label></td>
		<td><input id="approver_zdcwpaymentview" name="APPROVER" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'"></td>
		<td><label for="approvetimebegin_zdcwpaymentview">批准时间：</label></td>
		<td><input id="approvetimebegin_zdcwpaymentview" name="APPROVETIMEBEGIN" class="easyui-datebox" data-options="formatter:myformatter_Begin,parser:myparser"></td>
		<td>到</td>
		<td><input id="approvetimeend_zdcwpaymentview" name="APPROVETIMEEND" class="easyui-datebox" data-options="formatter:myformatter_End,parser:myparser"></td>
		<td width="100px"><a id="btn_search_zdcwpaymentview" href="javascript:void(0)" onClick="searchPayment();"></a></td>
	</tr>
</table>
</form>
</div>