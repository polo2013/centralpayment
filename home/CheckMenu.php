<?php
include_once("../public/php/session.php");
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$flag = $_REQUEST['flag'] ? $_REQUEST['flag'] : "";

$result = array();
$masterArray = array();
$detailArray = array();
$detailArrayItem = array();

if($flag == "all"){
	$query = "select DISTINCT TRIM(c.MENU) MENU from sys_modules c order by c.ORDERNO";
}else{
	$query = "select DISTINCT TRIM(c.MENU) MENU
	from sys_auth a, sys_modules_rights b, sys_modules c
	where a.RIGHTS = CONCAT('[',b.`CODE`,']',b.`NAME`)
	and b.MODULES = CONCAT('[',c.`CODE`,']',c.`NAME`)
	and a.ROLE = '$login_user_role' and b.`NAME` = '查看权' order by c.ORDERNO";
}
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	unset($masterArray);
	$masterArray['MENU'] = $row['MENU'];

	if($flag == "all"){
		$query2 = "select DISTINCT TRIM(c.CODE) CODE, TRIM(c.NAME) NAME, TRIM(c.URL) URL, TRIM(c.OBJ) OBJ, TRIM(c.NOTE) NOTE  
		from sys_modules c where c.MENU = '".$row['MENU']."' order by c.CODE";
	}else{
		$query2 = "select DISTINCT TRIM(c.CODE) CODE, TRIM(c.NAME) NAME, TRIM(c.URL) URL, TRIM(c.OBJ) OBJ, TRIM(c.NOTE) NOTE  
		from sys_auth a, sys_modules_rights b, sys_modules c 
		where a.RIGHTS = CONCAT('[',b.`CODE`,']',b.`NAME`)
		and b.MODULES = CONCAT('[',c.`CODE`,']',c.`NAME`)
		and a.ROLE = '$login_user_role' and c.MENU = '".$row['MENU']."' and b.`NAME` = '查看权' order by c.CODE";
	}
	$cursor2 = exequery($connection,$query2);
	unset($detailArray);
	while($row2 = mysqli_fetch_array($cursor2)){
		unset($detailArrayItem);
		$detailArrayItem['MCODE'] = $row2['CODE'];
		$detailArrayItem['MNAME'] = $row2['NAME'];
		$detailArrayItem['MURL'] = $row2['URL'];
		$detailArrayItem['MOBJ'] = $row2['OBJ'];
		$detailArrayItem['MNOTE'] = $row2['NOTE'];
		
		if($flag == "all"){
			$query3 = "select c.*
			from sys_auth a, sys_modules_rights b, sys_modules c
			where a.RIGHTS = CONCAT('[',b.`CODE`,']',b.`NAME`)
			and b.MODULES = CONCAT('[',c.`CODE`,']',c.`NAME`)
			and a.ROLE = '$login_user_role' and b.`NAME` = '查看权' and c.`CODE` = '".$row2['CODE']."'";
			$cursor3 = exequery($connection,$query3);
			if($row3 = mysqli_fetch_array($cursor3)){
				$detailArrayItem['HASRIGHT'] = true;
			}else{
				$detailArrayItem['HASRIGHT'] = false;
			}
		}
		
		
		$detailArray[] = $detailArrayItem;
	}
	$masterArray['MODULES'] = $detailArray;
	
	$result[] = $masterArray;
}

echo json_encode($result);
?>