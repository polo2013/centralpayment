<?php
include_once("session.php");
$login_user_code = $_SESSION['LOGIN_USER_CODE'];
$login_user_name = $_SESSION['LOGIN_USER_NAME'];
$login_user = $_SESSION['LOGIN_USER'];
$login_user_role = $_SESSION['LOGIN_USER_ROLE'];
$login_user_org = $_SESSION['LOGIN_USER_ORG'];

$result = array();
$result_item = array();
$result_item_item = array();


$PARA = $_REQUEST['PARA'];
$PARA2 = $_REQUEST['PARA2'];
$orgCode = splitCode($login_user_org);

//取额外可以显示的人
$another = array();
$query = "select * from zdcw_another_people WHERE `ORG` = '$orgCode' and OTYPE = 'people'";
$cursor = exequery($connection,$query);
while ($row = mysqli_fetch_array($cursor)){
	$another[] = $row;
}
//end

switch($PARA)
{
	case 'ME':
		$result['me'] = $login_user;
		break;
	case 'APPLICANT':
	case 'PAYEE':
		$result['me'] = $login_user;
		
		$another_str = '';
		
		if ($another) {
			foreach ($another as $val){
				$another_str .= $val['ANOTHER']."','";
			}
			$another_str = rtrim($another_str,"','");
		}
		
		
		$query = "select * from SYS_USER WHERE CHECKSTAT = '已复核' AND (`ORG` like '%[$orgCode%' OR `CODE` in ('".$another_str."')) ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		while ($row = mysqli_fetch_array($cursor)){
			$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
			$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
			$result_item[] = $result_item_item;
		}
		if($PARA == "PAYEE"){
			$query = "select * from BIZ_PAYEE WHERE CHECKSTAT = '已复核' AND SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY ORDERNO, `CODE`";
			$cursor = exequery($connection,$query);
			while ($row = mysqli_fetch_array($cursor)){
				$result_item_item['value'] = '['.$row['CODE'].']'.$row['NAME'];
				$result_item_item['text'] = '['.$row['CODE'].']'.$row['NAME'];
				$result_item[] = $result_item_item;
			}
		}
		$result['all'] = $result_item;
		break;
	case 'BANK':
		$query = "select * from SYS_USER WHERE CONCAT('[',`CODE`,']',`NAME`) = '$PARA2' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		if ($row = mysqli_fetch_array($cursor)){
			$result['bank'] = $row['BANK'];
			$result['account'] = $row['ACCOUNT'];
		}
		$query = "select * from BIZ_PAYEE WHERE CONCAT('[',`CODE`,']',`NAME`) = '$PARA2' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		if ($row = mysqli_fetch_array($cursor)){
			$result['bank'] = $row['BANK'];
			$result['account'] = $row['ACCOUNT'];
		}
		break;
	case 'IMPOA_BANK':
		$query = "select * from SYS_USER WHERE `NAME` = '$PARA2' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		if ($row = mysqli_fetch_array($cursor)){
			$result['bank'] = $row['BANK'];
			$result['account'] = $row['ACCOUNT'];
		}
		$query = "select * from BIZ_PAYEE WHERE `NAME` = '$PARA2' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		if ($row = mysqli_fetch_array($cursor)){
			$result['bank'] = $row['BANK'];
			$result['account'] = $row['ACCOUNT'];
		}
		break;
	case 'IMPOA_PAYEE':
		$query = "select * from SYS_USER WHERE CHECKSTAT = '已复核' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		while ($row = mysqli_fetch_array($cursor)){
			$result_item_item['value'] = $row['NAME'];
			$result_item_item['text'] = $row['NAME'];
			$result_item[] = $result_item_item;
		}
		$query = "select * from BIZ_PAYEE WHERE CHECKSTAT = '已复核' ORDER BY ORDERNO, `CODE`";
		$cursor = exequery($connection,$query);
		while ($row = mysqli_fetch_array($cursor)){
			$result_item_item['value'] = $row['NAME'];
			$result_item_item['text'] = $row['NAME'];
			$result_item[] = $result_item_item;
		}
		
		$result['all'] = $result_item;
		break;
}

echo json_encode($result);
?>