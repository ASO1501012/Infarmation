<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>レス投稿ページ</title>
</head>

<body>
<form method="post" action="conf_res.php">
    <h1>レス投稿</h1>
    <div align="center">
        <table>
            <tr>
                <td>内容</td>
                <td><input type="text" name="resContent" size="100">
            </tr>
            <tr>
                <td>パスワード</td>
                <td><?php
                session_start();
                $flg = 0;

                if (isset($_SESSION['delPass'])){
                    echo '<input type = "text" readonly = "readonly" size = "4" name = "delPass" value = "****">';
                    echo '<input type = "hidden" name = "delPass" value = "' . $_SESSION['delPass'] . '" />';
                } else {
                $delpass = "削除用パスワード(数字4文字)を入力してください";
                echo '<input type = "text" size = "43" name = "delPass" value = "' . $delpass . '" />';
                $flg = 1;
                }
                ?></td>
            </tr>
        </table>
        <input type="hidden" name="flg" value="<?php echo $flg; ?>">
        <input type="hidden" name="threadId" value="<?php echo $_POST['threadId']; ?>">
        <input type="submit" name="submit" value="作成">
    </div>
</form>
</body>

</html>
