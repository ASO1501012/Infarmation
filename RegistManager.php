<?php
require_once './dbManager.php';


class RegistManager{

    public function userSerch($userId, $favoriteUserId){

		$DBM = new dbManager();
		$check = $DBM->userCheck($userId, $favoriteUserId);
		$cnt = count($check);

        //checkにユーザーIDとレスIDが挿入されているかの判定
		if($cnt == 0){
            //ユーザーIDとレスID、日付をDBGoodTblに書き込む
            $DBM->insertUInfo($userId, "1");

		}
	}

	public function flg($userId, $favoriteUserId){

		$DBM = new dbManager();
		$check = $DBM->checkColor($userId, $favoriteUserId);
		$cnt = count($check);
		return($cnt);
	}




}

?>
