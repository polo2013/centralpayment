var authArr = new Array();
$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var url_getInfo = "";
	
	ret = ajaxFuncAuthCheck(moduleno,'新增权');
	if(ret == true){isdisable = false; authArr[1] = true;}else{isdisable = true; authArr[1] = false;}
	$('#btn-add_sysuser').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '新增',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'修改权');
	if(ret == true){isdisable = false; authArr[2] = true;}else{isdisable = true; authArr[2] = false;}
	$('#btn-edit_sysuser').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改',
	    disabled: isdisable
	});

	ret = ajaxFuncAuthCheck(moduleno,'删除权');
	if(ret == true){isdisable = false; authArr[3] = true;}else{isdisable = true; authArr[3] = false;}
	$('#btn-remove_sysuser').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '删除',
	    disabled: isdisable
	});

	url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno+'&AUTH=1';
	authArr[4] = true;

	ret = ajaxFuncAuthCheck(moduleno,'查看所属机构用户权');
	if(ret == true){
		url_getInfo = '../'+modulepath+'/getInfo.php?MODULENO='+moduleno+'&AUTH=2';
		authArr[5] = true;
	}else{
		authArr[5] = false;
	}

	ret = ajaxFuncAuthCheck(moduleno,'启用禁用权');
	if(ret == true){isdisable = false; authArr[6] = true;}else{isdisable = true; authArr[6] = false;}
	$('#btn-onoff_sysuser').linkbutton({
		iconCls: 'icon-ok',
		plain: true,
		text: '禁用或启用用户',
		disabled: isdisable
	});
	
	ret = ajaxFuncAuthCheck(moduleno,'复核权');
	if(ret == true){isdisable = false; authArr[7] = true;}else{isdisable = true; authArr[7] = false;}
	$('#btn-check_sysuser').linkbutton({
	    iconCls: 'icon-tip',
	    plain: true,
	    text: '复核用户信息',
	    disabled: isdisable
	});
	
	ret = ajaxFuncAuthCheck(moduleno,'修改密码权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-chpwd_sysuser').linkbutton({
	    iconCls: 'icon-edit',
	    plain: true,
	    text: '修改密码',
	    disabled: isdisable
	});
	
	ret = ajaxFuncAuthCheck(moduleno,'重置用户权');
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-resetpwd_sysuser').linkbutton({
	    iconCls: 'icon-sum',
	    plain: true,
	    text: '重置用户',
	    disabled: isdisable
	});
	
	$('#dg_sysuser').datagrid({
		title: moduletitle,
		pagination: true,
		rownumbers: true,
		fitColumns: true,
		nowrap:false,
		singleSelect: true,
		toolbar: '#toolbar_sysuser',
		pageSize: 15,
		pageList: [15,20,40],
		border: true,
		striped: true,
		url: url_getInfo,
		onSelect: rowPublicAct,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'CODE',title:'用户名',align:'left',width:100},
			{field:'NAME',title:'姓名',width:80},
			{field:'ROLE',title:'角色',width:150},
			{field:'ORG',title:'所属机构',width:150},
			{field:'MOBILE',title:'手机号码',align:'center',width:100},
			{field:'EMAIL',title:'电子邮件',align:'center',width:100},
			{field:'BANK',title:'银行',align:'center',width:100},
			{field:'ACCOUNT',title:'账号',align:'center',width:150},
			{field:'STAT',title:'状态',align:'center',width:50},
			{field:'CHECKSTAT',title:'复核状态',align:'center',width:80},
			{field:'NOTE',title:'备注',align:'center',width:50},
			{field:'ORDERNO',title:'排序',align:'center',width:30},
		]]
	});
	$('#role_sysuser').combobox({
		onSelect: function(rec){
			$.getJSON("../public/php/getOneChangeAnother.php",{who2who:"role2org",oneValue:rec.value}, function(data){
				$('#org_sysuser').val(data.anotherValue);
			});
		}
	});
	
});

function newAct(){
	$('#dlg_sysuser').dialog('open').dialog('setTitle','新增').dialog('center');
	$('#fm_sysuser').form('clear');
	
	$('#code_sysuser').attr("readonly",false);  //去除code的readonly属性
	$('#name_sysuser').attr("readonly",false);
	$('#passwd_sysuser').attr("disabled",false);   //enable密码
	$('#passwdcfm_sysuser').attr("disabled",false);   //enable确认密码
	$.getJSON("../public/php/getRoleForSelect.php", function(data){
		$('#role_sysuser').combobox('loadData', data.allrole);
		$('#role_sysuser').combobox('select', data.myrole);
	});
	$('#stat_sysuser').combobox('select', '禁用');
	$('#checkstat_sysuser').combobox('select', '未复核');
	if(authArr[6] == true){$('#stat_sysuser').combobox('readonly',false);}else{$('#stat_sysuser').combobox('readonly',true);}
	if(authArr[7] == true){$('#checkstat_sysuser').combobox('readonly',false);}else{$('#checkstat_sysuser').combobox('readonly',true);}
	
	
	submit_url = '../'+modulepath+'/new.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
}
function editAct(){
	var row = $('#dg_sysuser').datagrid('getSelected');
	//alert(JSON.stringify(row));
	if (row){
		$('#dlg_sysuser').dialog('open').dialog('setTitle','修改').dialog('center');
		$('#fm_sysuser').form('clear');
		
		$('#code_sysuser').attr("readonly",true);   //将code设置为readonly
		$('#name_sysuser').attr("readonly",true);
		$('#passwd_sysuser').attr("disabled",true);   //将密码设置为disabled
		$('#passwdcfm_sysuser').attr("disabled",true);   //将确认密码设置为disabled
		$('#code_sysuser').blur();
		$.getJSON("../public/php/getRoleForSelect.php", function(data){
			$('#role_sysuser').combobox('loadData', data.allrole);
		});
		$('#fm_sysuser').form('load',row);
		submit_url = '../'+modulepath+'/edit.php?MODULENO='+moduleno+'&MODULEOBJ='+moduleobj+'&MODULETITLE='+moduletitle;
	}else{
		$('#btn-edit_sysuser').grumble(missSelectMsg);
	}
}
function removeAct(){
	var row = $('#dg_sysuser').datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirm','你确定要删除这一条记录?',function(r){
			if (r){
				$.post(
					'../'+modulepath+'/remove.php',
					{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle},
					function(result){
						//alert(JSON.stringify(result));
						if (result.success){
							$('#dg_sysuser').datagrid('reload');    // reload the user data
						} else {
							art.dialog({
				        	    content: result.message,
				        	    ok: true
				        	});
						}
					},
					'json'
				);
			}
		});
	}else{
		$('#btn-remove_sysuser').grumble(missSelectMsg);
	}
}
function saveAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysuser').form('submit',{
		url: submit_url,
		onSubmit: function(){
			var isValidate = $(this).form('validate');
			if(isValidate){
				if(!checkValue($('#code_sysuser'), '用户名', '字母、数字、下划线')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#passwd_sysuser'), '密码', '字母、数字、下划线')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#name_sysuser'), '姓名', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#mobile_sysuser'), '手机号', '手机号')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#bank_sysuser'), '银行', '汉字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#account_sysuser'), '账号', '数字')){
					$.messager.progress('close');
					return false;
				}
				if(!checkValue($('#orderno_sysuser'), '排序号', '数字')){
					$.messager.progress('close');
					return false;
				}
				
				return true;
			}else{
				$.messager.progress('close');
				return false;
			}
		},
		success: function(data){
			$.messager.progress('close');
			//alert(data);
			var dataObj = eval('(' + data + ')');  // change the JSON string to javascript object
			/**这里会有隐患，如果后台php出错，会导致这个data不是json string，
			从而无法转换为对象，即：上述eval函数无法执行成功。
			如果要debug，则直接将data打印出来即可。alert(data);*/
	        if (dataObj.success){
	        	art.dialog({
	        	    content: dataObj.message,
	        	    ok: function(){
	        	    	$('#dlg_sysuser').dialog('close');        // close the dialog
	    				$('#dg_sysuser').datagrid('reload');      // reload the user data
		        	}
	        	});				
			} else {
				art.dialog({
	        	    content: dataObj.message,
	        	    ok: function(){
		        	}
	        	});
			}
		}
	});
}

function act(flag){
	var row = $('#dg_sysuser').datagrid('getSelected');
	if (row){
		if(flag == 'check' && row.CHECKSTAT == "已复核"){
			art.dialog({content:'该用户已经是复核状态，不需要复核！', ok: true});
		}else if(flag == 'chpwd'){
			$('#dlg_sysuser_chpwd').dialog('open').dialog('setTitle','修改密码').dialog('center');
			$('#fm_sysuser_chpwd').form('clear');
		}else{
			var act = '';
			var cfmsg = '';
			var cfmsgmsg = '';
			if(flag == 'onoff'){
				if(row.STAT == "启用"){act = '禁用'; cfmsg = '禁用';}
				if(row.STAT == "禁用"){act = '启用'; cfmsg = '启用';}
				cfmsgmsg = '您确定要&nbsp<font color="red">'+cfmsg+'</font>&nbsp该用户吗？';
			}
			if(flag == 'check'){
				if(row.CHECKSTAT == "未复核"){act = '已复核'; cfmsg = '复核';}
				cfmsgmsg = '您确定要对该用户资料进行&nbsp<font color="red">'+cfmsg+'</font>&nbsp吗？';
			}
			if(flag == 'resetpwd'){
				act = '';
				cfmsg = '重置';
				cfmsgmsg = '您确定要&nbsp<font color="red">'+cfmsg+'</font>&nbsp该用户的密码为&nbsp<font color="red">空</font>&nbsp吗？';
			}
			$.messager.confirm('Confirm',cfmsgmsg,function(r){
				if (r){
					$.post(
						'../'+modulepath+'/act.php',
						{CODE:row.CODE,MODULENO:moduleno,MODULEOBJ:moduleobj,MODULETITLE:moduletitle,FLAG:flag,ACT:act,CFMSG:cfmsg},
						function(result){
							//alert(JSON.stringify(result));
							if (result.success){
								art.dialog({
					        	    content: result.message,
					        	    ok: function(){
					        	    	$('#dg_sysuser').datagrid('reload');    // reload the user data
					        	    }
					        	});
								
							} else {
								art.dialog({
					        	    content: result.message,
					        	    ok: true
					        	});
							}
						},
						'json'
					);
				}
			});
		}
	}else{
		if(flag == 'onoff')
			$('#btn-onoff_sysuser').grumble(missSelectMsg);
		else if(flag == 'check')
			$('#btn-check_sysuser').grumble(missSelectMsg);
		else if(flag == 'chpwd')
			$('#btn-chpwd_sysuser').grumble(missSelectMsg);
		else if(flag == 'resetpwd')
			$('#btn-resetpwd_sysuser').grumble(missSelectMsg);
		
		
	}
}

function chpwdAct(){
	$.messager.progress();	// display the progress bar
	$('#fm_sysuser_chpwd').form('submit',{
		url: '../'+modulepath+'/chpwd.php',
		onSubmit: function(param){
			var row = $('#dg_sysuser').datagrid('getSelected');
			if (row){
				param.MODULENO = moduleno;
		        param.MODULEOBJ = moduleobj;
		        param.MODULETITLE = moduletitle;
		        param.CODE = row.CODE;
		        
				var isValidate = $(this).form('validate');
				if(isValidate){
					if(!checkValue($('#newpasswd_sysuser'), '密码', '字母、数字、下划线')){
						$.messager.progress('close');
						return false;
					}
					return true;
				}else{
					$.messager.progress('close');
					return false;
				}
			}else{
				return false;
			}
		},
		success: function(data){
			$.messager.progress('close');
			//alert(data);
			var dataObj = eval('(' + data + ')');  // change the JSON string to javascript object
			/**这里会有隐患，如果后台php出错，会导致这个data不是json string，
			从而无法转换为对象，即：上述eval函数无法执行成功。
			如果要debug，则直接将data打印出来即可。alert(data);*/
	        if (dataObj.success){
	        	art.dialog({
	        	    content: dataObj.message,
	        	    ok: function(){
	        	    	$('#dlg_sysuser_chpwd').dialog('close');        // close the dialog
		        	}
	        	});				
			} else {
				art.dialog({
	        	    content: dataObj.message,
	        	    ok: function(){
		        	}
	        	});
			}
		}
	});
}
