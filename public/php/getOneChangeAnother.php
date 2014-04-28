<?php
include_once("session.php");
$result = array();

$who2who = $_REQUEST['who2who'] ? $_REQUEST['who2who'] : "";
$oneValue = $_REQUEST['oneValue'] ? $_REQUEST['oneValue'] : "";

if ($who2who == "role2org"){
	$rolecode = splitCode($oneValue);
	$query = "SELECT * FROM sys_role WHERE `CODE` = '$rolecode'";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result['anotherValue'] = $row['ORG'];
	}
}

if ($who2who == "org2role"){
	$orgcode = splitCode($oneValue);
	$query = "SELECT * FROM sys_role WHERE `ORG` = '$oneValue'";
	$cursor = exequery($connection,$query);
	while($row = mysqli_fetch_array($cursor)){
		$result['anotherValue'] = '['.$row['CODE'].']'.$row['NAME'];
	}
}

echo json_encode($result);
?>