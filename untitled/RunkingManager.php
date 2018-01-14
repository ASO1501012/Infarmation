<?php
require_once './dbManager.php';

class RunkingManager{

	//過去一ヶ月の処理---------------------------------------------------
	public function runkingGetter2($categoryId, $goodDay){
		
		$category = intval($categoryId);
		$DBM = new dbManager();
		$check = $DBM->runkingGetter($category, $goodDay);
		return($check);
	}

	public function runkingThread2($t, $goodDay){
		$thread = intval($t[0]);
		$DBM = new dbManager();
		$check2 = $DBM->serchmonth($thread, $goodDay);
		return($check2);
		

	}
	//-----------------------------------------------------------------




	//過去一週間の処理----------------------------------------------------
	public function runkingGetter($categoryId, $goodDay){
		
		$category = intval($categoryId);
		$DBM = new dbManager();
		$check = $DBM->runkingGetter($category, $goodDay);
		return($check);
	}

	public function runkingThread($t, $goodDay){
		$thread = intval($t[0]);
		$DBM = new dbManager();
		$check2 = $DBM->threadserch($thread, $goodDay);
		return($check2);
		

	}
	//-------------------------------------------------------------------




}


?>
