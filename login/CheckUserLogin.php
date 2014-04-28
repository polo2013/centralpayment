<?php
include_once("../public/php/conn.php");
$result = array();
$loginUserCode = $_POST['ucode'];
$loginPwd = $_POST['upwd'];

$query = "SELECT * FROM SYS_USER WHERE CODE = '$loginUserCode'";
$cursor= exequery($connection,$query);
if($ROW=mysqli_fetch_array($cursor)){
	$loginUser = '['.$loginUserCode.']'.$ROW['NAME'];
	
	if($loginPwd == $ROW['PASSWD']){
		if($ROW['STAT'] === "启用"){
			isset($_SESSION) || session_start();
			if(isset($_SESSION['LOGIN_USER']) && $_SESSION['LOGIN_USER']!=$loginUser){
				$result['success'] = false;
				$result['info'] = "当前用户：“".$_SESSION['LOGIN_USER']."”未注销。请重新打开浏览器登录！";
			}else{
				$_SESSION['LOGIN_USER_CODE'] = $loginUserCode;
				$_SESSION['LOGIN_USER_NAME'] = $ROW['NAME'];
				$_SESSION['LOGIN_USER'] = $loginUser;
				$isMore = stripos($ROW['ORG'],'|');
				if($isMore){
					$orgArr = explode("|",$ROW['ORG']);
					$roleArr = explode("|",$ROW['ROLE']);
					$_SESSION['LOGIN_USER_ORG'] = $orgArr[0];
					$_SESSION['LOGIN_USER_ROLE'] = $roleArr[0];
				}else{
					$_SESSION['LOGIN_USER_ORG'] = $ROW['ORG'];
					$_SESSION['LOGIN_USER_ROLE'] = $ROW['ROLE'];
				
				}
				$result['success'] = true;
			}
		}else{
			$result['success'] = false;
			$result['info'] = "该用户没有启用，请联系管理员！";
		}
		
	}else{
		$result['success'] = false;
		$result['info'] = "您输入的密码不正确！";
	}
}else{
	$result['success'] = false;
	$result['info'] = "不存在该用户！";
}


//$result['loginUser'] = $loginUser;
//$result['loginPwd'] = $loginPwd;

//sleep(2);
echo json_encode($result);
?>