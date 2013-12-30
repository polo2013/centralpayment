$(document).ready(function(){
	//检查权限
	var ret = false;
	var isdisable = true;
	var role = "";
	
	$('#btn-expandAll_sysauth').linkbutton({
	    iconCls: 'icon-add',
	    plain: true,
	    text: '全部展开',
	    disabled: false
	});
	$('#btn-collapseAll_sysauth').linkbutton({
	    iconCls: 'icon-remove',
	    plain: true,
	    text: '全部收缩',
	    disabled: false
	});
	$('#btn-selectAll_sysauth').linkbutton({
	    iconCls: 'icon-ok',
	    plain: true,
	    text: '全部选中',
	    disabled: false
	});
	$('#btn-cancelAll_sysauth').linkbutton({
	    iconCls: 'icon-cancel',
	    plain: true,
	    text: '全部取消',
	    disabled: false
	});
	
	ret = ajaxFuncAuthCheck(moduleno,'修改权');;
	if(ret == true){isdisable = false;}else{isdisable = true;}
	$('#btn-save_sysauth').linkbutton({
	    iconCls: 'icon-save',
	    plain: false,
	    text: '保存',
	    disabled: isdisable
	});

	$.getJSON("../public/php/getRoleForSelect.php", function(data){
		$('#role_sysauth').combobox('loadData', data.allrole);
		$('#role_sysauth').combobox('select', data.myrole);
	});

	
	$('#role_sysauth').combobox({
		onSelect: function(rec){
			$.getJSON("../public/php/getOneChangeAnother.php",{who2who:"role2org",oneValue:rec.value}, function(data){
				$('#org_sysauth').combobox('setValue',data.anotherValue);
				var url_getInfo = '../'+modulepath+'/getInfo.php?ROLE='+rec.value;
				//alert(url_getInfo);
				//alert(encodeURI(url_getInfo));
				$('#tt_sysauth').tree({
					url:encodeURI(url_getInfo),
					animate:true,
					checkbox:true,
					cascadeCheck:true,
					onClick: function(node){
						var isleaf = $('#tt_sysauth').tree('isLeaf', node.target);
						if(isleaf){
							if(node.checked){
								$('#tt_sysauth').tree('uncheck', node.target);
								$('#tt_sysauth').tree('update', {
									target: node.target,
									iconCls: ''
								});
							}else{
								$('#tt_sysauth').tree('check', node.target);
								$('#tt_sysauth').tree('update', {
									target: node.target,
									iconCls: 'icon-ok'
								});
							}
						}else{
							if(node.state == 'open')
								$('#tt_sysauth').tree('collapse', node.target);
							else
								$('#tt_sysauth').tree('expand', node.target);
						}
					},
					onCheck: function(node,checked){
						var isleaf = $('#tt_sysauth').tree('isLeaf', node.target);
						if(isleaf){
							if(checked){
								$('#tt_sysauth').tree('update', {
									target: node.target,
									iconCls: 'icon-ok'
								});
							}else{
								$('#tt_sysauth').tree('update', {
									target: node.target,
									iconCls: ''
								});
							}
						}else{
							if(checked){//表示全部选中之后
								var children = $('#tt_sysauth').tree('getChildren', node.target);
								//alert(JSON.stringify(child[0]));
								$.each(children, function(i, n){
									$('#tt_sysauth').tree('update', {
										target: n.target,
										iconCls: 'icon-ok'
									});
								});
							}
							else{//表示全部取消后
								var children = $('#tt_sysauth').tree('getChildren', node.target);
								//alert(JSON.stringify(child[0]));
								$.each(children, function(i, n){
									$('#tt_sysauth').tree('update', {
										target: n.target,
										iconCls: ''
									});
								});
							}
						}
					}
				});
			});
		}
	});
});

function expandAllAct(){
	var roots = $('#tt_sysauth').tree('getRoots');
	//alert(JSON.stringify(roots));
	$.each(roots, function(i,v){
		$('#tt_sysauth').tree('expandAll', v.target);
	});
}
function collapseAllAct(){
	var roots = $('#tt_sysauth').tree('getRoots');
	//alert(JSON.stringify(roots));
	$.each(roots, function(i,v){
		$('#tt_sysauth').tree('collapseAll', v.target);
	});
}
function selectAllAct(){
	var roots = $('#tt_sysauth').tree('getRoots');
	//alert(JSON.stringify(roots));
	$.each(roots, function(i,v){
		$('#tt_sysauth').tree('check', v.target);
	});
}
function cancelAllAct(){
	var roots = $('#tt_sysauth').tree('getRoots');
	//alert(JSON.stringify(roots));
	$.each(roots, function(i,v){
		$('#tt_sysauth').tree('uncheck', v.target);
	});
}
function saveAct(){
	var isValidate = $(this).form('validate');
	if(isValidate){
		var nodes = $('#tt_sysauth').tree('getChecked');	// get checked nodes
		var msg = '确认授权给该角色吗？';
		if(nodes.length == 0){
			msg = '取消该角色的所有权限吗？';
		}
		art.dialog({
		    content: msg,
		    ok: function(){
		    	var authdtl = JSON.stringify(nodes);
		    	//alert(authdtl);
		    	$.messager.progress();	// display the progress bar
		    	$('#fm_sysauth').form('submit',{
		    		url: '../'+modulepath+'/save.php',
		    		onSubmit: function(param){
		    			param.MODULENO = moduleno;
		    	        param.MODULEOBJ = moduleobj;
		    	        param.MODULETITLE = moduletitle;
		    	        param.AUTHDTL = authdtl;
		    			return true;
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
		    	        	    	//这里可以刷新权限树。不刷新也可。
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
		    },
		    cancel: true
		
		});

	}
}

