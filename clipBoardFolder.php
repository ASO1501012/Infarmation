<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 

require_once "clipBoardManager.php";
require_once "sec.php";
$cm = new clipBoardManager();
$sce = new sec();
$userId = $_SESSION['userId'];
$tablePage = 1;
$keepTablePage = $tablePage;
$tabcnt = 0;


//フォルダーの読み込み処理
$loadResult = $cm->loadFolder($_SESSION['userId']);
$loadResult2 = $loadResult;

//新規作成時の処理
if(!empty($_SESSION['userId']) && !empty($_POST['folderId']) && !empty($_POST['folderName'])){
    $cm->registerFolder($_SESSION['userId'],$_POST['folderId'],$_POST['folderName']);
    header('Location:clipBoardFolder.php');
}

$resultCnt = ceil(count($loadResult)/7);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Infarmation</title>
        <link rel="stylesheet" href="./clip_style.css" type="text/css">
        <link rel="stylesheet" href="./clip_style2.css" type="text/css">
        <link rel="stylesheet" href="./simplePagination.css" type="text/css">
        <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="./js/jquery.simplePagination.js"></script>
    </head>
    <script>
        $(function(){
            $(".panel:not(:first)").hide();
            $("#tabs a").click(function() {
                $(".panel").hide();
                $(".panel").filter(this.hash).show();
                $("#tabs a").removeClass("selected");
                $(this).addClass("selected");
                return false;
            }).filter(":first").click();
        });

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
            <div style="float:left;margin-left:440px;padding-right:160px;">
               <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button></div>
            <div id="create">
                <form action="clipBoardFolder.php" method="post">
                    <p style="color:#ffffff; display:inline;">フォルダー名</p>
                    <input type="text" name="folderName">
                    <input type="submit" value="作成" id="createbtn">
                </form>
            </div>
        </div>
    </header>

    <body>
        <article>
            <p class="title">クリップボード</p>
        <?php
        if(!empty($loadResult2)){
            //<ul>を先に全部表示させるために一度実行する。?>
            <div id="clipnavi">
            <ul id="tabs"><?php
            foreach($loadResult2 as $loadResult2){
                $tabcnt++;
                $tabId = "tab0".strval($tabcnt);
                $getRessId = $cm->loadRess($loadResult2->folderId);
                $getResult = $cm->getRessData($getRessId);

                if(strlen($loadResult2->folderName) >= 45){
                    $folderName = substr($loadResult2->folderName,0,90) . "...";
                }else{
                    $folderName = $loadResult2->folderName;
                }?>
            <li><a href="#<?php echo $tabId; ?>"><?php echo $folderName; ?></a></li>
                <?php
            } $tabcnt=0;?>
            </ul>
            </div>
            <?php
            foreach($loadResult as $loadResult){
                $tabcnt++;
                $tabId = "tab0".strval($tabcnt);
                $cnt = 0;
                //表示させるレス情報の読み込み
                $getRessId = $cm->loadRess($loadResult->folderId);
                $getResult = $cm->getRessData($getRessId);

                if(strlen($loadResult->folderName) >= 45){
                    $folderName = substr($loadResult->folderName,0,90) . "...";
                }else{
                    $folderName = $loadResult->folderName;
                }

                //取得してきたレスの件数分、clipBoardInfで表示させる。
                $dbm = new dbManager();
                $sec = new sec();
                //echo $folderName;
        ?>    
                <?php
                if(!empty($getResult)){ ?>
            <div id="<?php echo $tabId; ?>" class="panel">
                <table class="selection" id="page-<?php echo $tablePage; ?>" border="1">
                <?php foreach($getResult as $getResult){
                    if(strlen($getResult->ressText) >= 45){
                        $ressText = substr($getResult->ressText,0,90) . "...";
                    }else{
                        $ressText = $getResult->ressText;
                    }
                    if($keepTablePage != $tablePage){
                        echo '<table class="selection"　id="page-'. $tablePage.'"border="1">';
                        $keepTablePage = $tablePage;
                    }
                    //$result = $dbm->getRessThreadInf($getResult->ressId);
                    $kekka = ("ID:".$getResult->ressId." ".$getResult->ressText);
                    //echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$getResult->threadId.'">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
                ?>
                    <tr class=tr1>
                        <td><?php echo $getResult->userId; ?></td>
                        <td class="R" align="right">
                            <?php echo $getResult->rightDay; ?>
                        </td>
                    </tr>
                    <tr class="tr2">
                        <td class="text" colspan="2">
                            <?php
                    echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$getResult->threadId.'">'.$sec->hsc($kekka).'</a>'
                            ?>
                    </td>
                </tr>

                <?php
                    if($cnt != 0 and $cnt % 6 == 0){
                        echo "</table>";
                        $tablePage = $tablePage + 1;
                    }
                    $cnt++;
                }
                ?>
            </table>
        </div>
        <?php
             }
            }
        }
        ?>
        <div style="padding-left:415px;" id="paging"></div>
        </article>
    </body>
</html>