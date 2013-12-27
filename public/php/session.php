<?php
include_once("utility.php");

function checkSession()
{
	global $OFFLINE_TIME_MIN;

	session_start();  //如果没有发现session文件，则创建文件，id和数组；如果有现成的session文件，则依据文件创建数组

	if($OFFLINE_TIME_MIN>0)
	{
		if(isset($_COOKIE["LAST_OPERATION_TIME"]))
			$LAST_OPERATION_TIME=$_COOKIE["LAST_OPERATION_TIME"];
		else
			$LAST_OPERATION_TIME="";
		setcookie("LAST_OPERATION_TIME",time(),0,"/");
		if($LAST_OPERATION_TIME!="" && time()-$LAST_OPERATION_TIME > $OFFLINE_TIME_MIN*60)
		{
			session_unset();  //清除内存中的$_SESSION全局数组
			session_destroy(); //删除session文件和session id，但不清除内存中的$_SESSION全局数组
			return false;
		}
	}
	
	if(isset($_SESSION) and isset($_SESSION['LOGIN_USER'])){
			return true;
	}else{
		session_unset();
		session_destroy();
		return false;
	}
}

if(!checkSession()){
	echo "<title>用户未登录</title><body><center>用户未登录或已超时，请重新登录!&nbsp;&nbsp;<input type=\"button\" value=\"点击登录\" onclick=\"window.top.location='".$HTTP_ROOT_PATH."login';\"></center></body>";
	exit;
}
?>