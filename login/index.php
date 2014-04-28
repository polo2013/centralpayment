<?php
include_once("../public/php/myConfig.php");
?>
<!DOCTYPE html>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo $SYS_TITLE; ?></title>
		<link rel="shortcut icon" href="../favicon.ico" />
		
		<!-- jquery -->
		<script src="../public/jQuery/jquery-1.10.2.js"></script>
		
		<!-- md5加密 -->
		<script src="../public/jQuery/jquery.md5.js"></script>
		
		<!-- json2string & string2json -->
		<script src="../public/jQuery/json2.js"></script>
		
		<!-- 对话框插件 -->
		<script src="../public/artDialog4.1.7/artDialog.js?skin=default"></script>
		
		<!-- loading插件 -->
		<link rel="stylesheet" type="text/css" href="../public/showLoading/css/showLoading.css" />
		<script src="../public/showLoading/js/jquery.showLoading.min.js"></script>
		
		<!-- 冒泡插件 -->
		<link rel="stylesheet" href="../public/grumble.js-master/css/grumble.min.css?v=5">
		<script src="../public/grumble.js-master/js/jquery.grumble.min.js?v=7"></script>

		<!-- 公用js -->
		<script src="../public/js/public.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<script src="../home/public.js"></script>
		<script src="js/index.js"></script>
		
    </head>
    <body>
		<div id="system_login_title">
			<p><?php echo $SYS_TITLE; ?></p>
		</div>
		
		<div class="container">
			<form class="form">
			    <p class="clearfix">
			        <label for="login">用户：</label>
			        <input type="text" name="login" id="login" placeholder="请输入用户名">
			    </p>
			    <p class="clearfix">
			        <label for="password">密码：</label>
			        <input type="password" name="password" id="password" placeholder="请输入密码"> 
			    </p>
			    <p class="clearfix">
			        
			    </p>
			    <p class="clearfix">
			        <input type="button" name="submit" id="submit" value="登  录">
			    </p>       
			</form>
		</div>
    </body>
</html>