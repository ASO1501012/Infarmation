<?php
require_once "ressManager.php";
require_once "userManager.php";

if(!isset($_SESSION)){
	session_start();
}

if (isset($_POST["resContent"]) && isset($_POST["threadId"]) &&  isset($_SESSION['delPass'])){
    $RM = new ressManager();
    $RM->ResponseContribution($_POST["resContent"],$_POST["threadId"],$_SESSION['delPass']);
    echo "投稿成功しました。";
} else {
    echo "投稿失敗しました。";
}

if(isset($_SESSION['userId']) && isset($_SESSION['delPass'])){
    $um = new userManager();
    $um->registDel($_SESSION['userId'],$_SESSION['delPass']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>投稿ページ</title>
</head>
<body>
    <form method="get" action="ThreadPage.php">
        <input type="hidden" name="id" value="<?php echo $_POST['threadId']; ?>">
        <input type="submit" value="戻る">
    </form>
</body>
</html>