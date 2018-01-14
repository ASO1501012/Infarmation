<?php
//お気に入りユーザー先のスレッド一覧画面
if(!isset($_SESSION)){ 
    session_start(); 
} 
//$_SESSION['userId'] = 'Garudo24';
}
?>


    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>お気に入りユーザー画面</title>
    </head>

    <body>
        <?php
            echo '<a href="http://localhost/jk2/zenkikaihatu/clipBoardFolder.php">'."クリップボード画面へ".'</a>'.nl2br("\n");
            echo '<a href="http://localhost/jk2/zenkikaihatu/ressInfPage.php?userId='.$_POST['userId'].'">'."投稿済みレス画面へ".'</a>'.nl2br("\n");
        ?>      
    </body>

    </html>