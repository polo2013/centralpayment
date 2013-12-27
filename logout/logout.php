<?php
include_once("../public/php/head.php");
include_once("../public/php/session.php");

session_unset();  //清除内存中的$_SESSION全局数组
session_destroy(); //删除session文件和session id，但不清除内存中的$_SESSION全局数组

header("Location: ".$HTTP_ROOT_PATH."login");
//确保重定向后，后续代码不会被执行
exit;
?>