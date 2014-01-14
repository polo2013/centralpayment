
//查看付款汇总表
function actView(num){
	//alert(num);
	$.getJSON("../public/php/getModuleInfo.php", {ModuleNo: "009"}, function(data){
		//alert(JSON.stringify(data));
		openTabs(data.ModuleNo,data.ModuleName,data.ModuleUrl,data.ModuleObj,'view');
	});
	viewNum = num;
}
//取得功能权限
function ajaxFuncAuthCheck(module, auth) {
    var $params = "module=" + module + "&auth=" + auth;
    var rtn = false;
    var allAuth = new Array();
    $.ajax({
        type: 'POST',
        url: '../public/php/getFuncAuth.php',
        async: false,
        cache: false,
        dataType: 'json',
        data: $params,
        timeout: 2000,  //毫秒，超时后执行error
        success: function (data) {
        	//alert(JSON.stringify(data));
        	if(auth == 'allauth'){
        		//将json转为数组
        		allAuth = data.allAuth;
        	}else{
        		rtn = data.success;
        	}
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	art.dialog({
        	    content: '与服务器通讯失败，请稍后重试！<br>textStatus：'+textStatus+'<br>XMLHttpRequest.status：'+XMLHttpRequest.status+'<br>XMLHttpRequest.readyState：'+XMLHttpRequest.readyState+'<br>'+errorThrown,
        	    ok: true
        	});
        }
    });
    if(auth == 'allauth'){
    	//返回一个数组
    	return allAuth;
    }else{
    	return rtn;
    }
}

//取得编号
function ajaxGetNewCode(module, para) {
    var params = "module=" + module + "&para=" + para;
    var rtn = '';
    $.ajax({
        type: 'POST',
        url: '../public/php/getNewCode.php',
        async: false,
        cache: false,
        dataType: 'json',
        data: params,
        timeout: 2000,  //毫秒，超时后执行error
        success: function (data) {
        	//alert(JSON.stringify(data));
        	rtn = data.newNumber;
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	art.dialog({
        	    content: '与服务器通讯失败，请稍后重试！<br>textStatus：'+textStatus+'<br>XMLHttpRequest.status：'+XMLHttpRequest.status+'<br>XMLHttpRequest.readyState：'+XMLHttpRequest.readyState+'<br>'+errorThrown,
        	    ok: true
        	});
        }
    });
    return rtn;
}

//检查输入
function checkValue(input, desc, type){
	var reg;
	var errmsg;
	switch(type)
	{
	case '数字':
		reg = new RegExp("^[0-9]*$");
		errmsg = desc + '只能为'+type+'！';
		break;
	case '汉字':
		reg = new RegExp("^[\u4e00-\u9fa5]*$");
		errmsg = desc + '只能为'+type+'！';
		break;
	case '字母':
		reg = new RegExp("^[A-Za-z]*$");
		errmsg = desc + '只能为'+type+'！';
		break;
	case '字母和下划线':
		reg = new RegExp("^[A-Za-z_]*$");
		errmsg = desc + '只能由'+type+'组成！';
		break;
	case '字母、数字、下划线':
		reg = new RegExp("^[A-Za-z0-9_]*$");
		errmsg = desc + '只能由'+type+'组成！';
		break;
	case '字母、数字、下划线、点':
		reg = new RegExp("^[A-Za-z0-9_.]*$");
		errmsg = desc + '只能由'+type+'组成！';
		break;
	case '数字、中划线':
		reg = new RegExp("^([0-9]{3,4}-[0-9]{7,8}(-[0-9]{1,3})?)?$");
		errmsg = '格式不正确！示例：0123-1234567、010-12345678、021-12345678-888！';
		break;
	case '汉字、字母、数字':
		reg = new RegExp("^[\u4e00-\u9fa5A-Za-z0-9（）]*$");
		errmsg = desc + '只能由'+type+'组成！';
		break;
	case '手机号':
		reg = new RegExp('^(1[0-9]{10})?$');
		errmsg = '格式不正确！示例：13801234567';
		break;
	case '来源状态':
		reg = new RegExp('^([0-9]{3})?(,[0-9]{3})*$');
		errmsg = '格式不正确！示例：001,002,003';
		break;
	case '付款汇总表':
		reg = new RegExp('^[0-9]{4}(0[1-9]|1[0-2]){1}(0[1-9]|1[0-9]|2[0-9]|3[0-1]){1}[0-9]{4}$');
		errmsg = '编号规则为“日期+序列号”！<br>示例：201401010001';
		break;
	default:
		reg = "";
	}
	
	if((reg=="")||(!reg.test(input.val()))){
		input.grumble(
				{
					text: errmsg, 
					angle: 85, 
					distance: 120, 
					type: 'alt-',  //蓝色
					showAfter: 100,
					hideAfter: 2000
				}
		);
		return false;
	}else{
		return true;
	}
}

//打开 tab
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

//toggle grid 行select与unselect
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