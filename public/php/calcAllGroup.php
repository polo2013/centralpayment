<?php
/*
	$tileWide = array(1,2);
	while(true){
		$tileGroup = array();
		for($i=0; $i<10; $i++){
			$tileGroup[$i] = getRandomArrVal($tileWide);
		}
		
		$temp = sumArrVal($tileGroup);
		
	    //echo json_encode($tileGroup);
	    //echo $temp;
		if($temp == 15){
			if(validArrVal($tileGroup)){
				echo json_encode($tileGroup);
				break;
			}
		}
	}
*/
	//加快显示速度
	$tileGroup = array(1,2,1,1,2,2,1,1,2,2);
	echo json_encode($tileGroup);
	
	//确保每行是5的倍数
	function validArrVal($arr){
		$sumWide = 0;
		$tempsum = 0;
		
		for($i=0; $i<count($arr); $i++){
			if($sumWide < 5)
				$tempsum = 5;
			else if($sumWide >= 5 && $sumWide < 10)
				$tempsum = 10;
			else if($sumWide >= 10 && $sumWide < 15)
				$tempsum = 15;
			
			if($sumWide + $arr[$i] <= $tempsum){
				$sumWide = $sumWide + $arr[$i];
			}else{
				return false;
			}
		}
		return true;
	}
		
	function getRandomArrVal($arr){
		$min = 0;
		$max = count($arr)-1;
		return $arr[rand($min,$max)];
	}
	
	function sumArrVal($arr){
		$sumVal = 0;
		foreach ($arr as $val) {
			$sumVal = $sumVal + $val;
		}
		return $sumVal;
	}
?>