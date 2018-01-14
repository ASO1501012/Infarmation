<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "clipBoardManager.php";
require_once "sec.php";
$cm = new clipBoardManager();
$sec = new sec();

//<a>タグで渡していたフォルダーIDを取得
if(isset($_GET['folderId'])){
    $folderId = $_GET['folderId'];
}else{
    $folderId = $_POST['folderId'];
}

//表示させるレスのリストを読み込み処理
$loadResult = $cm->loadress($folderId);

//表示させるレス情報の読み込み
$getResult = $cm->getRessData($loadResult);

//このフォルダーにレスを追加する
if(!empty($_SESSION['userId']) && !empty($_POST['ressId'])){
    $cm->insertClipRess($_SESSION['userId'],$_POST['folderId'],$_POST['ressId']);
    header('Location:clipBoardFolder.php');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>クリップ画面</title>
    </head>
    <body>
        新規フォルダー作成
        <form action="clipBoardInf.php" method="post">
            ressId:<input type="text" name="ressId">
            <input type="hidden" name="folderId" value="<?php echo $sec->hsc($folderId); ?>">
            <input type="submit" value="作成">
        </form>
        <?php
        //検索に一致したレス情報をすべて表示する
        $cm->displayRess($getResult);
        ?>
    </body>
</html>