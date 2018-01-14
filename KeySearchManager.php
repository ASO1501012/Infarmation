<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 

require_once "dbManager.php";
require_once "sec.php";

class keySearchManager{
    
    public function keySearch($typeId,$keyWord){
        $dbm = new dbManager();
        $searchResult =$dbm->keySearch($typeId,$keyWord);
        $_SESSION['searchResult'] = $searchResult;
        $listlength = count($searchResult);

        if($listlength >= 1){
            header('Location: searchResult.php');
        }else{
            //検索結果なし
            header('Location: main.php');
        }
    }

    public function typeDivide($serachResult){
        $sec = new sec();
        $result = $serachResult;
        $resultType = $_SESSION['resultTypeId'];
        if($resultType == 0){
            foreach($result as $result){
                $kekka = ("ID:".$result["threadId"]." ".$result["threadTitle"]);
                echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
            }
        }else if($resultType == 1){
            foreach($result as $result){
                $kekka = ("ID:".$result["ressId"]." ".$result["ressText"]);
                echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
            }
        }else if($resultType == 2){
            foreach($result as $result){
                $kekka = ("ID:".$result["folderId"]." ".$result["folderName"]);
                echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
            }
        }
    }

}

?>