<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once 'dbManager.php';
require_once "sec.php";

class clipBoardManager{
    //新規フォルダー作成
    public function registerFolder($userId,$folderId,$folderName){
        $dbm = new dbManager();
        $dbm->registerFolder($userId,$folderId,$folderName);
    }
    
    //表示させるフォルダー情報を取得する
    public function loadFolder($userId){
        $dbm = new dbManager();
        $loadResult = $dbm->loadFolder($userId);
        return $loadResult;
    }
    
    //clipBoardInfで表示させるフォルダに入っているRessIdを取得する
    public function loadRess($folderId){
        $dbm = new dbManager();
        $loadResult = $dbm->loadRess($folderId);
        return $loadResult;
    }
    
    //clipBoardInfで表示させるRessInfの情報を取得する
    public function getRessData($loadResult){
        $dbm = new dbManager();
        $getResult = $dbm->getRessData($loadResult);
        return $getResult;
    }
    
    //取得してきたフォルダーの件数分、clipBoardFolderで表示させる
    public function displayFolder($loadResult){
        $sec = new sec();
        foreach($loadResult as $loadResult){
            $kekka = ("ID:".$loadResult->folderId." ".$loadResult->folderName);
            echo '<a href="http://localhost/jk2/zenkikaihatu/clipBoardInf.php?folderId='.$loadResult->folderId.'">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
        }
    }
    
    //取得してきたレスの件数分、clipBoardInfで表示させる。
    public function displayRess($getResult){
        $dbm = new dbManager();
        $sec = new sec();
        if(!empty($getResult)){
            foreach($getResult as $getResult){
                $result = $dbm->getRessThreadInf($getResult->ressId);
                $kekka = ("ID:".$getResult->ressId." ".$getResult->ressText." "."書き込み日時:".$getResult->rightDay);
                echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$result[0]->threadId.'">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
            }
        }else{
            echo "クリップされているレスはありません";
        }
    }
    
    //フォルダーの中にレスを登録する
    public function insertClipRess($userId,$folderId,$ressId){
        $folderID = intval($folderId);
        $ressID = intval($ressId);
        $dbm = new dbManager();
        $dbm->insertClipRess($userId,$folderID,$ressID);
    }
    public function displayPullD($folderList){
        $sec = new sec();
        foreach ($folderList as $list) {
            echo '<option value="' . $sec->hsc($folderList->folderId) . '>' . $sec->hsc($folderList->folderName) . '</option>';
        }
    }
}
?>
