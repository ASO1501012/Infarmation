<?php
if(!isset($_SESSION)){
    session_start();
}

if(isset($_SESSION['delPass'])){
    $_SESSION['delPass'] = $_POST['delPass'];
}
?>

 <!DOCTYPE html>
<html>

<head>
    <meta content="ja" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>作成確認</title>
</head>

<body>
<form method="post" action="inputres.php">
    <dl class="message">
        <dt class="msg-title">投稿の内容</dt>
        <dd class="msg-TT"><?php echo $_POST['resContent']; ?></dd>
        <dt class="msg-conf">間違いがなければ確認を押してください</dt>
    </dl>
    <input type="submit" value="確認">
    <input type="hidden" name="threadId" value="<?php echo $_POST['threadId']; ?>">
    <input type="hidden" name="resContent" value="<?php echo $_POST['resContent'];?>">
</form>
</body>
</html>
