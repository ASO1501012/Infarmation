<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "ressManager.php";
$rm = new ressManager();
require_once "noticeManager.php";
require_once "sec.php";
$nm = new noticeManager();
$sec = new sec();
$tablePage = 1;
$keepTablePage = $tablePage;
$cnt = 0;
//ユーザーの投稿済みレス情報一覧
    //<a>タグで渡していたフォルダーIDを取得
    //if(isset($_GET['userId'])){
if($_GET['flag'] == true){
    $result = $rm->getRessInf($_SESSION['userId']);
}elseif(isset($_POST['favoriteUserId2'])){
        //$userId = $rm->getRessInf($_GET['userId']);
    $result = $rm->getRessInf($_POST['favoriteUserId2']);
    }else{
        echo '表示できませんでした。';
        exit;
    }
$resultCnt = ceil(count($result)/7);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link href="sureddo_style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="./ressInf_style.css" type="text/css">
        <link rel="stylesheet" href="./simplePagination.css" type="text/css">
        <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="./js/jquery.simplePagination.js"></script>
        <title>Infarmation</title>
    </head>
    <script>
        $(function () {
            $('#paging').pagination({
                items: <?php echo $resultCnt;?> ,
                itemsOnpage:7,
                cssStyle: 'light-theme',
                prevText: "前へ",
                nextText: "次へ",
                onPageClick: function (pageNumber) {
                show(pageNumber)
            }
                                    })
        });

        function show(pageNumber) {
            var page = "#page-" + pageNumber;
            $('.selection').hide()
            $(page).show()
        }
    </script>
    <header>
        <div id="header">
            <div style="text-align:center;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <body>
        
        <?php
            //検索に一致したレス情報をすべて表示する
            //$rm->displayRess($result);
        ?>
        <p class="title">投稿済みレス</p>
            <div class="selection" id="page-<?php echo $tablePage; ?>">
        <?php
        if(!empty($result)){
            foreach($result as $result){
                $threadResult = $nm->getThreadInf($result->threadId);
                
                    $ressText = $result->ressText;
                

                if($keepTablePage != $tablePage){
                    echo '<div class="selection" id="page-'. $tablePage.'"border="1">';
                    $keepTablePage = $tablePage;
                }
        ?>
                <table  cellspacing="0" rules="rows" >
                    <tr class=tr1>
                        <td>
                            <?php echo $threadResult[0]->threadTitle; ?>
                        </td>
                        <td class="R">
                            投稿日時:<?php $date = new DateTime($result->rightDay);echo $date->format('Y-m-d');?>
                        </td>
                    </tr>
                    <tr class="tr2">
                        <td class="text"><?php echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$threadResult[0]->threadId.'">'.$ressText.'</a>'; ?></td>
                        <td class="R">
                    </tr>
                </table>
        <?php
                $cnt++;
                if($cnt % 7 == 0){
                    echo "</div>";
                    $tablePage = $tablePage + 1;
                }
            }
        }
        ?>
            </div>
            <br>
        
        <div style="padding-left:410px;" id="paging"></div> 
    </body>

</html>