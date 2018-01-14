<?php
session_start();

if(isset($_POST['ThreadTitle'])){$_SESSION['ThreadTitle'] = $_POST['ThreadTitle'];}
if (isset($_POST['Category'])){$_SESSION['Category'] = $_POST['Category'];}

$title = $_SESSION["ThreadTitle"];

switch ($_SESSION["Category"]){
    case 0:
        $cate = "病気・災害情報";
        break;

    case 1:
        $cate = "おもしろ野菜";
        break;

    case 2:
        $cate = "雑談";
        break;

    case 3:
        $cate = "おすすめレシピ";
        break;

    case 4:
        $cate = "報告";
        break;
}
print <<<EOF
<!DOCTYPE html>
<html>

<head>
    <meta content="ja" http-equiv="Content-Language">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>作成確認</title>
</head>

<body>
<form method="post" action="create.php">
    <dl class="message">
        <dt class="msg-title">スレッドのタイトル</dt>
        <dd class="msg-TT">$title</dd>
        <dt class="msg-cate">カテゴリ</dt>
        <dd class="msg-CT">$cate</dd>
        <dt class="msg-conf">間違いがなければ確認を押してください</dt>
    </dl>
    <input type="submit" value="確認">



</body>

</html>
EOF;
?>