<?php
include_once("session.php");
$result = array();
$result_item = array();
$result_item_item = array();

$query = "SELECT * FROM sys_currency ORDER BY `ID`";
$cursor = exequery($connection,$query);
while($row = mysqli_fetch_array($cursor)){
	$result_item_item['value'] = $row['CURRENCY'];
	$result_item_item['text'] = $row['CURRENCY'];
	$result_item[] = $result_item_item;
}

$result['all'] = $result_item;
$result['my'] = $result_item[0]['value'];
echo json_encode($result);
?>