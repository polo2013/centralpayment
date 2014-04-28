function getLodop(oOBJECT,oEMBED){
/*
 * 本函数根据浏览器类型决定采用哪个对象作为控件实例：
 * IE系列、IE内核系列的浏览器采用oOBJECT，
 * 其它浏览器(Firefox系列、Chrome系列、Opera系列、Safari系列等)采用oEMBED,
 * 对于64位浏览器指向64位的安装程序install_lodop64.exe。
 */
var strHtmInstall = "<font color='green'>打印控件未安装！点击&nbsp<a href='../public/lodop6.164/install_lodop32.exe' target='_self'>这里执行安装</a>，安装后请重新进入系统。</font><br>";
var strHtmUpdate = "<font color='green'>打印控件需要升级！点击&nbsp<a href='../public/lodop6.164/install_lodop32.exe' target='_self'>这里执行升级</a>，升级后请重新进入系统。</font><br>";
var strHtm64_Install = "<font color='green'>打印控件未安装！点击&nbsp<a href='../public/lodop6.164/install_lodop64.exe' target='_self'>这里执行安装</a>，安装后请重新进入系统。</font><br>";
var strHtm64_Update = "<font color='green'>打印控件需要升级！点击&nbsp<a href='../public/lodop6.164/install_lodop64.exe' target='_self'>这里执行升级</a>，升级后请重新进入系统。</font><br>";
var strHtmFireFox = "<font color='green'>注意：<br>1、如曾安装过Lodop旧版附件npActiveXPLugin，请在【工具】->【附加组件】->【扩展】中先卸它。</font><br>";
var LODOP = oEMBED;
var msg = "";
try{
	var isIE = (navigator.userAgent.indexOf('MSIE')>=0) || (navigator.userAgent.indexOf('Trident')>=0);
	var is64IE = isIE && (navigator.userAgent.indexOf('x64')>=0);
	if (isIE) LODOP = oOBJECT;
	if ((LODOP==null)||(typeof(LODOP.VERSION)=="undefined")) {
		if (navigator.userAgent.indexOf('Firefox')>=0){
			msg = msg + strHtmFireFox;
		}
		if (is64IE) {
			msg = msg + strHtm64_Install;
		} else {
			if (isIE) {
				msg = msg + strHtmInstall;
			} else {
				msg = msg + strHtmInstall;
			}
		}

	} else {
		if (LODOP.VERSION<"6.1.6.4") {
			if (is64IE){ 
				msg = msg + strHtm64_Update;
			} else {
				if (isIE) { 
					msg = msg + strHtmUpdate;
				} else {
					msg = msg + strHtmUpdate;
				}
			}

		}
		//=====如下空白位置适合调用统一功能:=====
		//=====================================

	}
}catch(err){
	if (is64IE)
		msg = msg + "Error:" + strHtm64_Install;
	else
		msg = msg + "Error:" + strHtmInstall;
    
}

if(msg != ""){
	art.dialog({
		title: '打印控件安装和升级',
	    content: msg,
	    cancel: true
	});
}else{
	return LODOP;
}

}


