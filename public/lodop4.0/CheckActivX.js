function CheckLodop(){
	var oldVersion=LODOP.Version;
	var msg = '';
	var newVerion="4.0.0.2";
	if (oldVersion==null){
		msg = msg + "<h3><font color='#FF00FF'>打印控件未安装!点击这里<a href='../public/lodop4.0/install_lodop.exe'>执行安装</a>,安装后请重新登录系统。</font></h3>";
		if (navigator.appName=="Netscape")
			msg = msg + "<h3><font color='#FF00FF'>（Firefox浏览器用户需先点击这里<a href='../public/lodop4.0/npActiveX0712SFx31.xpi'>安装运行环境</a>）</font></h3>";
	} else if (oldVersion<newVerion)
		msg = msg + "<h3><font color='#FF00FF'>打印控件需要升级!点击这里<a href='../public/lodop4.0/install_lodop.exe'>执行升级</a>,升级后请重新登录系统。</font></h3>";
	
	return msg;
}

