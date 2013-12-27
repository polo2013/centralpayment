<script type="text/javascript" src="../sysauth/main.js"></script>
<div id="cc_sysauth" class="easyui-layout" fit="true">
	<div data-options="region:'east',title:'授予对象',split:false,collapsible:false" style="width:300px;background:#eee;">
		<div class="ftitle">Information</div>
		<form id="fm_sysauth" method="post" style="padding:10px 25px;">
			<div>
				<label for="role_sysauth">角色：</label>
				<input id="role_sysauth" name="ROLE" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',required:true" >
			</div>
			<div style="margin:20px 0px;">
				<label for="org_sysauth">机构：</label>
				<input id="org_sysauth" name="ORG" class="easyui-combobox" data-options="readonly:true,hasDownArrow:false,required:true" >
			</div>
			<div style="text-align:right;margin:20px 0px">
				<a id="btn-save_sysauth" href="javascript:void(0)" class="easyui-linkbutton" onclick="saveAct()"></a>
			</div>
		</form>
    </div>
    <div data-options="region:'center',title:'权限列表'" style="padding:10px;background:#eee;">
		<div id="toolbar_sysauth" style="padding:5px;border:0px solid #ddd">
			<a id="btn-expandAll_sysauth" href="javascript:void(0)" onclick="expandAllAct()"></a>
			|
			<a id="btn-collapseAll_sysauth" href="javascript:void(0)" onclick="collapseAllAct()"></a>
			|
			<a id="btn-selectAll_sysauth" href="javascript:void(0)" onclick="selectAllAct()"></a>
			|
			<a id="btn-cancelAll_sysauth" href="javascript:void(0)" onclick="cancelAllAct()"></a>
		</div>
	    <ul id="tt_sysauth"></ul>
    </div>
</div>

