<?php
require_once './dbManager.php';

class GoodManager{

    public function goodcount($userId, $ressId,$goodDay){
		$num = intval($ressId);
		$DBM = new dbManager();
		$check = $DBM->goodCheck($userId, $num);
		$cnt = count($check);

		if($cnt == 0){
            $DBM->insertUserinfo($userId, $num, $goodDay);
			$DBM->insertcount($num);

		}
	}

	public function flag($userId, $ressId){
		$flag = 0;
		$num = intval($ressId);
		$DBM = new dbManager();
		$check = $DBM->goodCheck($userId, $num);
		$cnt = count($check);
		if($cnt == 2){
			$flag = 1;
		}
		return($flag);
	}

}


?>
