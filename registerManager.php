<?php

require_once './dbManager.php';


class registerManager{


    public function deleteUserSerch($userId, $favoriteUserId){

        $dbm = new dbManager();
        $check = $dbm->userCheck($userId, $favoriteUserId);
        $cnt = count($check);
        //checkにユーザーIDとレスIDが挿入されているかの判定
        if($cnt >= 1){
            //ユーザーIDとレスIDをけす
            $dbm->deleteUserInfo($userId, $favoriteUserId);

        }
    }


    public function userList($userId){
        $dbm = new dbManager();
        $List = $dbm->userList($userId);
        return($List);
    }
    
    public function getUserInf($userId){
        $dbm = new dbManager();
        $result = $dbm->getUserInf($userId);
        return($result);
    }

    public function checkColor($userId, $favoriteUserId){

        $dbm = new dbManager();
        $check = $dbm->checkColor($userId, $favoriteUserId);
        $cnt = count($check);
        return($cnt);
    }

}

?>