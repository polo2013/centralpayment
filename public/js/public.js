// 对Date的扩展，将 Date 转化为指定格式的String 
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
// 例子： 
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.Format = function(fmt) 
{ //author: meizz 
  var o = { 
    "M+" : this.getMonth()+1,                 //月份 
    "d+" : this.getDate(),                    //日 
    "h+" : this.getHours(),                   //小时 
    "m+" : this.getMinutes(),                 //分 
    "s+" : this.getSeconds(),                 //秒 
    "q+" : Math.floor((this.getMonth()+3)/3)  //季度 
  };
  if(/(y+)/.test(fmt)) 
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
  for(var k in o)
    if(new RegExp("("+ k +")").test(fmt))
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
  
  if(/(S)/.test(fmt)){  //始终返回3位毫秒数，不足补0
	  var mis = this.getMilliseconds()+"";
	  var mis2 = (mis.length==1) ? "00"+mis : ((mis.length==2) ? "0"+mis : mis);
	  fmt = fmt.replace(RegExp.$1, mis2);
  }
  return fmt;
};

//禁用回退键[backspace键]浏览历史跳转的解决办法 
window.onload=function(){
	/****************************
	 * 作者：q821424508@sina.com	*
	 * 时间：2012-08-20			*
	 * version：2.1				*
	 ****************************/
	document.getElementsByTagName("body")[0].onkeydown =function(){
		//获取事件对象
		var elem = event.relatedTarget || event.srcElement || event.target ||event.currentTarget; 
		if(event.keyCode==8){//判断按键为backSpace键
			//获取按键按下时光标做指向的element
			var elem = event.srcElement || event.currentTarget; 
			//判断是否需要阻止按下键盘的事件默认传递
			var name = elem.nodeName;
			if(name!='INPUT' && name!='TEXTAREA'){
				return _stopIt(event);
			}
			var type_e = elem.type.toUpperCase();
			if(name=='INPUT' && (type_e!='TEXT' && type_e!='TEXTAREA' && type_e!='PASSWORD' && type_e!='FILE')){
					return _stopIt(event);
			}
			if(name=='INPUT' && (elem.readOnly==true || elem.disabled ==true)){
					return _stopIt(event);
			}
		}
	};
};

function _stopIt(e){
	if(e.returnValue){
		e.returnValue = false ;
	}
	if(e.preventDefault ){
		e.preventDefault();
	}
	return false;
}
//禁用回退键[backspace键]完毕

/*****************art.dialog*******************/
//对话框全局配置
(function (config) {
    config['lock'] = true;
    config['fixed'] = true;
    config['okVal'] = '确定[ok]';
    config['cancelVal'] = '取消[cancel]';
    config['esc'] = false;
    //config['drag'] = false;
    config['resize'] = false;
    config['zIndex'] = 9999;
    config['cancel'] = false;
})(art.dialog.defaults);
/**
 * 警告
 * @param	{String}	消息内容
 */
artDialog.alert = function (content) {
    return artDialog({
        id: 'Alert',
        icon: 'warning',
        fixed: true,
        lock: true,
        content: content,
        ok: true
    });
};
/**
 * 确认
 * @param	{String}	消息内容
 * @param	{Function}	确定按钮回调函数
 * @param	{Function}	取消按钮回调函数
 */
artDialog.confirm = function (content, yes, no) {
    return artDialog({
        id: 'Confirm',
        icon: 'question',
        fixed: true,
        lock: true,
        opacity: .1,
        content: content,
        ok: function (here) {
            return yes.call(this, here);
        },
        cancel: function (here) {
            return no && no.call(this, here);
        }
    });
};
/**
 * 提问
 * @param	{String}	提问内容
 * @param	{Function}	回调函数. 接收参数：输入值
 * @param	{String}	默认值
 */
artDialog.prompt = function (content, yes, value) {
    value = value || '';
    var input;
    
    return artDialog({
        id: 'Prompt',
        icon: 'question',
        fixed: true,
        lock: true,
        opacity: .1,
        content: [
            '<div style="margin-bottom:5px;font-size:12px">',
                content,
            '</div>',
            '<div>',
                '<input value="',
                    value,
                '" style="width:18em;padding:6px 4px" />',
            '</div>'
            ].join(''),
        init: function () {
            input = this.DOM.content.find('input')[0];
            input.select();
            input.focus();
        },
        ok: function (here) {
            return yes && yes.call(this, input.value, here);
        },
        cancel: true
    });
};
/**
 * 短暂提示
 * @param	{String}	提示内容
 * @param	{Number}	显示时间 (默认1.5秒)
 */
artDialog.tips = function (content, time) {
    return artDialog({
        id: 'Tips',
        title: false,
        cancel: false,
        fixed: true,
        lock: false
    })
    .content('<div style="padding: 0 1em;">' + content + '</div>')
    .time(time || 1.5);
};


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
		/*
		art.dialog({
		    content: errmsg,
		    ok: function(){
	    	    input.focus();
	    	    input.select();
	    	}
		});
		*/
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

//自定义validatebox验证规则
$.extend($.fn.validatebox.defaults.rules, {
    minLength: {
        validator: function(value, param){
            return value.length >= param[0];
        },
        message: 'Please enter at least {0} characters.'
    },
    equals: {
        validator: function(value,param){
            return value == $(param[0]).val();
        },
        message: '密码输入不一致！'
    }
});

//数组相关
//查找是否有制定值
function arrSearch(value, arr){
	for(var i=0; i<arr.length; i++){
		if(arr[i] == value){
			return true;
			break;
		}
	}
	return false;
}
//查找index
Array.prototype.indexOf = function(val) {
  for (var i = 0; i < this.length; i++) {
      if (this[i] == val) return i;
  }
  return -1;
};
//删除指定值
Array.prototype.remove = function(val) {
  var index = this.indexOf(val);
  if (index > -1) {
      this.splice(index, 1);
  }
};
//取得radom值
function getRandom(min,max){
    return Math.floor(Math.random()*(max-min)+min);
}
function getRandomArrVal(arr){
	return arr[getRandom(0, arr.length)];
}

