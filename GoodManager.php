<?php
if(!isset($_SESSION)){
    session_start();
}
require_once './dbManager.php';
$goodDay = date("Y-m-d");
$userId = $_SESSION['userId'];
$ressId = $_GET['ressId'];
if(isset($_GET['flg'])){
	$flg = $_GET['flg'];
}
		
	if(isset($flg)){	
		$num = intval($ressId);
		$DBM = new dbManager();
		$check = $DBM->goodCheck($userId, $num);
		$cnt = count($check);
		if($cnt == 0){
            $DBM->insertUserinfo($userId, $num, $goodDay);
			$DBM->insertcount($num);

		}
	}
		$flag = 0;
		$flg2 = 0;
		$num = intval($ressId);
		$DBM = new dbManager();
		$check = $DBM->goodCheck($userId, $num);
		$cnt = count($check);
		if($cnt == 2){
			$flag = 1;
			$flag2 = 1;
		}
		echo json_encode($flag);
		return($flag2);
?>
