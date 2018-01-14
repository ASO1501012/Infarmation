<?php
session_start();
require_once './dbManager.php';
require_once './RegistManager.php';
require_once './DBRegistTbl.php';
require_once './clipBoardManager.php';
$favcolor = "#FFFFFF";//白 #FFFFFF
if(isset($_SESSION['userId']) and !isset($_SESSION['delPass'])){
    $_SESSION['delPass'] = "1234";
}


if(isset($_SESSION['userId'])){
    $favoriteUserId = $_POST['favoriteUserId'];
}
$cm = new clipBoardManager();
$db = new dbManager();
$rm = new RegistManager();

//フォルダーの読み込み処理
$folderList = $cm->loadFolder($_SESSION['userId']);

$ti = $_GET['id'];
$db_res = $db->getResList($ti);
$db_ti = $db->serchthread($ti);
$i = 0;

if (isset( $_SESSION['userId']) && isset($_GET['ressId']) && isset($_GET['goodDay'])){
    $gm->goodcount($_SESSION['userId'], $_GET['ressId'], $_GET['goodDay']);
}


if (isset($_SESSION['userId']) /*&& isset($favoriteUserId)*/ && isset($_POST['flg'])){
    $t = $rm->userSerch($_SESSION['userId'],"1");
}

if (isset( $_SESSION['userId']) && isset($favoriteUserId)){
    $test = $rm->flg($_SESSION['userId'], $favoriteUserId);
    if($test == 1){
        $favcolor = "#FFFF66";//黄色 #FFFF66
    }
}

if (isset($ressId)){$ressId = $_GET['ressId'];}
if (isset($_SESSION['userId'])){$userId = $_SESSION['userId'];}
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
        <title><?php echo $db_ti[0]->threadtitle; ?></title>
        <!--<link href="style.css" rel="stylesheet" type="text/css">-->
        <link href="sureddo_style.css" rel="stylesheet" type="text/css">

        
    </head>
    <body>
        <header>
            <div id="header">
                <div id="inheader">
                <form method="POST" action="ResponsePage.php">
                    <input type="hidden" name="threadId" value="<?php echo $ti?>">
                    <button type="submit" onclick="location.href='./ResponsePage.php'" id="new">
                    <p style="color:#ffffff; display:inline;">投稿</p><img src="img/sinki2.png" alt="投稿" style="margin-left:5px;"></button>
                    </form>
                </div>
            </div>
        </header>
        <article>
            <p class="title"><?php echo $db_ti[0]->threadtitle; ?></p>
            <?php foreach($db_res as $lists) : ?>
            <table  cellspacing="0" rules="rows">
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
                
                
                <div class="row2">
                    <div class="L">
                        <?php
                        echo $lists->resid . ":";
                        if($lists->delflg == 1) {
                            $content = '削除された投稿です。';
                        } else {
                            $content = $lists->rescontent;
                        }
                        echo $content;
                        ?>
                    </div>
                    <div class="R">
                        <?php $i = $i + 1;
                        $ressId = $lists->resid;?>
                    <body>
                        <div>
                            <button id="good" value="<?php echo $ressId; ?>" type="" onSubmit="return false;" style="background-color: <?php echo $color; ?>">いいね</button>

                        </div>
                        <div>
                            <form method="post" action="">
                                <select><?php $cm->displayPullD($folderList);?></select>
                            </form>
                        </div>
                        </div>
                    
                </div>
                
                
                <tr class="tr2">
                    <?php
                    echo $lists->resid . ":";
                    if($lists->delflg == 1) {
                        $content = '削除された投稿です。';
                    } else {
                        $content = $lists->rescontent;
                    }
                    echo $content;
                    ?>
                    <td class="text"><?php echo $content; ?></td>
                    <td class="R">
                       
                        <form style="display:inline;" method="get" action="ThreadPage.php">
                            <input type="hidden" name="id" value="<?php echo $ti; ?>">
                            <input type="hidden" name="goodDay" value="<?php echo $goodDay; ?>">
                            <input type="hidden" name="ressId" value="<?php echo $lists->resid; ?>">
                            <input type="submit" value="" id="submit" style="background-color:'.$color.'">
                        </form>
                        
                        <form style="display:inline;" method="post" action="delres.php">
                            <input type="hidden" name="threadId" value="<?php echo $ti?>">
                            <input type="hidden" name="flg" value="0">
                            <input type="hidden" name="delPass" value="<?php echo $lists->delpass;?>">
                            <input type="hidden" name="reshid" value="<?php echo $lists->resid; ?>">
                            <input type="hidden" name="reshcont" value="<?php echo $lists->rescontent; ?>">
                            <?php
                            if($lists->delflg == 0){?>
                                <button name="del"><img src="img/dust.png" alt="削除"></button>
                            <?php}
                            ?>
                        </form>
                        <!-- クリップボード登録 -->
                        <form method="post" action="" style="margin-top:10px;">
                            <select><?php $cm->displayPullD($folderList);?></select>
                        </form>
                        <!--<button type="button" onclick="クリップボードに登録" style="margin-top:10px;">
                            <img src="img/clip.png" alt="クリップ"></button>
                        <button type="button" onclick="返信投稿"><img src="img/reply.png" alt="返信"></button>-->
                    </td>
                </tr>
            </table>
            <?php endforeach ?>
        </article>
                <p><a href="CategoryPage.php">戻る</a></p>
            </body>
    </html>
