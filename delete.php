<?php
session_start();
require_once 'ressManager.php';
$rm = new ressManager();
if(!isset($_SESSION)){ 
    session_start(); 
} 
if ($_POST['delPass'] == $_POST['delPass_conf']){
	$del = $rm->DeleteResponse($_POST['reshid']);
	echo "成功しました。";
} else {
	echo "パスワードが違います。";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>削除ページ</title>
</head>
<body>
    <form method="get" action="ThreadPage.php">
    <input type="hidden" name="id" value="<?php echo $_POST['threadId']; ?>">
    <input type="submit" value="戻る">
    </form>
</body>
</html>