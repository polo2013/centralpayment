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


switch($PARA)
{
	case 'ME':
		$result['me'] = $login_user;
		break;
	case 'APPLICANT':
	case 'PAYEE':
		$result['me'] = $login_user;
		$query = "select * from SYS_USER WHERE CHECKSTAT = '已复核' AND SUBSTR(`ORG`,2,LENGTH('$orgCode')) = '$orgCode' ORDER BY ORDERNO, `CODE`";
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
		
}

echo json_encode($result);
?>