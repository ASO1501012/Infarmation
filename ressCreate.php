<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "ressManager.php";
$rm = new ressManager();
if(!empty($_POST['ressId']) && !empty($_POST['ressText']) && !empty($_POST['parentId']) && !empty($_POST['threadId']) &&    !empty($_POST['rightDay']) && !empty($_POST['goodCnt'])){
    echo "確認";
    $rm->ressCreate($_POST['ressId'],$_POST['ressText'],$_POST['parentId'],$_POST['threadId'],$_SESSION['userId'],$_POST['rightDay'],$_POST['goodCnt']);
    //header('Location:main.php');
}
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>レス作成画面</title>
    </head>
    <body>
        <form action="ressCreate.php" method="post">
            <table>
                <tr><td>レスID:</td><td><input type="text" name="ressId" size="20"></td></tr>
                <tr><td>レステキスト:</td><td><input type="text" name="ressText" size="20"></td></tr>
                <tr><td>親ID:</td><td><input type="text" name="parentId" size="20"></td></tr>
                <tr><td>スレッドID:</td><td><input type="text" name="threadId" size="20"></td></tr>
                <tr><td>書き込み日時:</td><td><input type="text" name="rightDay" size="20"></td></tr>
                <tr><td>いいね数:</td><td><input type="text" name="goodCnt" size="20"></td></tr>
                <tr><td colspan=2><input type="submit" value="登録"></td></tr>
            </table>
        </form>
    </body>
</html>
