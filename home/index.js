var moduleno;
var moduletitle;
var modulepath;
var moduleobj;
var submit_url;
var missSelectMsg = {text: '请选择数据!', angle: 60, distance: 40, type: 'alt-', showAfter: 100, hideAfter: 2000};
var paymentViewMode = undefined;
var viewNum = undefined;
var allAuth = new Array();
var selectedRow = new Array();
var selectedTab = "";
var tabInitCount = new Array();

//DOM加载完就执行
$(document).ready(function(){
	$(document.body).showLoading();
	ajaxCheckMenu();

	$('#tt').tabs({
		/**
		 * 这里记录一下发现的问题：
		 * 每当select tab的时候，会执行该tab的  $(document).ready 函数
		 * 而 tab.panel refresh的时候，又会执行一次 $(document).ready 函数
		 * 所以，初次打开一个tab，alert测试会出现两次
		 */
		onSelect:function(title,index){
			//if(title != '主页'){
			//if(selectedTab != title){
				selectedTab = title;
				var tab = $(this).tabs('getTab',index);
				//必须刷新，否则会出现form乱串的现象
				tab.panel('refresh');

				//重置全局变量
				var opts = tab.panel('options');
				var content = String(opts.content); //这里必须强制类型转换为string
				var paraArr = content.split(",");
				moduleno = paraArr[0];
				moduletitle = paraArr[1];
				modulepath = paraArr[2];
				moduleobj = paraArr[3];
				//获取当前角色拥有当前模块的所有权限
				allAuth = ajaxFuncAuthCheck(moduleno, 'allauth');
			//}
			
		}
	});
	
	
	//主页
	$('#tt').tabs('add',{
	    title:'主页',
	    style:{padding:10},
	    href:'main.php'
	});
	
});

/*
 //所有元素加载完再执行，等价于js的window.onload
 $(window).load(function (){ });
*/

function openTabs(code,name,url,obj,mode){
	if(code == '009' && mode == 'new'){
		paymentViewMode = "new";
	}
	if(code == '009' && mode == 'view'){
		paymentViewMode = "view";
	}
	
	var moduleContent = code+','+name+','+url+','+obj;

	if ($('#tt').tabs('exists', name)){
		$('#tt').tabs('select', name);
	} else {
		$('#tt').tabs('add',{
			title: name,
			href: '../'+url+'/main.php',
			closable: true,
			style: {padding:10},
			content: moduleContent
		});
	}
}

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
		var aboutInfo = 
				'<div>v1.0</div>' + 
				'<ol>' + 
					'<li>构建系统框架</li>' + 
					'<li>新增付款汇总表模块</li>' +
				'</ol>' ;
		$("#menu_toolbar").append('<a id="menu_about" href="javascript:void(0)"></a>');
		$("#all_menu_in_one").append('<div id="submenu_about" class="menu-content" style="font-size:14px"></div>');
		$("#submenu_about").append(aboutInfo);
		$("#menu_about").menubutton({plain:true, text:'关于', menu:'#submenu_about'});
		
		
	});
}

//toggle选中与不选
function rowPublicAct(rowIndex, rowData){
	var jsonRow = JSON.stringify(rowData);
	if(selectedRow.length == 0){
		selectedRow.push(jsonRow);
	}else{
		if(selectedRow[0] == jsonRow){
			selectedRow.pop();
			$(this).datagrid('unselectRow', rowIndex);
		}else{
			selectedRow.pop();
			selectedRow.push(jsonRow);
		}
	}
}
//logout
function logout(){
	if(confirm('真的离开吗？')){
		window.top.location='../logout/logout.php';
	}
}

//查看付款汇总表
function actView(num){
	//alert(num);
	$.getJSON("../public/php/getModuleInfo.php", {ModuleNo: "009"}, function(data){
		//alert(JSON.stringify(data));
		openTabs(data.ModuleNo,data.ModuleName,data.ModuleUrl,data.ModuleObj,'view');
	});
	viewNum = num;
}

//刷新 tiles
function ajaxGenTile() {
	var tileWide = ['one-wide','two-wide'];
	var tileDelay = [2000,3000,4000];
	var tileMode = ['fade','flip','carousel','slide'];
	var tileColor = ['amber', 'blue', 'brown', 'cobalt', 'crimson', 'cyan', 'emerald', 'green', 'indigo', 'lime', 'magenta', 'mango', 'mauve', 'olive', 'orange', 'pink', 'purple', 'red', 'sienna', 'steel', 'teal', 'violet', 'yellow'];
	var tileDir = ['horizontal', 'vertical'];
	$.getJSON("CheckMenu.php", {flag:"all"}, function(data){
		$("#all_tiles").empty();
		var randomWideTxt = '';
		var randomWideVal = 0;
		var sumWide = 0;
		var tile = '';
		var i = 1;
		var j = 10 - i;
		var tempsum = 0;
		var imageFormat = '';
		var imageFormat_t = '';
		$.each(data, function(idx,value){
			$.each(value.MODULES, function(idx2,value2){
				while(true){
					randomWideTxt = getRandomArrVal(tileWide);
					switch (randomWideTxt){
					case 'one-wide':
						randomWideVal = 1;
						imageFormat = '../public/images/tile/'+value2.MURL+'.png';
						imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'_icon.png';
						break;
					case 'two-wide':
						randomWideVal = 2;
						imageFormat = '../public/images/tile/'+value2.MURL+'2.png';
						imageFormat_t = '../public/images/tile/icon/'+value2.MURL+'2_icon.png';
						break;
					}
					//确保每行塞满
					if(sumWide < 5)
						tempsum = 5;
					else if(sumWide >= 5 && sumWide < 10)
						tempsum = 10;
					else if(sumWide >= 10 && sumWide < 15)
						tempsum = 15;
					
					if(sumWide + randomWideVal <= tempsum){
						//确保三行搞定
						if(sumWide + randomWideVal + j <= 15){
							var hasright = '';
							if(value2.HASRIGHT){
								hasright = ' data-link= "javascript:openTabs(\''+value2.MCODE+'\',\''+value2.MNAME+'\',\''+value2.MURL+'\',\''+value2.MOBJ+'\',\'new\');"';
							}
							tile = '<div id="tile_'+idx+'_'+idx2+'" '
							+' class="live-tile '+ randomWideTxt + ' ' +getRandomArrVal(tileColor)+'" '
							+' data-mode="'+getRandomArrVal(tileMode)+'" '
							+' data-delay="'+getRandomArrVal(tileDelay)+'" '
							+' data-stops="50%,100%,0px" '
							+' data-direction="'+getRandomArrVal(tileDir)+'" '
							+' data-bounce=true '
							+ hasright + '>'
							+'<span class="tile-title">'+value2.MNOTE+'</span>'
							+'<div><img class="full" src="'+imageFormat+'" alt="1" /></div>'
							+'<div><img class="full" src="'+imageFormat_t+'" alt="2" /></div>'
							+'</div>';
	
							$("#all_tiles").append(tile);

							sumWide = sumWide + randomWideVal;
							i ++;
							j --;
							break;
						}
					}
				}
			});
		});
		
		$("div[id^='tile_']").liveTile();
		
		
	});
	
}

//刷新消息中心
function ajaxGenTodo() {
	/**
	 * 如果直接在ready函数中写下列语句，是不行的，原因不知，可能是元素还没有加载完成。
	 * 而通过 ajax 返回函数来处理，就能成功，非常的费解。如下就能成功。奇怪
	 */
	$.getJSON("CheckTodo.php", function(data){
		//alert(JSON.stringify(data));
		var panels = $('#msg_todo').accordion('panels');
		//alert(JSON.stringify(panels.length));
		$.each(panels, function(idx,value){
			$('#msg_todo').accordion('remove', panels[idx]);
		});
		
		
		$.each(data, function(idx,value){
			var titleFormat = value.STAT+'<font style="color:red">（'+value.CNT+'）</font>';
			var contentFormat = '';
			$.each(value.DETAIL, function(idx2,value2){
				contentFormat = contentFormat 
					+'<a id="billTodo_'+idx+'_'+idx2+'" href="javascript:void(0)" onclick="actView(\''+value2.NUM+'\')">付款汇总表编号：'
					+value2.BILLNUM+' [ 金额：'+value2.TOTALAMT+' ] </a><br>';
			});
				
				
			$('#msg_todo').accordion('add', {
				title: titleFormat,
				content: contentFormat,
				selected: false
			});
		});

		
	});
}

function expandAllMsg(){
	var panels = $('#msg_todo').accordion('panels');
	$.each(panels, function(idx,value){
		$('#msg_todo').accordion('select', $('#msg_todo').accordion('getPanelIndex', panels[idx]));
		//alert(JSON.stringify($('#msg_todo').accordion('getPanelIndex', panels[idx])));
	});
	
	//alert('expand');
}
function collapseAllMsg(){
	var panels = $('#msg_todo').accordion('panels');
	$.each(panels, function(idx,value){
		$('#msg_todo').accordion('unselect', $('#msg_todo').accordion('getPanelIndex', panels[idx]));
	});
}