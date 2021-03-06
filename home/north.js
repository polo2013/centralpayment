//刷新菜单
function ajaxCheckMenu() {
	$.getJSON("CheckMenu.php", {flag:"auth"}, function(data){
		$(document.body).hideLoading();
		//alert(JSON.stringify(data));
		//总容器下增加toolbar层，存放所有菜单
		$("#all_menu_in_one").append('<div id="menu_toolbar"></div>');
		//toolbar下增加第一个菜单：HOME
		$("#menu_toolbar").append('<a id="menu_home" href="javascript:void(0)"></a>');
		$("#menu_home").linkbutton({plain:true, text:'主页'});
		$('#menu_home').unbind();
		$('#menu_home').bind('click', function(){$('#tt').tabs('select', '主页');});
		
		$.each(data, function(idx,value){
			//总容器下增加子菜单层
			$("#all_menu_in_one").append('<div id="submenu_'+idx+'"></div>');
			
			$.each(value.MODULES,function(idx2,value2){
				//子菜单层下增加子菜单项
				$("#submenu_"+idx).append('<div id="submenu_'+idx+'_'+idx2+'" data-options="iconCls:\'icon-menu-'+value2.MURL+'\'">'+value2.MNAME+'</div>');
				$("#submenu_"+idx+"_"+idx2).unbind();
				$("#submenu_"+idx+"_"+idx2).bind('click', function(){
					openTabs(value2.MCODE,value2.MNAME,value2.MURL,value2.MOBJ,'new');
				});
			});
			
			//toolbar下增加下拉菜单
			$("#menu_toolbar").append('<a id="menu_'+idx+'" href="javascript:void(0)"></a>');
			$("#menu_"+idx).menubutton({plain:true, text:value.MENU, menu:'#submenu_'+idx});
		});
		
		//增加最后一个菜单
		/*
		var aboutInfo = ''
			+ '<div>' 
			+ 	'<div>v1.0->v1.1</div>' 
			+ 	'<ol>' 
			+		'<li>[新增] 支持用户多机构多角色任职</li>'
			+		'<li>[新增] 付款汇总表删除功能</li>'
			+ 	'</ol>' 
			+ '</div>'

			+ '<div>' 
			+ 	'<div>v1.0</div>' 
			+ 	'<ol>' 
			+		'<li>[新增] 构建系统框架</li>' 
			+		'<li>[新增] 付款汇总表模块</li>' 
			+ 	'</ol>' 
			+ '</div>';
		*/
		$("#menu_toolbar").append('<a id="menu_about" href="javascript:void(0)"></a>');
		//$("#all_menu_in_one").append('<div id="submenu_about" class="menu-content" style="font-size:14px;"></div>');
		//$("#submenu_about").append(aboutInfo);
		//$("#menu_about").menubutton({plain:true, text:'关于', menu:'#submenu_about'});
		$("#menu_about").linkbutton({plain:true, text:'关于'});
		
		
	});
}

function checkMultiOrg(){
	$.getJSON("CheckMultiOrg.php", function(data){
		//alert(JSON.stringify(data));
		if(data.Multi){
			$("#switch_org_btn").html('组织机构切换');
		}
	});
}

function switch_org(){
	$.getJSON("CheckMultiOrg.php", function(data){
		//alert(JSON.stringify(data));
		if(data.Multi){
			$('#dg_switch_org').datagrid({
				iconCls:'icon-edit',
				title: '请选择：',
				rownumbers: true,
				fitColumns: true,
				nowrap:false,
				singleSelect: true,
				border: true,
				striped: true,
				columns: [[
					{field:'ck',checkbox:true},
					{field:'ORG',title:'可选机构',width:100,align:'center'},
					{field:'ROLE',title:'担任角色',width:100,align:'center'}
				]],
				data: data.OtherOrg
			});
			$('#dlg_switch_org').dialog('open').dialog('setTitle','组织机构切换').dialog('center');
		}
	});
}

function switch_org_Act(){
	var row = $('#dg_switch_org').datagrid('getSelected');
	if (row){
		//alert(JSON.stringify(row));
		$.post(
    		'SwitchOrg.php',
    		{
    			"NextOrg" : encodeURI(row.ORG),
    			"NextRole" : encodeURI(row.ROLE),
    		},
			function(result){
				//alert(JSON.stringify(result));
				if (result.success){
				window.top.location='../home';
				} else {
					art.dialog({
		        	    content: result.message,
		        	    ok: true
		        	});
				}
			}, 
			"json"
    	);
	}
}


//logout
function logout(){
	if(confirm('真的离开吗？')){
		window.top.location='../logout/logout.php';
	}
}