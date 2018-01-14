<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "dbManager.php";

class noticeManager{
    //レス情報を取得
    public function getNoticeRessInf($userId){
        $db = new dbManager();
        //そのレスIDが親IDに指定されているレスのIDと件数を取得する
        $result = $db->getNoticeRessInf($userId);
        //noticeRessInfに存在しているレスIDのparentIdを取得
        $result = $db->noticeInf($result);
        //parentIdを元に条件をかけて、表示するレス情報を取得:ressId・ressText
        //$result = $db->noticeRess($result);
        
        return $result;
    }
    
    //取得してきたレスの件数分、noticePage.phpで表示させる。45文字
    public function displayNotice($result){
        $db = new dbManager();
        $sec = new sec();
        if(!empty($result)){
            foreach($result as $result){
                $threadResult = $db->getThreadInf($result->threadId);
                if(strlen($result->ressText) >= 45){
                    $ressText = substr($result->ressText,0,90) . "...";
                }
                $kekka = ($threadResult[0]->threadTitle."ID:".$result->ressId." ".$ressText."　"."書込み日時"." ".$result->rightDay);
                echo '<a href="http://localhost/jk2/zenkikaihatu/main.php?ressId='.$result->ressId.'">'.$sec->hsc($kekka).'</a>'.nl2br("\n").nl2br("\n");
            }
        }else{
            echo "新しい通知はありません";
        }
    }
    
    public function updataNoticeInf($ressId){
        $db = new dbManager();
        $db->updataNoticeInf($ressId);
    }
    
    public function getThreadInf($threadId){
        $db = new dbManager();
        $threadResult = $db->getThreadInf($threadId);
        return $threadResult;
    }
    
    public function getUserInf($userId){
        $db = new dbManager();
        $userResult = $db->getUserInf($userId);
        return $userResult;
    }
}

?>