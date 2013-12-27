<?php
include_once("myConfig.php");

function writeLog($msg, $type)
{
	global $ROOT_PATH, $TURN_LOG_ON;
	if ($TURN_LOG_ON){
		$SCRIPT_FILENAME = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		$LOG_PATH = realpath($ROOT_PATH.'logs');
		if(file_exists($LOG_PATH) && is_writable($LOG_PATH))
		{
			$DATA  = date("[Y-m-d H:i:s]")."\r\n";
			$DATA .= $msg."\r\n";
			$DATA .= $SCRIPT_FILENAME."\r\n";
			$DATA .= "\r\n";
	
			$LOG_FILE = $LOG_PATH."/".$type."_".date("Ymd").".log";
			$FP = @fopen($LOG_FILE, 'a');
			if($FP)
			{
				fwrite($FP, $DATA);
				fclose($FP);
			}
		}
	}
}

function OpenConnection()
{
	global $connection, $MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB, $MY_DB_CHARSET;

	if(!$connection)
	{
		$connection = mysqli_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);
		if(!$connection)
		{
			writeLog("不能连接到MySQL数据库，请检查：1、MySQL服务是否启动；2、MySQL被防火墙阻止；3、连接MySQL的用户名和密码是否正确；4、数据库名是否正确。", "error");
			exit;
		}

		mysqli_query($connection, "SET NAMES $MY_DB_CHARSET");
		
	}

	return $connection;
}

function exequery($C, $Q)
{
	global $MY_DB_CHARSET;
	if (!$C)
	{
		writeLog("无效的数据库连接。", "error");
		return false;
	}
	if($Q == ""){
		return false;
	}
	
	//开始事务
	if(mysqli_begin_transaction($C)){		
		$cursor = mysqli_query($C, $Q);
		writeLog($Q, 'info');
		
		if($cursor){
			mysqli_commit($C);  //提交事务
			return $cursor;
		}else{
			writeLog("执行语句失败。SQL语句：\r\n".$Q, "error");
			writeLog("错误信息：".mysqli_error($C), "error");
			mysqli_rollback($C);  //失败回滚，一定要放在mysqli_error的后面，因为mysqli_error记录最近一个语句的错误。
			return false;
		}
	}else{
		return false;
	}
}

function exeMutiQuery($C, $Q)
{
	global $MY_DB_CHARSET;
	if (!$C)
	{
		writeLog("无效的数据库连接。", "error");
		return false;
	}
	if($Q == ""){
		return false;
	}
	
	//开始事务
	if(mysqli_begin_transaction($C)){
		writeLog("mysqli_begin_transaction", 'muti');
		//多条语句，当第一条语句成功时，就返回true。
		$cursor = mysqli_multi_query($C, $Q);
		writeLog($Q, 'muti');
		
		$flag = 'ok';
		if($cursor){
			//是否有更多的结果集，如果有，则用next访问，用store来取得。
			while (mysqli_more_results($C)) {
				if(mysqli_next_result($C)){
					$rt = mysqli_store_result($C);
					//如果仅仅是insert等没有结果集的操作，mysqli_store_result返回false，则mysqli_free_result无法接受。
					//mysqli_free_result($rt);
				}else{
					$flag = 'failure';
					break;
				}
			}
		}else{
			$flag = 'failure';
		}

		if ($flag == 'ok'){
			mysqli_commit($C);  //提交事务
			writeLog("mysqli_commit", 'muti');
			return true;
		}else{
			writeLog("mysqli_rollback", 'muti');
			writeLog("执行语句失败。SQL语句：\r\n".$Q, "error");
			writeLog("错误信息：".mysqli_error($C), "error");
			mysqli_rollback($C);//失败回滚
			return false;
		}
	}else{
		return false;
	}

}

OpenConnection();
?>