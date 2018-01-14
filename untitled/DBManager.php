<?php
require_once "DBinfo.php";
require_once "ThreadTblDT.php";
require_once 'ResTblDT.php';
require_once 'DBRegistTbl.php';

class DBManager
{
    public $myPdo;

    public function checkThread($threadId){
        try
        {
            $this->dbConnect();

            $stmt = $this->myPdo->prepare("SELECT * FROM threadtbl WHERE threadId = $threadId");
            $stmt->execute();

            $threid = $stmt->fetch(PDO::FETCH_ASSOC);
            return $threid['threadId'];
        }catch(PDOException $e){
            print('検索に失敗'.$e->getMessage());
        }
        $this->dbDisconnect();
    }

    public function checkResponse($resId,$userId){

    }

    public function deleteResponse($reeId,$userId){
      try
      {
        $this->dbConnect();

        $stmt = $this->myPdo->prepare("UPDATE 'responsetbl' SET 'resContent' = '削除されました','userId'= 'del3' WHERE  userId = :userId, resId = :resId");

        $stmt->bindValue(':userId', $_SESSION["userId"], PDO::PARAM_STR);
        $stmt->bindValue(':resId', $resId, PDO::PARAM_INT);
      }catch(PDOException $e){
          print('検索に失敗'.$e->getMessage());
      }
      $this->dbDisconnect();
    }

    public function serchthread($threadId){
      try {
        $this->dbConnect();

        $stmt = $this->myPdo->prepare("SELECT threadTitle FROM threadtbl WHERE threadId = $threadId");
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

            $stmt = $this->myPdo->prepare("SELECT * FROM threadtbl");
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

            $stmt = $this->myPdo->prepare("SELECT * FROM responsetbl WHERE threadId = $threadId");
            $stmt->execute();

            $retList = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rowData = new ResTblDT();

                $rowData->resid = $row["resId"];
                $rowData->rescontent = $row["resContent"];
                $rowData->parentid = $row["parentId"];
                $rowData->threadid = $row["threadId"];
                $rowData->userid = $row['userId'];
                $rowData->writeday = $row['writeDay'];
                $rowData->goodcnt = $row['goodCnt'];
                $rowData->delpass = $row['delPass'];

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

            $stmt = $this->myPdo->prepare('SELECT * FROM threadtbl');
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

    public function registDel($delPass,$userId){
      try{
        $this->dbConnect();

        $stmt = $this->myPdo->prepare("UPDATE usertbl SET delPass = '$delPass' WHERE userId = :userid");
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

            $stmt = $this->myPdo->prepare("INSERT INTO responsetbl(resId,resContent,threadId,userId,writeDay,delPass) VALUES (null,:resContent,:threadId,:userId,now(),:delPass)");
            $stmt->bindValue(':resContent',$resContent,PDO::PARAM_STR);
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

            $stmt = $this->myPdo->prepare("INSERT INTO threadtbl(threadId,threadTitle,categoryId,createDay) VALUES (null,:threadTitle,:categoryId,now())");
            $stmt->bindValue(':threadTitle',$threadTitle,PDO::PARAM_STR);
            $stmt->bindValue(':categoryId',$categoryid,PDO::PARAM_INT);

            $stmt->execute();

            $this->dbDisConnect();

        } catch (PDOException $e) {
            print('書き込み失敗。' . $e->getMessage());
            throw $e;
        }
    }

    public function dbConnect()
    {
        try {
            $cnect = new DBinfo();
            $this->myPdo = new PDO('mysql:host=' . $cnect->dbhost . ';dbname=' . $cnect->dbname . ';charset=utf8', $cnect->userid, $cnect->password, array(PDO::ATTR_EMULATE_PREPARES => false));
        } catch (PDOException $e) {
            print('データベース接続失敗。' . $e->getMessage());
            throw $e;
        }
    }

    public function dbDisconnect()
    {
        unset($myPdo);
    }

    public function goodCheck($userId, $resId){
    try{
   $this->dbConnect();

   $stmt = $this->myPdo->prepare('SELECT * FROM DBGoodTbl WHERE userId = :keyid AND resId = :RESUID');
   $stmt->bindValue(':keyid', $userId,PDO::PARAM_STR);
   $stmt->bindValue(':RESUID', $resId,PDO::PARAM_INT);

   $stmt->execute();


   $retList = array();
     while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){


       array_push($retList,$row["userId"]);
       array_push($retList,$row["resId"]);
     }
   $this->dbDisconnect();

     return $retList;
     }catch (PDOExeption $e){
     print('検索失敗'.$e->getMessage());
     }
 }



 public function insertUserinfo($userId, $resId, $goodDay){
   try{
     $this->dbConnect();

     $stmt = $this->myPdo -> prepare("INSERT INTO DBGoodTbl (userId, resId, goodDay) VALUES(:userId, :resId, :goodDay)");
     $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
     $stmt->bindValue(':resId', $resId,PDO::PARAM_INT);
     /*(string)*/$stmt->bindValue(':goodDay', $goodDay,PDO::PARAM_STR);

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

     $stmt = $this->myPdo -> prepare("UPDATE responsetbl SET goodCnt = goodCnt + 1  WHERE resId = :resId");
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
		$stmt = $this->myPdo->prepare('SELECT * FROM threadTbl WHERE threadTbl.categoryId = :keyid ');
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
			$stmt = $this->myPdo->prepare('SELECT * FROM responsetbl WHERE writeDay BETWEEN :day2 AND :day AND threadId = :thread ORDER BY goodCnt DESC');
			$stmt->bindValue(':thread', $thread,PDO::PARAM_INT);
			$stmt->bindValue(':day2',$Day2,PDO::PARAM_STR);
			$stmt->bindValue(':day',$Day,PDO::PARAM_STR);
			$stmt->execute();

			//実行して帰ってきたレコードの一部をオブジェクトとして配列に入れる
			$retList = array();
				while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
					$rowData = new ResTblDT();

					$rowData->resContent = $row['resContent'];
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
			$stmt = $this->myPdo->prepare('SELECT * FROM responsetbl WHERE writeDay BETWEEN :day2 AND :day AND threadId = :thread ORDER BY goodCnt DESC');
			$stmt->bindValue(':thread', $thread,PDO::PARAM_INT);
			$stmt->bindValue(':day2',$Day2,PDO::PARAM_STR);
			$stmt->bindValue(':day',$Day,PDO::PARAM_STR);

			$stmt->execute();

			//実行して帰ってきたレコードの一部をオブジェクトとして配列に入れる
			$retList = array();
				while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
					$rowData = new ResTblDT();

					$rowData->resContent = $row['resContent'];
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

  public function userCheck($userId, $favoriteUserId){

	   try{
		$this->dbConnect();
		//貰ってきた自分のユーザーIDとレスにあるユーザーIDをユーザーテーブルにあるユーザーIDと一致するものを探す
		$stmt = $this->myPdo->prepare('SELECT * FROM DBRegistTbl WHERE userId = :keyid AND FavoriteUserId = :FAVORITE');
		$stmt->bindValue(':keyid', $userId,PDO::PARAM_STR);
        $stmt->bindValue(':FAVORITE', $favoriteUserId,PDO::PARAM_STR);

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

  public function insertUInfo($userId, $favoriteUserId){
		try{

			$this->dbConnect();

			$stmt = $this->myPdo->prepare('INSERT INTO DBRegistTbl (userId, FavoriteUserId) VALUES(:userId, :favorite)');
            $stmt->bindValue(':userId', $userId,PDO::PARAM_STR);
			$stmt->bindValue(':favorite', $favoriteUserId,PDO::PARAM_STR);


			$stmt->execute();

			$this->dbDisconnect();

		}catch(PDOExeption $e){
			print('検索失敗'.$e->getMessage());
			throw $e;
		}
	}

	public function checkColor($userId, $favoriteUserId){

	   try{
		$this->dbConnect();
		$stmt = $this->myPdo->prepare('SELECT * FROM dbregisttbl WHERE userId = :KEYID AND FavoriteUserId = :USERID');
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

}
?>
