<?php
if(isset($_SESSION)){
    session_start();
}
//<a>タグで渡していたフォルダーIDを取得
if(isset($_GET['ressId'])){
    $ressId = $_GET['ressId'];
}
require_once "noticeManager.php";
$nm = new noticeManager();
$nm->updataNoticeInf($ressId);
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>通知レス画面</title>
        <link rel="stylesheet" href="./notice_style.css" type="text/css">
    </head>
    <body>
        <p class="title">通知画面</p>
        <?php
        echo "レスの画面へ";
            echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'."メイン登録画面へ".'</a>'.nl2br("\n");
        ?>
    </body>
</html>