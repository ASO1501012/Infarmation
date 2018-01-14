<?php
    
//テーブル用のクラスを読み込む
require_once "userInf.php";
require_once "favoriteUserId.php";
require_once "clipBoard.php";
require_once "category.php";
require_once "threadInf.php";
require_once "ressInf.php";
require_once "good.php";
require_once "ranking.php";
require_once "noticeRessInf.php";
require_once "DBinfo.php";
require_once "ThreadTblDT.php";
require_once 'ResTblDT.php';
require_once 'DBRegistTbl.php';



class dbManager{
    //DBに接続する際の必要な情報
    public $user = "farmedUser";
    public $password = "password";
    public $dbhost = "localhost";
    public $dbname = "farmed";
    private $myPdo;

    //接続のメソッド
    public function dbConnect(){
        try{
            $this->myPdo = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname  . ';charset=utf8', $this->user, $this->password, array(PDO::ATTR_EMULATE_PREPARES => false));
        }catch(PDOException $e) {
            print('データベース接続失敗'.$e->getMessage());
            throw $e;
        }
    }

    //切断のメソッド
    public function dbDisconnect(){
        unset($myPdo);
    }
    
    //ユーザー情報の取り出し：会員登録時
    public function getUser($userId,$passWord){
        try{
            //DBに接続
            $this->dbConnect();

            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM userInf WHERE userId = :userId AND passWord = :passWord');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':passWord', $passWord, PDO::PARAM_STR);
            //SQLを実行
            $stmt->execute();


            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new userInf();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->userId = $row["userId"];
                $rowData->userName = $row["userName"];
                $rowData->passWord = $row["passWord"];
                $rowData->mailAddress = $row["mailAddress"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }

            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch (PDOException $e) {
            print('検索に失敗。'.$e->getMessage());
        }
    }
    
    //ユーザー情報の取り出し：アカウント変更時
    public function getUserInf($userId){
        try{
            //DBに接続
            $this->dbConnect();

            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM userInf WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            //SQLを実行
            $stmt->execute();


            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new userInf();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->userId = $row["userId"];
                $rowData->userName = $row["userName"];
                $rowData->passWord = $row["passWord"];
                $rowData->mailAddress = $row["mailAddress"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }

            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch (PDOException $e) {
            print('検索に失敗。'.$e->getMessage());
        }
    }
    
    //ユーザーが書いたレスのすべての情報
    public function getNoticeRessInf($userId){
        $flag = "0";
        $flag = intval($flag);
        try{
            //DBに接続
            $this->dbConnect();

            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM noticeRessInf WHERE toUserId = :userId AND flag = :flag');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindParam(':flag', $flag, PDO::PARAM_STR);
            //SQLを実行
            $stmt->execute();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new noticeRessInf();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->userId = $row["userId"];
                $rowData->ressId = $row["ressId"];
                
                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }

            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch (PDOException $e) {
            print('検索に失敗。'.$e->getMessage());
        }
    }

    //ユーザー登録のメソッド
    public function registerUser($userId,$userName,$hash,$mailAddress){
        try{
            //DBに接続
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('INSERT INTO userInf(userId, userName, passWord, mailAddress) VALUES (:userId, :userName, :passWord, :mailAddress)');
            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':userName',$userName,PDO::PARAM_STR);
            $stmt->bindValue(':passWord', $hash, PDO::PARAM_STR);
            $stmt->bindValue(':mailAddress', $mailAddress, PDO::PARAM_STR);
            
            //SQL実行
            $stmt->execute();
            
            //DB切断
            $this->dbDisconnect();
            
        }catch (PDOException $e) {
            print('登録失敗。'.$e->getMessage());
            throw $e;
        }
    }
    
    //キーワード検索のメソッド
    public function keySearch($typeId,$keyWord){
        try{
            //DBに接続
            $this->dbConnect();
            //typeIdごとに検索する内容を変更する
            if($typeId == 0){
                //スレッドの検索
                $stmt=$this->myPdo->prepare("SELECT * FROM threadInf WHERE threadTitle LIKE '%{$keyWord}%'");
                $stmt->execute();
                
                //取得したデータを１件ずつループしながらクラスに入れていく
                $list = array();
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    //取得した一件を配列に追加する
                    array_push($list,$row);
                }
                $_SESSION['resultTypeId'] = $typeId;
                
                $this->dbDisconnect();
                return $list;
                
            }else if($typeId == 1){
                //レスの検索
                $stmt=$this->myPdo->prepare("SELECT * FROM ressInf WHERE ressText LIKE '%{$keyWord}%'");
                $stmt->execute();
                
                //取得したデータを１件ずつループしながらクラスに入れていく
                $list = array();
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){                    
                    //取得した一件を配列に追加する
                    array_push($list,$row);
                }
                $_SESSION['resultTypeId'] = $typeId;
                
                $this->dbDisconnect();
                return $list;
                
            }else if($typeId == 2){
                //クリップボードの検索
                $stmt=$this->myPdo->prepare("SELECT * FROM clipBoard WHERE folderName LIKE '%{$keyWord}%'");
                $stmt->execute();
                //取得したデータを１件ずつループしながらクラスに入れていく
                $list = array();
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    //取得した一件を配列に追加する
                    array_push($list,$row);
                }
                $_SESSION['resultTypeId'] = $typeId;
                
                $this->dbDisconnect();
                return $list;
            }
        }catch(PDOException $e){
            print('検索失敗。'.$e->getMessage());
            throw $e;
        }        
    }
    
    //アカウント情報の変更
    public function accountManagement($userId,$userName,$hash,$mailAddress){
        try{
            //DBに接続
            $this->dbConnect();
            //ユーザー情報の変更
            $stmt=$this->myPdo->prepare('UPDATE userInf SET userName=:userName,passWord=:hash,mailAddress=:mailAddress WHERE userId=:userId');
            
            $stmt->bindValue(':userName',$userName,PDO::PARAM_STR);
            $stmt->bindValue(':hash',$hash, PDO::PARAM_STR);
            $stmt->bindValue(':mailAddress',$mailAddress, PDO::PARAM_STR);
            $stmt->bindValue(':userId',$userId, PDO::PARAM_STR);
            
            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();
            
        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
            throw $e;
        }
    }
    
    //パスワードリセット
    public function resetPass($userId,$passWord){
        try{
            //DBに接続
            $this->dbConnect();
            //ユーザー情報の変更
            $stmt=$this->myPdo->prepare('UPDATE userInf SET passWord=:passWord WHERE userId=:userId');

            $stmt->bindValue(':passWord',$passWord, PDO::PARAM_STR);
            $stmt->bindValue(':userId',$userId, PDO::PARAM_STR);

            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();

        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
            throw $e;
        }
    }
    
    //新規フォルダー作成
    public function registerFolder($userId,$folderId,$folderName){
        try{
            //DBに接続
            $this->dbConnect();

            $stmt=$this->myPdo->prepare('INSERT INTO clipBoard(userId, folderId, folderName) VALUES (:userId, :folderId, :folderName)');
            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':folderId',$folderId,PDO::PARAM_INT);
            $stmt->bindValue(':folderName', $folderName, PDO::PARAM_STR);
            
            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();

        }catch(PDOException $e){
            print('作成失敗。'.$e->getMessage());
            throw $e;
        }
    }
        
    public function loadFolder($userId){
        try{
            //DBに接続
            $this->dbConnect();

            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM clipBoard WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
            //SQLを実行
            $stmt->execute();


            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new clipBoard();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->userId = $row["userId"];
                $rowData->folderId = $row["folderId"];
                $rowData->folderName = $row["folderName"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }

            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch (PDOException $e) {
            print('検索に失敗。'.$e->getMessage());
        }
    }
    
    public function loadRess($folderId){
        try{
            //DBに接続
            $this->dbConnect();

            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM clipBoardRess WHERE folderId = :folderId');
            $stmt->bindParam(':folderId', $folderId, PDO::PARAM_STR);
            
            //SQLを実行
            $stmt->execute();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new clipBoard();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->ressId = $row["ressId"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }

            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch (PDOException $e) {
            print('検索に失敗。'.$e->getMessage());
        }
    }
    
    public function getRessData($loadResult){
        try{
            //DBに接続
            $this->dbConnect();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            foreach($loadResult as $loadResult){
                //SQLを生成
                $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE ressId = :ressId');
                $stmt->bindParam(':ressId', $loadResult->ressId, PDO::PARAM_STR);

                //SQLを実行
                $stmt->execute();
                
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    //データを入れるクラスをnew
                    $rowData = new ressInf();

                    //DBから取れた情報をカラム毎に、クラスに入れていく
                    $rowData->ressId = $row["ressId"];
                    $rowData->ressText = $row["ressText"];
                    $rowData->rightDay = $row["rightDay"];
                    $rowData->threadId = $row["threadId"];
                    $rowData->userId = $row["userId"];

                    //取得した一件を配列に追加する
                    array_push($list, $rowData);
                }
            }
            
            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch(PDOException $e){
            print('取得に失敗。'.$e->getMessage());
        }
    }
    
    public function insertClipRess($userId,$folderId,$ressId){
        try{
            echo $userId.$folderId.$ressId;
            //DBに接続
            $this->dbConnect();
            
            $stmt=$this->myPdo->prepare('INSERT INTO clipBoardRess(userId, folderId, ressId) VALUES (:userId, :folderId, :ressId)');
            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':folderId',$folderId,PDO::PARAM_STR);
            $stmt->bindValue(':ressId', $ressId, PDO::PARAM_STR);
                
            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();

        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    public function ressCreate($ressId,$ressText,$parentId,$threadId,$userId,$rightDay,$goodCnt){
        try{
            //DBに接続
            $this->dbConnect();

            $stmt=$this->myPdo->prepare('INSERT INTO ressInf(ressId, ressText, parentId, threadId, userId, rightDay, goodCnt) VALUES (:ressId, :ressText, :parentId, :threadId, :userId, :rightDay, :goodCnt)');
            $stmt->bindValue(':ressId', $ressId, PDO::PARAM_INT);
            $stmt->bindValue(':ressText',$ressText,PDO::PARAM_STR);
            $stmt->bindValue(':parentId', $parentId, PDO::PARAM_INT);
            $stmt->bindValue(':threadId', $threadId, PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':rightDay', $rightDay, PDO::PARAM_STR);
            $stmt->bindValue(':goodCnt', $goodCnt, PDO::PARAM_INT);

            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();
            
        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    //レスIDが親IDに指定されているレスのIDを取得する
    public function noticeInf($result){
        try{            
            $cnt = count($result);
            //DBに接続
            $this->dbConnect();
            
            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            for($i = 0;$i < $cnt;$i++){
                $ressId = $result[$i]->ressId;
                $ressId = intval($ressId);
                $userId = $result[$i]->userId;
                
                $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE userId = :userId AND ressId = :ressId');
                
                $stmt->bindValue(':userId',$userId, PDO::PARAM_STR);
                $stmt->bindValue(':ressId',$ressId, PDO::PARAM_INT);
                
                //SQL実行
                $stmt->execute();
                
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    //データを入れるクラスをnew
                    $rowData = new ressInf();

                    //DBから取れた情報をカラム毎に、クラスに入れていく
                    $rowData->ressId = $row["ressId"];
                    $rowData->ressText = $row["ressText"];
                    $rowData->rightDay = $row["rightDay"];
                    $rowData->userId = $row["userId"];
                    $rowData->threadId = $row["threadId"];
                    

                    //取得した一件を配列に追加する
                    array_push($list, $rowData);
                }
            }
            //DB切断
            $this->dbDisconnect();
            //echo "noticeInf";

            return $list;
        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    //parentIDがressIdと等しいレスのuserIdを取得する
    public function noticeToUser($parentId){
        echo "noticeToUserまで動いている";
        try{            
            //DBに接続
            $this->dbConnect();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            $ressId = intval($parentId);
            $stmt=$this->myPdo->prepare('SELECT * FROM ressInf WHERE ressId = :parentId');
            //$stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':parentId',$parentId, PDO::PARAM_INT);

            //SQL実行
            $stmt->execute();

            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new ressInf();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->userId = $row["userId"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }
            //DB切断
            $this->dbDisconnect();
            
            return $list;
        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    //表示するressIdとressTextを表示
    public function noticeRess($result){
        try{      
            $cnt = count($result);

            //DBに接続
            $this->dbConnect();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            for($i = 0;$i < $cnt;$i++){

                $parentId = intval($result[$i]->parentId);

                $stmt=$this->myPdo->prepare('SELECT * FROM ressInf WHERE ressId = :parentId');
                $stmt->bindValue(':parentId',$parentId, PDO::PARAM_INT);

                //SQL実行
                $stmt->execute();

                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    //データを入れるクラスをnew
                    $rowData = new ressInf();

                    //DBから取れた情報をカラム毎に、クラスに入れていく
                    $rowData->ressId = $row["ressId"];
                    $rowData->ressText = $row["ressText"];

                    //取得した一件を配列に追加する
                    array_push($list, $rowData);
                }
            }
            //DB切断
            $this->dbDisconnect();

            return $list;
        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    public function noticeRessInfCreate($result,$userId,$ressId){
        echo "noticeRessInfCreateまで動いている";
        try{
            $toUserId = $result[0]->userId;
            $resuId = intval($ressId);
            $flag = 0;
            
            //DBに接続
            $this->dbConnect();
        
            $stmt=$this->myPdo->prepare('INSERT INTO noticeRessInf(toUserId, userId, ressId, flag) VALUES (:toUserId, :userId, :ressId, :flag)');
            $stmt->bindValue(':toUserId', $toUserId, PDO::PARAM_STR);
            $stmt->bindValue(':userId',$userId,PDO::PARAM_STR);
            $stmt->bindValue(':ressId', $ressId, PDO::PARAM_INT);
            $stmt->bindValue(':flag', $flag, PDO::PARAM_INT);

            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();

        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    public function updataNoticeInf($ressId){
        try{
            echo $ressId;
            $ressId = intval($ressId);
            
            //DBに接続
            $this->dbConnect();

            $stmt=$this->myPdo->prepare('UPDATE noticeRessInf SET flag = 1 WHERE ressId = :ressId');
            $stmt->bindValue(':ressId', $ressId, PDO::PARAM_INT);

            //SQL実行
            $stmt->execute();
            //DB切断
            $this->dbDisconnect();

        }catch(PDOException $e){
            print('更新失敗。'.$e->getMessage());
        }
    }
    
    //結合テスト　大津
    public function userCheck($userId, $favoriteUserId){
        try{	
            $this->dbConnect();
            //貰ってきた自分のユーザーIDとレスにあるユーザーIDをユーザーテーブルにあるユーザーIDと一致するものを探す
            $stmt = $this->myPdo->prepare('SELECT * FROM favotiteUserId WHERE userId = :userId AND favoriteUserId = :favoriteUserId');
            
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':favoriteUserId', $favoriteUserId,PDO::PARAM_STR);

            $stmt->execute();


            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

                array_push($retList,$row["userId"]);

            }
            $this->dbDisconnect();

            return $retList;
        }catch (PDOExeption $e){
            print('検索失敗'.$e->getMessage());
        }	
    }




    public function deleteUserInfo($userId, $favoriteUserId){
        try{
            $this->dbConnect();
            //DBRegistTblから自分のユーザーIDとお気に入りにしたユーザーIDを消す
            //var_dump($userId);
            //var_dump($favoriteUserId);

            $stmt = $this->myPdo->prepare('DELETE FROM favotiteUserId WHERE userId = :userId AND favoriteUserId = :favorite');
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':favorite', $favoriteUserId,PDO::PARAM_STR);

            $stmt->execute();

            $this->dbDisconnect();

        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }


    public function userList($userId){

        try{	
            $this->dbConnect();
            //貰ってきた自分のユーザーIDとレスにあるユーザーIDをユーザーテーブルにあるユーザーIDと一致するものを探す
            $stmt = $this->myPdo->prepare('SELECT * FROM favotiteUserId WHERE userId = :userId');
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);

            $stmt->execute();


            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

                array_push($retList,$row['favoriteUserId']);

            }
            $this->dbDisconnect();

            return $retList;
        }catch (PDOExeption $e){
            print('検索失敗'.$e->getMessage());
        }	
    }
    
    public function getRessInf($userId){
        try{
            //DBに接続
            $this->dbConnect();

            //取得したデータを１件ずつループしながらクラスに入れていく
            $list = array();
            //SQLを生成
            $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

            //SQLを実行
            $stmt->execute();

            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                //データを入れるクラスをnew
                $rowData = new ressInf();

                //DBから取れた情報をカラム毎に、クラスに入れていく
                $rowData->ressId = $row["ressId"];
                $rowData->ressText = $row["ressText"];
                $rowData->rightDay = $row["rightDay"];
                $rowData->threadId = $row["threadId"];

                //取得した一件を配列に追加する
                array_push($list, $rowData);
            }
            $this->dbDisconnect();

            //結果が格納された配列を返す
            return $list;

        }catch(PDOException $e){
            print('取得に失敗。'.$e->getMessage());
        }
    }
    
    public function checkColor($userId, $favoriteUserId){

        try{	
            $this->dbConnect();
            //貰ってきた自分のユーザーIDとレスにあるユーザーIDをユーザーテーブルにあるユーザーIDと一致するものを探す
            $stmt = $this->myPdo->prepare('SELECT * FROM favotiteUserId WHERE userId = :KEYID AND favoriteUserId = :USERID');
            $stmt->bindValue(':KEYID', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':USERID', $favoriteUserId,PDO::PARAM_STR);

            $stmt->execute();


            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){


                array_push($retList,$row["userId"]);
            }
            $this->dbDisconnect();

            return $retList;
        }catch (PDOExeption $e){
            print('検索失敗'.$e->getMessage());
        }	
    }
    
    public function moveUserInfo($userId, $favoriteUserId2){
        //レスに移動するためのメソッド。
        try{

            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM favotiteUserId WHERE userId = :userId AND favoriteUserId = :favorite');
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':favorite', $favoriteUserId2,PDO::PARAM_STR);

            $stmt->execute();

            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){

                array_push($retList,$row['userId']);
                array_push($retList,$row['favoriteUserId']);

            }
            var_dump($retList);
            $this->dbDisconnect();

            return($retList);

        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }

    public function getRessThreadInf($ressId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE ressId = :ressId');
            $stmt->bindValue(':ressId', $ressId,PDO::PARAM_INT);

            $stmt->execute();

            $retList = array();            
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $rowData = new threadInf();
                $rowData->threadId = $row["threadId"];

                array_push($retList,$rowData);
            }
            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }
    
    public function getThreadInf($threadId){
        try{
        $this->dbConnect();

        $stmt = $this->myPdo->prepare('SELECT * FROM threadInf WHERE threadId = :threadId');
        $stmt->bindValue(':threadId', $threadId,PDO::PARAM_INT);

        $stmt->execute();

        $retList = array();            
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $rowData = new threadInf();
            $rowData->threadId = $row["threadId"];
            $rowData->threadTitle = $row["threadTitle"];
            
            array_push($retList,$rowData);
        }
            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }
    
    public function getCategoryInf(){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM category');
            $stmt->execute();

            $retList = array();            
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $rowData = new category();
                $rowData->categoryId = $row["categoryId"];
                $rowData->categoryName = $row["categoryName"];

                array_push($retList,$rowData);
            }
            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }
    
    public function getCategoryThreadInf($categoryId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM threadInf WHERE categoryId = :categoryId');
            $stmt->bindValue(':categoryId', $categoryId,PDO::PARAM_INT);

            $stmt->execute();

            $retList = array();            
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $rowData = new threadInf();
                $rowData->threadId = $row["threadId"];
                $rowData->threadTitle = $row["threadTitle"];

                array_push($retList,$rowData);
            }
            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }
    public function checkThread($threadId){
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("SELECT * FROM threadInf WHERE threadId = $threadId");
            $stmt->execute();

            $threid = $stmt->fetch(PDO::FETCH_ASSOC);
            return $threid['threadId'];
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
        $this->dbDisconnect();
    }

    public function deleteResponse($resId){
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("UPDATE ressInf SET delflg = 1 WHERE ressId = :resId");
            $stmt->bindValue(':resId', $resId, PDO::PARAM_INT);

            $stmt->execute();
            $this->dbDisconnect();
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
    }

    public function serchthread($threadId){
        try {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("SELECT threadTitle FROM threadInf WHERE threadId = $threadId");
            $stmt->execute();

            $List = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rowData = new ThreadTblDT();

                $rowData->threadtitle = $row['threadTitle'];


                array_push($List,$rowData);
            }
            $this->dbDisconnect();

            return $List;
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
    }

    public function getThreadTitle(){
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("SELECT * FROM threadInf");
            $stmt->execute();

            $List = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rowData = new ThreadTblDT();

                $rowData->threadid = $row['threadId'];
                $rowData->threadtitle = $row['threadTitle'];

                array_push($List,$rowData);
            }
            $this->dbDisconnect();

            return $List;
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
    }

    public function getResList($threadId) {
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("SELECT * FROM ressInf WHERE threadId = $threadId");
            $stmt->execute();

            $retList = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rowData = new ResTblDT();

                $rowData->resid = $row["ressId"];
                $rowData->rescontent = $row["ressText"];
                $rowData->parentid = $row["parentId"];
                $rowData->threadid = $row["threadId"];
                $rowData->userid = $row['userId'];
                $rowData->writeday = $row['rightDay'];
                $rowData->goodcnt = $row['goodCnt'];
                $rowData->delpass = $row['delPass'];
                $rowData->delflg = $row['delflg'];

                array_push($retList,$rowData);

            }
            $this->dbDisconnect();

            return $retList;
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
    }

    public function getThread(){
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM threadInf');
            $stmt->execute();

            $retList = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rowData = new ThreadTblDT();

                $rowData->threadid = $row["threadId"];
                $rowData->threadtitle = $row["threadTitle"];
                $rowData->categoryid = $row["categoryId"];
                $rowData->createday = $row["createDay"];

                array_push($retList,$rowData);

            }
            $this->dbDisconnect();

            return $retList;
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
    }

    public function registDelPass($delPass,$userId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("UPDATE userInf SET delPass = :delPass WHERE userId = :userid");
            $stmt->bindValue(':delPass',$delPass,PDO::PARAM_INT);
            $stmt->bindValue(':userid',$userId,PDO::PARAM_STR);

            $stmt->execute();
            $this->dbDisconnect();

        } catch (PDOException $e) {
            print('投稿失敗' . $e->getMessage());
            throw $e;
        }
    }

    public function RegistResponse($resContent, $threadId, $userId, $delPass)
    {
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("INSERT INTO ressInf(ressId,ressText,parentId,threadId,userId,rightDay,goodCnt,delPass) VALUES (null,:ressText,0,:threadId,:userId,now(),0,:delPass)");
            $stmt->bindValue(':ressText',$resContent,PDO::PARAM_STR);
            $stmt->bindValue(':threadId',$threadId,PDO::PARAM_INT);
            $stmt->bindValue(':userId',$userId ,PDO::PARAM_STR);
            $stmt->bindValue(':delPass',$delPass,PDO::PARAM_INT);

            $stmt->execute();

            $this->dbDisconnect();
        } catch (PDOException $e) {
            print('投稿失敗' . $e->getMessage());
            throw $e;
        }

    }

    public function CreateNewThread($threadTitle, $categoryid)
    {
        try {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("INSERT INTO threadInf(threadId,threadTitle,categoryId,createDay) VALUES (null,:threadTitle,:categoryId,now())");
            $stmt->bindValue(':threadTitle',$threadTitle,PDO::PARAM_STR);
            $stmt->bindValue(':categoryId',$categoryid,PDO::PARAM_INT);

            $stmt->execute();

            $this->dbDisConnect();

        } catch (PDOException $e) {
            print('書き込み失敗。' . $e->getMessage());
            throw $e;
        }
    }

    public function goodCheck($userId, $resId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo->prepare('SELECT * FROM good WHERE userId = :keyid AND ressId = :RESUID');
            $stmt->bindValue(':keyid', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':RESUID', $resId,PDO::PARAM_INT);

            $stmt->execute();


            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){


                array_push($retList,$row["userId"]);
                array_push($retList,$row["ressId"]);
            }
            $this->dbDisconnect();

            return $retList;
        }catch (PDOExeption $e){
            print('検索失敗'.$e->getMessage());
        }
    }



    public function insertUserinfo($userId, $resId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo -> prepare("INSERT INTO good (userId, ressId, goodDay) VALUES(:userId, :resId, now())");
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':resId', $resId,PDO::PARAM_INT);

            $stmt->execute();
            $this->dbDisconnect();
        }catch(PDOExeption $e){
            print('書き込み失敗'.$e->getMessage());
            throw $e;
        }
    }


    public function insertcount($resId){
        try{
            $this->dbConnect();

            $stmt = $this->myPdo -> prepare("UPDATE ressInf SET goodCnt = goodCnt + 1  WHERE ressId = :resId");
            $stmt->bindValue(':resId', $resId,PDO::PARAM_INT);

            $stmt->execute();
            $this->dbDisconnect();
        }catch(PDOExeption $e){
            print('加算失敗'.$e->getMessage());
            throw $e;
        }
    }

    public function runkingGetter($category, $goodDay){

        try{
            $this->dbConnect();
            //貰ってきたカテゴリIDをスレッドテーブルにあるカテゴリIDと一致するものを探してスレッドIDを出す
            $stmt = $this->myPdo->prepare('SELECT * FROM threadInf WHERE threadnf.categoryId = :keyid ');
            $stmt->bindValue(':keyid', $category,PDO::PARAM_INT);

            $stmt->execute();


            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){


                array_push($retList,$row["threadId"]);
            }
            $this->dbDisconnect();

            return $retList;
        }catch (PDOExeption $e){
            print('検索失敗'.$e->getMessage());
        }
    }





    public function threadserch($thread, $goodDay){
        try{
            $Day = $goodDay;
            $Day2 = date("Y-m-d", strtotime("-7 day"  ));
            $this->dbConnect();
            //レステーブルを対象にして今日から一週間前の間に一致する日付のレコードを取ってくる。
            $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE rightDay BETWEEN :day2 AND :day AND threadId = :thread ORDER BY goodCnt DESC');
            $stmt->bindValue(':thread', $thread,PDO::PARAM_INT);
            $stmt->bindValue(':day2',$Day2,PDO::PARAM_STR);
            $stmt->bindValue(':day',$Day,PDO::PARAM_STR);
            $stmt->execute();

            //実行して帰ってきたレコードの一部をオブジェクトとして配列に入れる
            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $rowData = new ResTblDT();

                $rowData->resContent = $row['ressText'];
                $rowData->goodCnt = $row['goodCnt'];

                array_push($retList, $rowData);
            }
            $this->dbDisconnect();

            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }






    public function serchmonth($thread, $goodDay){
        try{
            $Day = $goodDay;
            $Day2 = date("Y/m/d", strtotime("-31 day"  ));
            $this->dbConnect();
            //レステーブルを対象にして今日から一ヶ月前の間に一致する日付のレコードを取ってくる。
            $stmt = $this->myPdo->prepare('SELECT * FROM ressInf WHERE rightDay BETWEEN :day2 AND :day AND threadId = :thread ORDER BY goodCnt DESC');
            $stmt->bindValue(':thread', $thread,PDO::PARAM_INT);
            $stmt->bindValue(':day2',$Day2,PDO::PARAM_STR);
            $stmt->bindValue(':day',$Day,PDO::PARAM_STR);

            $stmt->execute();

            //実行して帰ってきたレコードの一部をオブジェクトとして配列に入れる
            $retList = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $rowData = new ResTblDT();

                $rowData->resContent = $row['ressText'];
                $rowData->goodCnt = $row['goodCnt'];

                array_push($retList, $rowData);
            }
            //var_dump($retList);
            $this->dbDisconnect();

            return $retList;
        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }

    public function insertUInfo($userId, $favoriteUserId){
        try{

            $this->dbConnect();

            $stmt = $this->myPdo->prepare('INSERT INTO favotiteUserId (userId, favoriteUserId) VALUES(:userId, :favorite)');
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
            $stmt->bindValue(':favorite', $favoriteUserId,PDO::PARAM_STR);


            $stmt->execute();

            $this->dbDisconnect();

        }catch(PDOExeption $e){
            print('検索失敗'.$e->getMessage());
            throw $e;
        }
    }
}
?>