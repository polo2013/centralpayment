<?php
include_once("conn.php");

function get_microsecond()
{
	list($usec, $sec) = explode(" ", microtime());
	$micro = substr((string)((float)$usec * 1000), 0, 3);
	//3位微妙
	//return $micro;
	//当前日期+3位微妙
	return date('YmdHis',$sec).$micro;
}

function writeDBLog($oper, $obj, $type, $msg, $note = ""){
	global $connection;
	$query = "INSERT INTO SYS_LOG(OPER, OBJ, OPERTYPE, CONTENT, NOTE) VALUES ('$oper', '$obj', '$type', '$msg', '$note')";
	exequery($connection, $query);
}
function splitCode($allInOne){
	$start = strpos($allInOne, '[') + 1;
	$end = strpos($allInOne, ']');
	$len = $end - $start;
	return substr($allInOne, $start, $len);
}
function splitName($allInOne){
	$start = strpos($allInOne, ']') + 1;
	return substr($allInOne, $start);
}

//获取模块权限
function getAuthInfo($role, $module, $auth){
	global $connection;
	$result = array();
	if($auth == 'allauth'){
		$query = "select * from sys_auth where ROLE in ( '$role' ) and SUBSTRING(RIGHTS,2,3) = '$module' order by RIGHTS";
		$cursor = exequery($connection,$query);
		while($row = mysqli_fetch_array($cursor)){
			$result[] = substr($row['RIGHTS'],strpos($row['RIGHTS'], ']')+1);
		}
		return $result;
	}else{
		$query = "select * from sys_auth where ROLE in ( '$role' ) and SUBSTRING(RIGHTS,2,3) = '$module' and SUBSTRING(RIGHTS,LOCATE(']',RIGHTS)+1) = '$auth'";
		$cursor = exequery($connection,$query);
		if($row = mysqli_fetch_array($cursor)){
			return true;
		}else{
			return false;
		}
	}
}

//读取配置
function readSetting($type, $name){
	global $connection;
	$query = "select * from sys_setting where STYPE = '$type' and SNAME = '$name' ";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		return $row['SVALUE'];
	}else{
		return false;
	}
}

//是否含有
function hasSpec($str, $spec){
	//$str 类似于  xxx,xxx,xxx 这样的字符串
	$pos = stripos($str, $spec);
	if ($pos === false) {
		return false;
	}else{
		return true;
	}
	
}

//获取单据下一状态
function getNextSTAT($OPERATION){
	global $connection;
	$query = "SELECT * FROM SYS_STAT WHERE FIREACT = '$OPERATION'";
	$cursor = exequery($connection,$query);
	if($row = mysqli_fetch_array($cursor)){
		return $row['NAME'];
	}else{
		return '未知状态';
	}
}

//检查数据合法性
function checkValueAtDB($module, $request, $type)
{
	global $connection;
	$result = array();
	$query = "";
	$errmsg = "";

	switch ($module)
	{
		case "001":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM SYS_MODULES WHERE NAME = '".$request['NAME']."' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = '已存在名称为“'.$row['NAME'].'”的模块，代码为“'.$row['CODE'].'”，请修改名称！';
				}
			}else if($type=="remove"){
				$query = "SELECT A.* FROM SYS_AUTH A, SYS_MODULES_RIGHTS B, SYS_MODULES C "
				."WHERE A.RIGHTS = CONCAT('[',B.`CODE`,']',B.`NAME`) "
				."AND B.MODULES = CONCAT('[',C.`CODE`,']',C.`NAME`) "
				."AND C.`CODE`='".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "角色“".$row['ROLE']."”拥有该模块的权限，不能删除使用中的模块！";
				}
			}
			break;
		case "moduleRights":
			if($type=="save"){
				$errmsg = "";
			}
			break;
		case "002":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM SYS_STAT WHERE NAME = '".$request['NAME']."' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = '已存在名为“'.$row['NAME'].'”的状态，代码为“'.$row['CODE'].'”，请修改！';
				}else{
					$query = "SELECT * FROM SYS_STAT WHERE FIREACT = '".$request['FIREACT']."' AND CODE != '".$request['CODE']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = '已存在名为“'.$row['FIREACT'].'”的动作，代码为“'.$row['CODE'].'”，请修改！';
					}
				}
			}else if($type=="remove"){
				$query = "SELECT A.* FROM ZDCW_PAYMENT_MASTER A, SYS_STAT B WHERE A.STAT = B.`NAME` AND B.`CODE`='".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "状态“".$row['STAT']."”被单据所使用，不能删除！";
				}
			}
			break;
		case "003":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM SYS_ORG WHERE NAME = '".$request['NAME']."' AND PARENT = '".$request['PARENT']."' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "在机构“".$row['PARENT']."”下已存在同名机构“".$row['NAME']."”，代码为“".$row['CODE']."”，请修改名称！";
				}
			}else if($type=="remove"){
				$query = "SELECT A.* FROM SYS_ORG A, SYS_USER B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ORG A, SYS_ROLE B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ORG A, BIZ_PAYEE B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ORG A, BIZ_PROJECT B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ORG A, ZDCW_PAYMENT_DETAIL B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ORG A, ZDCW_PAYMENT_MASTER B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' ";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "机构“[".$row['CODE']."]".$row['NAME']."”被基本资料或历史单据所使用，不能删除！";
				}
			}
			break;
		case "004":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM SYS_ROLE WHERE NAME = '".$request['NAME']."' AND ORG = '[001]正大集团' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "在机构“".$row['ORG']."”下已存在同名角色“".$row['NAME']."”，代码为“".$row['CODE']."”，请修改角色名称！";
				}
			}else if($type=="remove"){
				$query = "SELECT A.* FROM SYS_ROLE A, SYS_AUTH B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ROLE AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_ROLE A, SYS_USER B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ROLE AND A.`CODE`='".$request['CODE']."' ";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "角色“[".$row['CODE']."]".$row['NAME']."”拥有权限，或拥有从属人员，请取消权限或从属人员后再删除！";
				}
			}
			break;
		case "005":
			$errmsg = "";
			if($type=="save"){
				$errmsg = "";
			}
			break;
		case "006":
			$errmsg = "";
			if($type=="add"){
				$query = "SELECT * FROM SYS_USER WHERE CODE = '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "该用户名“".$row['CODE']."”已被使用，请使用其他用户名！";
				}
				if($request['ACCOUNT'] != '' && $request['BANK'] == ''){
					$errmsg = "您填写了账号，但没有填写银行！";
				}
				if($request['ACCOUNT'] == '' && $request['BANK'] != ''){
					$errmsg = "您填写了银行，但没有填写账号！";
				}
				/*
				if($request['ACCOUNT'] != ''){
					$query = "SELECT * FROM SYS_USER WHERE ACCOUNT = '".$request['ACCOUNT']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被用户“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
					$query = "SELECT * FROM BIZ_PAYEE WHERE ACCOUNT = '".$request['ACCOUNT']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被收款人“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
				}
				*/
			}else if($type=="edit"){
				$errmsg = "";
				if($request['ACCOUNT'] != '' && $request['BANK'] == ''){
					$errmsg = "您填写了账号但没有填写银行！";
				}
				if($request['ACCOUNT'] == '' && $request['BANK'] != ''){
					$errmsg = "您填写了银行但没有填写账号！";
				}
				/*
				if($request['ACCOUNT'] != ''){
					$query = "SELECT * FROM SYS_USER WHERE ACCOUNT = '".$request['ACCOUNT']."' AND CODE != '".$request['CODE']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被用户“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
					$query = "SELECT * FROM BIZ_PAYEE WHERE ACCOUNT = '".$request['ACCOUNT']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被收款人“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
				}
				*/
			}else if($type=="remove"){
				$query = "SELECT A.* FROM SYS_USER A, ZDCW_PAYMENT_MASTER B "
				."WHERE (CONCAT('[',A.`CODE`,']',A.`NAME`) = B.INPUTTER "
				."OR CONCAT('[',A.`CODE`,']',A.`NAME`) = B.CHECKER "
				."OR CONCAT('[',A.`CODE`,']',A.`NAME`) = B.APPROVER ) AND A.`CODE`='".$request['CODE']."' "
				."UNION "
				."SELECT A.* FROM SYS_USER A, ZDCW_PAYMENT_DETAIL B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.APPLICANT AND A.`CODE`='".$request['CODE']."' ";

				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "该用户“[".$row['CODE']."]".$row['NAME']."”在历史单据中被使用，不能删除！";
				}
				//$errmsg = "用户一旦创建就不允许删除，您可以禁用该用户！";
			}
			break;
		case "007":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM BIZ_PROJECT WHERE NAME = '".$request['NAME']."' AND ORG = '".$request['ORG']."' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "在机构“".$row['ORG']."”下已存在同名项目“".$row['NAME']."”，代码为“".$row['CODE']."”，请修改名称！";
				}
			}else if($type=="remove"){
				$query = "SELECT A.* FROM BIZ_PROJECT A, ZDCW_PAYMENT_DETAIL B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.ORG AND A.`CODE`='".$request['CODE']."' ";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "项目“[".$row['CODE']."]".$row['NAME']."”在历史单据中被使用，不能删除！";
				}
			}
			break;
		case "008":
			$errmsg = "";
			if($type=="add" or $type=="edit"){
				$query = "SELECT * FROM BIZ_PAYEE WHERE NAME = '".$request['NAME']."' AND ORG = '".$request['ORG']."' AND ACCOUNT = '".$request['ACCOUNT']."' AND CODE != '".$request['CODE']."'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "在机构“".$row['ORG']."”下已存在同名收款人“".$row['NAME']."”，且银行账号一样，代码为“".$row['CODE']."”！";
				}
				if($request['ACCOUNT'] != '' && $request['BANK'] == ''){
					$errmsg = "您填写了账号，但没有填写银行！";
				}
				if($request['ACCOUNT'] == '' && $request['BANK'] != ''){
					$errmsg = "您填写了银行，但没有填写账号！";
				}
				/*
				if($request['ACCOUNT'] != ''){
					$query = "SELECT * FROM BIZ_PAYEE WHERE ACCOUNT = '".$request['ACCOUNT']."' AND CODE != '".$request['CODE']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被收款人“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
					$query = "SELECT * FROM SYS_USER WHERE ACCOUNT = '".$request['ACCOUNT']."'";
					$cursor = exequery($connection,$query);
					if($row = mysqli_fetch_array($cursor)){
						$errmsg = "该账号“".$row['ACCOUNT']."”已被用户“[".$row['CODE']."]".$row['NAME']."”使用，请确认是否填写正确！";
					}
				}
				*/
			}else if($type=="remove"){
				$query = "SELECT A.* FROM BIZ_PAYEE A, ZDCW_PAYMENT_DETAIL B "
				."WHERE CONCAT('[',A.`CODE`,']',A.`NAME`) = B.PAYEE AND A.`CODE`='".$request['CODE']."' ";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "收款人“[".$row['CODE']."]".$row['NAME']."”在历史单据中被使用，不能删除！";
				}
			}
			break;
		case "009":
			$errmsg = "";
			$uniqueArray = array();
			$billPaystatArray = array();
			$ORG = $_REQUEST['ORG'] ? $_REQUEST['ORG'] : "";
			$BILLNUM = $_REQUEST['BILLNUM'] ? $_REQUEST['BILLNUM'] : "";
			$PAYMENTROWS = $_REQUEST['PAYMENTROWS'] ? $_REQUEST['PAYMENTROWS'] : "";
			$OPERATION = $_REQUEST['OPERATION'] ? $_REQUEST['OPERATION'] : "";

			//查找历史中是否重复，本组织中付款汇总表编号唯一
			if($errmsg == "" && $type == "new"){
				$query = "SELECT * FROM ZDCW_PAYMENT_MASTER WHERE ORG = '$ORG' AND BILLNUM = '$BILLNUM'";
				$cursor = exequery($connection,$query);
				if($row = mysqli_fetch_array($cursor)){
					$errmsg = "在机构“".$ORG."”下已存在编号为“".$BILLNUM."”的付款汇总表，请勿重复录入!";
				}
			}
			
			//解码成数组
			$PAYMENTROWSARR = json_decode($PAYMENTROWS,TRUE);
			if($PAYMENTROWSARR != NULL){
				foreach($PAYMENTROWSARR as $key => $value){
					$uniqueArray[] = '申请人：'.$value['APPLICANT']
					.'<br>付款事由：'.$value['PAYMENT']
					.'<br>金额：'.$value['TOTALAMT']
					.'<br>账号：'.$value['ACCOUNT'];
					$billPaystatArray[] = $value['PAYSTAT'];
				}
				$lenbillnum = count ($uniqueArray);
				$lenbillPay = count ($billPaystatArray);
				
				//查找当前录入是否重复
				if($errmsg == "" && $type == "new"){
					for($i = 0; $i < $lenbillnum; $i ++) {
						for($j = $i + 1; $j < $lenbillnum; $j ++) {
							if ($uniqueArray[$i] == $uniqueArray[$j]) {
								$errmsg = "当前单据中存在重复录入的付款申请，重复信息为：<br>".$uniqueArray[$i];
								break 2;
							}
						}
					}
				}
				
				//是否全部已付款
				if($errmsg == "" && $type == "view" && $OPERATION == '付款完成'){
					for($i = 0; $i < $lenbillPay; $i ++) {
						if($billPaystatArray[$i] == '未付款'){
							$errmsg = "尚有未付款的数据，不能完成!";
							break 1;
						}
					}
				}
				//没有未付款的情况下不允许付款不通过
				if($errmsg == "" && $type == "view" && $OPERATION == '付款不通过'){
					$j = true;
					for($i = 0; $i < $lenbillPay; $i ++) {
						if($billPaystatArray[$i] == '未付款'){
							$j = false;
							break;
						}
					}
					if($j){
						$errmsg = "已经不存在未付款的数据，该动作没有意义!";
					}
				}
			}else{//没有任何明细
				$errmsg = "明细不允许为空!";
			}
			
			break;
		default:
			$errmsg = "检查（checkValueAtDB）不通过，此模块无定义，请联系管理员！";
	};
	if ($errmsg != ""){
		//有结果，表示有问题
		writeLog('checkValueAtDB : errmsg='.$errmsg, "info");
		$result['ok'] = false;
		$result['msg'] = $errmsg;
	}else{
		//没结果，表示没问题
		$result['ok'] = true;
		$result['msg'] = $errmsg;
	}

	return $result;
}

?>