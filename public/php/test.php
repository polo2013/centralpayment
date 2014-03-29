<?php 

echo date('Ymd',time());
echo "<br/>";
echo date('Y-m-d H:i:s',time());
echo "<br/>";
echo date('YmdHis',time());
echo "<br/>";

echo time();
echo "<br/>";

echo microtime();
echo "<br/>";
echo "<br/>";
echo "<br/>";

function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}


$time_start = microtime_float();

// Sleep for a while
usleep(100);

$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Did nothing in $time seconds";
echo "<br/>";
echo "<br/>";
echo "<br/>";

function get_microsecond()
{
	list($usec, $sec) = explode(" ", microtime());
	$micro = substr((string)((float)$usec * 1000), 0, 3);
	//3位微妙
	//return $micro;
	//当前日期+3位微妙
	return date('YmdHis',$sec).$micro;
}
echo get_microsecond();

?>