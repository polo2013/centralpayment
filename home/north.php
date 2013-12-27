<?php
include_once("../public/php/session.php");
//echo $SYS_TITLE;
?>
<style type="text/css">
/*整个top north部分*/
#home_top_bar_table
{
	background: #563c55 url(../public/images/blurred.jpg) no-repeat center top;
	background-size: cover;
	height:100%;
	width:100%;
}
/*logo*/
#logo_div
{
	float:left;
}
#logo_gif
{
	width:100px;
	height:50px;
}

/*菜单条*/
#all_menu_in_one
{
	padding:5px;
	float:right;
	background-color:#7FFF00;
	position: relative;
	right: 15%;
}
/*主菜单按钮*/
a[id ^= "menu_"]
{
	margin: 0px 10px;
}
/*关于*/
#submenu_about
{
	width:200px;
	padding:10px;
	background-color: #7FFF00;
	border:0px;
}

/*用户信息条*/
.home_top_bar_userinfo{
	margin-left:30px;
	float:left;
}
/*注销按钮*/
#logout_btn
{
	float:right;
	margin-right:30px;
	width:7em;
	background-color:purple;
	color:white;
	text-decoration:none;
	text-align:center;
	height:30px;
	vertical-align:middle;
	line-height:30px;
	position: relative;
	top: -45px;
}
#logout_btn:hover {
	background-color:#ff3300
}

</style>

<table id="home_top_bar_table">
<tr>
	<td>
		<div id="all_menu_in_one"></div>
	</td>
</tr>

<tr>
	<td style="background:green;height:30px;color:white;valign:'bottom';">
		<div class="home_top_bar_userinfo">当前用户：<?=$_SESSION['LOGIN_USER']?></div>
		<div class="home_top_bar_userinfo">所属机构：<?=$_SESSION['LOGIN_USER_ORG']?></div>
		<div class="home_top_bar_userinfo">所属角色：<?=$_SESSION['LOGIN_USER_ROLE']?></div>
	</td>
</tr>
</table>
<a id="logout_btn" href="javascript:void(0)" onclick="logout();">注销</a>