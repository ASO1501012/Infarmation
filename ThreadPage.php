<?php
session_start();
require_once './dbManager.php';
require_once './RegistManager.php';
require_once './DBRegistTbl.php';
require_once './clipBoardManager.php';
$favcolor = "#FFFFFF";//白 #FFFFFF

if(isset($_SESSION['userId'])){$favoriteUserId = $_SESSION['userId'];}
$cm = new clipBoardManager();
$db = new dbManager();
$rm = new RegistManager();
$tablePage = 1;
$keepTablePage = $tablePage;
$cnt = 0;

//フォルダーの読み込み処理
if(isset($_SESSION['userId'])){
    $folderList = $cm->loadFolder($_SESSION['userId']);
}


$ti = $_GET['id'];
$db_res = $db->getResList($ti);
$db_ti = $db->serchthread($ti);
$i = 0;

if (isset( $_SESSION['userId']) && isset($_GET['ressId']) && isset($_GET['goodDay'])){
    $gm->goodcount($_SESSION['userId'], $_GET['ressId'], $_GET['goodDay']);
}

if (isset($_SESSION['userId']) && isset($favoriteUserId) && isset($_POST['flg'])){
    $t = $rm->userSerch($_SESSION['userId'], $favoriteUserId);
}

if (isset( $_SESSION['userId']) && isset($favoriteUserId)){
    $test = $rm->flg($_SESSION['userId'], $favoriteUserId);
    if($test == 1){
        $favcolor = "#FFFF66";//黄色 #FFFF66
    }
}

if (isset($ressId)){$ressId = $_GET['ressId'];}
if (isset($_SESSION['userId'])){$userId = $_SESSION['userId'];}
$resultCnt = ceil(count($db_res)/7);
?>

<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            .star-check{
                background-color: blue;
            }

            .goodbtn2{
                background-color: red;
            }
        </style>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>Infarmation</title>
        <link href="sureddo_style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="./simplePagination.css" type="text/css">
        <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="./js/jquery.simplePagination.js"></script>
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

    <body>
        <header>
            <div id="header">
                <div style="text-align:center;">
                    <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
                </div>
                <div id="inheader" style="margin-top:-30px;">
                    <form method="POST" action="ResponsePage.php">
                        <input type="hidden" name="threadId" value="<?php echo $ti?>">
                        <button type="submit" onclick="location.href='./ResponsePage.php'" id="new">
                            <p style="color:#ffffff; display:inline;">投稿</p><img src="./img/sinki2.png" alt="投稿" style="margin-left:5px;"></button>
                    </form>
                </div>
            </div>
        </header>
        
        
        <article>
            <p class="title"><?php echo $db_ti[0]->threadtitle; ?></p>
            <div class="selection" id="page-<?php echo $tablePage; ?>">
            <?php foreach($db_res as $lists) : ?>
            <?php  
            if($keepTablePage != $tablePage){
                echo '<div class="selection" id="page-'. $tablePage.'"border="1">';
                $keepTablePage = $tablePage;
            }
            ?>
            <table  cellspacing="0" rules="rows" >
                <tr class=tr1>
                    <?php if ($lists->userid == 'null3') {
                        $name = "名無しさん";
                    } else {
                        $name = $lists->userid;
                    }?>
                    <td><?php echo $name; ?>
                        <form action="ThreadPage.php" method="get" style="display:inline;">
                            <input type="hidden" name="ressId" value="<?php echo $lists->resid; ?>">
                            <input type="hidden" name="userId" value="<?php echo $_GET['userId']; ?>">
                            <input type="hidden" name="favoriteUserId" value="<?php echo $lists->userid; ?>">
                            <input type="hidden" name="flg" value="flg">
                            <input type="hidden" name="id" value="<?php echo $ti; ?>">
                            <?php
                            if($lists->delflg == 0){
                                echo '<input type="submit" name="number" value="お気に入り" style="background-color:<?php echo $favcolor;?>">';
                            }
                            ?>
                        </form>
                    </td>
                    <td class="R">
                        投稿日時:<?php $date = new DateTime($lists->writeday);echo $date->format('Y-m-d');?>
                    </td>
                </tr>
                <tr class="tr2">
                    <?php
                    if($lists->delflg == 1) {
                        $content = '削除された投稿です。';
                    } else {
                        $content = $lists->rescontent;
                    }
                    ?>
                    <td class="text"><?php echo $content; ?></td>
                    <td class="R">
                        <!--<form style="display:inline;" method="get" action="ThreadPage.php">-->
                            <input type="hidden" name="id" value="<?php echo $ti; ?>">
                            <input type="hidden" name="goodDay" value="<?php echo $goodDay; ?>">
                            <input type="hidden" name="ressId" value="<?php echo $lists->resid; ?>">
                            <input type="submit" value="" id="submit" style="background-color:'.$color.'">
                        <!--</form>-->

                        <form style="display:inline;" method="post" action="delres.php">
                            <input type="hidden" name="threadId" value="<?php echo $ti?>">
                            <input type="hidden" name="flg" value="0">
                            <input type="hidden" name="delPass" value="<?php echo $lists->delpass;?>">
                            <input type="hidden" name="reshid" value="<?php echo $lists->resid; ?>">
                            <input type="hidden" name="reshcont" value="<?php echo $lists->rescontent; ?>">

                            <button name="del" style="background:none;border:hidden;"><img src="img/dust.png" alt="削除"></button>

                        </form>
                        <!-- クリップボード登録 
                        <form method="post" action="" style="margin-top:10px;">
                            <select><?php $cm->displayPullD($folderList);?></select>
</form>-->
                        <button type="button" onclick="クリップボードに登録" style="margin-top:10px;background:none;border:hidden;">
<img src="img/clip.png" alt="クリップ"></button>
                        <button type="button" style="background:none;border:hidden;"onclick="返信投稿"><img src="img/reply.png" alt="返信"></button>
                    </td>
                </tr>
                </table>
                <?php
                $cnt++;
                if($cnt % 7 == 0){
                    echo "</div>";
                    $tablePage = $tablePage + 1;
                }
                ?>
            <?php endforeach ?>
            </div>
            <br>
        </article>
        <div style="padding-left:630px;" id="paging"></div>           
    </body>
</html>
