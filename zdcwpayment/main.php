<script type="text/javascript" src="../zdcwpayment/main.js"></script>
<script type="text/javascript" src="../zdcwpayment/main_new.js"></script>
<script type="text/javascript" src="../zdcwpayment/main_view.js"></script>

<script type="text/javascript">

</script>

<style type="text/css">
#tb_zdcwpayment
{
	margin-bottom:20px;	
}
#tb_zdcwpayment td
{
	padding-left:20px;
}
#fm_zdcwpayment
{
	padding:0px 0px;
}

</style>
<form id="fm_zdcwpayment" method="post">
<table id="tb_zdcwpayment">
	<tr>
		<td><label for="num_zdcwpayment">系统单号：</label></td>
		<td><input id="num_zdcwpayment" name="NUM" class="easyui-validatebox" readonly></td>
		<td><label for="org_zdcwpayment">所属机构：</label></td>
		<td><input id="org_zdcwpayment" name="ORG" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',required:true"></td>
		<td><label for="billnum_zdcwpayment">付款汇总表编号：</label></td>
		<td><input id="billnum_zdcwpayment" name="BILLNUM" class="easyui-validatebox" data-options="required:true,missingMessage:'必填项'"></td>
	</tr>
	<tr>
		<td><label for="inputter_zdcwpayment">录入人：</label></td>
		<td><input id="inputter_zdcwpayment" name="INPUTTER" class="easyui-validatebox" readonly></td>
		<td><label for="checker_zdcwpayment">审核人：</label></td>
		<td><input id="checker_zdcwpayment" name="CHECKER" class="easyui-validatebox" readonly></td>
		<td><label for="approver_zdcwpayment">批准人：</label></td>
		<td><input id="approver_zdcwpayment" name="APPROVER" class="easyui-validatebox" readonly></td>
	</tr>
	<tr>
		<td><label for="inputtime_zdcwpayment">录入时间：</label></td>
		<td><input id="inputtime_zdcwpayment" name="INPUTTIME" class="easyui-validatebox" readonly></td>
		<td><label for="checktime_zdcwpayment">审核时间：</label></td>
		<td><input id="checktime_zdcwpayment" name="CHECKTIME" class="easyui-validatebox" readonly></td>
		<td><label for="approvetime_zdcwpayment">批准时间：</label></td>
		<td><input id="approvetime_zdcwpayment" name="APPROVETIME" class="easyui-validatebox" readonly></td>
	</tr>
	<tr>
		<td><label for="note_zdcwpayment">备注：</label></td>
		<td><input id="note_zdcwpayment" name="NOTE" class="easyui-validatebox"></td>
	
		<td><label for="stat_zdcwpayment">状态：</label></td>
		<td><input id="stat_zdcwpayment" name="STAT" class="easyui-validatebox" readonly></td>
		<td><label for="operation_zdcwpayment">可选操作：</label></td>
		<td><input id="operation_zdcwpayment" name="OPERATION" class="easyui-combobox" data-options="required:true,editable:false,panelHeight:'auto'"></td>
		<td rowspan="4" valign="bottom">
			<a id="btn_save_zdcwpayment" href="javascript:void(0)""></a>
		</td>
	</tr>
</table>

<table id="dg_zdcwpayment"></table>

<div id="tb_dg_zdcwpayment">
	<a id="btn1_zdcwpayment" href="javascript:void(0)"></a>
	<a id="btn2_zdcwpayment" href="javascript:void(0)"></a>
	<a id="btn3_zdcwpayment" href="javascript:void(0)"></a>
</div>

</form>



