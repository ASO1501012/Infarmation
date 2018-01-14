<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 

require_once "dbManager.php";
require_once "sec.php";

class ressManager{
    public function ressCreate($ressId,$ressText,$parentId,$threadId,$userId,$rightDay,$goodCnt){
        $db = new dbManager();
        $ressId = intval($ressId);
        $parentId = intval($parentId);
        $threadId = intval($threadId);
        $goodCnt = intval($goodCnt);
        $db->ressCreate($ressId,$ressText,$parentId,$threadId,$userId,$rightDay,$goodCnt);
        $result = $db->noticeToUser($parentId);
        var_dump($result);
        $db->noticeRessInfCreate($result,$userId,$ressId);
    }
    
    public function getRessInf($userId){
        $db = new dbManager();
        $result = $db->getRessInf($userId);
        return $result;
    }
    
    //取得してきたレスの件数分、clipBoardInfで表示させる。
    public function displayRess($getResult){
        $sec = new sec();
        $dbm = new dbManager();
        if(!empty($getResult)){
            foreach($getResult as $getResult){
                $result = $dbm->getRessThreadInf($getResult->ressId);
                $kekka = ("ID:".$getResult->ressId." ".$getResult->ressText." "."書き込み日時:".$getResult->rightDay);
                echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$result[0]->threadId.'">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
            }
        }else{
            echo "投稿されているレスはありません。";
        }
    }
    public function ResponseContribution($resContent, $threadId, $delPass){
        if(isset($_SESSION["userId"])){
            $userId = $_SESSION["userId"];
        }else{
            $userId = 'null3';
        }
        $db = new dbManager();
        $insrt = $db->RegistResponse($resContent, $threadId, $userId,$delPass);
    }

    public function DeleteResponse($ressId){
        $db = new dbManager();
        $delete = $db->deleteResponse($ressId);
    }

}