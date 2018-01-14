<!DOCTYPE html>
<html>

<head>
    <meta content="ja" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>削除</title>
</head>

<body>
<form method="post" action="delete.php">
    <input type="hidden" name="reshid" value="<?php echo $_POST['reshid']; ?>">
    <input type="hidden" name="delPass" value="<?php echo $_POST['delPass'];?>">
    <input type="hidden" name="threadId" value="<?php echo $_POST['threadId']; ?>">
    <dl class="message">
        <dt class="msg-title">投稿の内容</dt>
        <dd class="msg-TT"><?php echo $_POST['reshcont']; ?></dd><br>
        <dt class="msg-conf">この投稿を削除します。間違いがなければパスワードを入力して確認を押してください。</dt>
        <input type="password" name="delPass_conf" placeholder="削除用パスワード">
    </dl>
    <input type="submit" value="確認">
</body>
</html>