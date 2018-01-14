<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Infarmation</title>
    <link href="sinki_style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header>
        <div id="header">
            <div style="text-align:center;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <article>
        <p class="title">レス投稿</p>
        <form method="post" action="conf_res.php">
            <?php //var_dump($_POST['threadId']); ?>
            <div align="center">
                <table style="margin-top:100px;">
                    <tr>
                        <td style="width:90px;p">内容</td>
                        <td style="width:465px;">
                            <textarea cols="47" rows="15" name="resContent" maxlength="300" style="font-size:17px; padding:0 10px; float:right; resize:none;width:465px;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:90px;">パスワード</td>
                        <td>
                        <?php
                        session_start();
                        $_SESSION['delPass'] = 0;
                        if($_SESSION['delPass'] == 0){
                            echo '<input type = "text" style="width:480px;" size = "43" name = "delPass" placeholder = "削除用パスワード(数字4文字)を入力してください" />';
                        } else {
                            echo '<input type = "text" style="width:488px;" readonly = "readonly" size = "4" name = "pass" value = "****">';
                            echo '<input type = "hidden" name = "delPass" value = "' . $_SESSION['delPass'] . '" />';
                        }
                        ?>     
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" name="submit" style="border-style:none; width:488px; float:right;">投稿</button>
                        </td>
                    </tr>

                </table>
                <input type="hidden" name="threadId" value="<?php echo $_POST['threadId']; ?>">
            </div>
        </form>
    </article>
</body>
</html>