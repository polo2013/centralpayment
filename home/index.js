var moduleno;     //模块号
var moduletitle;  //模块名称
var modulepath;   //模块路径
var moduleobj;    //模块表
var submit_url;   //提交url
var missSelectMsg = {text: '请选择数据!', angle: 60, distance: 40, type: 'alt-', showAfter: 100, hideAfter: 2000};
var paymentViewMode = undefined;  //付款汇总表的显示模式（new or view）
var viewNum = undefined;          //当前查看的付款汇总表的单号
var allAuth = new Array();        //当前用户所拥有的本模块的所有权限
var selectedRow = new Array();    //当前选中的datagrid中的行，用于select后再次点击可以unselect
var selectedTab = "";             //当前选中的tab，用于select为当前tab后再次点击它，不refresh
var openedPanels = new Array();    //记录消息中心中打开的面板，在tab的unselect事件中记录，在ready中凭此恢复。


//DOM加载完就执行
$(document).ready(function(){
	$(document.body).showLoading();
	//加载菜单
	ajaxCheckMenu();
	//设置tabs事件
	$('#tt').tabs({
		/**
		 * 这里记录一下发现的问题：
		 * 每当新开 tab的时候，会得到如下顺序
		 * 
		 * []select--before refresh
		 * []select--after refresh
		 * ["查看权","新增权","修改权","删除权","模块权限设置权"]select--after get allAuth
		 * ["查看权","新增权","修改权","删除权","模块权限设置权"]ready
		 * ["查看权","新增权","修改权","删除权","模块权限设置权"]ready
		 * SCRIPT5007: 无法获取未定义或 null 引用的属性“options”
		 * 文件: jquery.easyui.min.js，行: 8761，列: 1
		 * 
		 * 1、先触发onselect事件，其中的refresh操作，不会执行$(document).ready 函数，到底是做些什么，不知道。
		 * 2、执行2次$(document).ready 函数，猜测可能一次是加载main.php，另一次是refresh引起。
		 * 所以，初次打开一个tab，会init两次.
		 * 解决办法：不在每个main中使用 ready 函数，将其内容转移到init函数中，然后再onselect事件中调用init函数。
		 * 结果：不行，因为ready函数是在各个main加载完毕后执行的，但是init无法做到这一点，并且即使用延时的办法，也无法消除form乱串的现象
		 * 未找到form乱串的原因。目前的想法是看能不能每次将form关闭。
		 */
		onSelect:function(title,index){
			selectedTab = title;
			var tab = $(this).tabs('getTab',index);
			if(title != '主页'){
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
				//console.info(JSON.stringify(allAuth)+'select--after get allAuth');
				
				//console.info(JSON.stringify(allAuth)+'select--before refresh');
				//必须刷新，否则会出现form乱串的现象
				tab.panel('refresh');
				//console.info(JSON.stringify(allAuth)+'select--after refresh');
				
			}else{
				//console.info(JSON.stringify(openedPanels)+'select-before');
				tab.panel('refresh');
				//console.info(JSON.stringify(openedPanels)+'select-after');
			}
			
		},
		onUnselect:function(title,index){
			if(title == '主页'){
				rememberMsgStat();
				//console.info(JSON.stringify(openedPanels)+'unselect');
			}
		}
	
	
	});
	
	//加载主页
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

