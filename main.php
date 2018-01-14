<?php
session_start();
if(isset($_GET['userId'])){
    $_SESSION['userId'] = $_GET['userId'];
}
//$_SESSION['userId'] = 'Garudo24';
if(isset($_POST['typeId']) && !empty($_POST['keyWord'])){
    require_once "keySearchManager.php";
    $km = new keySearchManager();
    $km->keySearch($_POST['typeId'],$_POST['keyWord']);
    $result　= $_SESSION['searchResult'];
    header('Location:searchResult.php');
}

//最初にロードした時にDBからそのユーザーの情報を取り出しておき、新しい返信が来ていれば表示。
require_once "./noticeManager.php";
require_once "./categoryManager.php";
$nm = new noticeManager();
$cm = new categoryManager();
//最初にユーザーのすべてのレスIDを取得
//そのレスIDが親IDに指定されているレスのIDと件数を取得する
if(isset($_SESSION['userId'])){
    $result = $nm->getNoticeRessInf($_SESSION['userId']);
    $noticeTrigger = count($result);
    //echo $noticeTrigger;
}

//すべてのカテゴリーIDとカテゴリーNameを持ってくる
$categoryInfResult = $cm->getCategoryInf();
$cnt = 1;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Infarmation</title>
        <link href="./main.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="./simplePagination.css" type="text/css">
    </head>
    <script src="./js/jquery-1.8.3.js"></script>
    <script src="./js/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="./js/script2.js"></script>
    <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery.simplePagination.js"></script>
    <body>
        <header>
            <div id="header">
                <div id="loginlogout">
                   <?php
                       if(!isset($_SESSION['userId'])){ ?>
                        <button type="button" onclick="location.href='./registerUser.php'" class="inoutbtn">新規登録</button>
                        <button type="button" onclick="location.href='./loginProcess.php'" class="inoutbtn">ログイン</button>
                    <?php }else{ ?>
                           <button type="button" onclick="location.href='./logout.php'" class="inoutbtn">ログアウト</button>
                            <h5><?php echo $_SESSION['userId']; ?></h5>
                            <h5 id="test"></h5>
                        <?php } ?>
                </div>
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>
                <div id="kensaku">
                    <form action="main.php" method="post">
                        <select name="typeId">
                            <option value="0">スレッド検索</option>
                            <option value="1">レス検索</option>
                            <!--<option value="2">クリップボード検索</option>-->
                        </select>
                        <input type="search" name="keyWord" placeholder="キーワード">
                        <input type="submit" class="button" value="検索">
                    </form>
                </div>
            </div>
        </header>
        
        <div class="fixed" id="ranking">
            <p style="margin:0;">いいね数RANKING</p>
            <div>
                <p class="m"><img src="img/no1.png">１位</p>
                <a href="http://localhost/jk2/zenkikaihatu/threadPage.php?id=8"><p class="s" style="margin-top:-3px;">根腐病は原因菌（フザリウム菌）により発病します。発病適温は25～30度で高温期に発生しやすい病気です。初期症状は外葉の一部が葉の縁側から萎凋して黄色に変色します。やがて株全体が萎れてしまいます。レタスなどは軽症の株は枯死しませんが、結球・・・
                    </p></a>
                <p class="s" align="right">いいね数:821</p>
            </div>
            <div>
                <p class="m"><img src="img/no2.png">２位</p>
                <a href="http://localhost/jk2/zenkikaihatu/threadPage.php?id=13"><p class="s" style="margin-top:-3px;">普通の玉ねぎと新玉ねぎの一番の違いは食べ方にあります。普通の玉ねぎは、炒め物や煮込み料理向き。新玉ねぎは、サラダなど生食向き。なぜなら、新玉ねぎは水分が多く柔らかい。また、辛味成分が少ないのでサラダでも十分食べられる。逆に普通の・・・</p></a>
                <p class="s" align="right">いいね数:756</p>
            </div>
            <div>
                <p class="m"><img src="img/no3.png">３位</p>
                <a href="http://localhost/jk2/zenkikaihatu/threadPage.php?id=13"><p class="s" style="margin-top:-3px;">紫がかったキャベツ。それは、低温に当たることでアントシアニン色素を生成するから。
                    アントシアニンとはポリフェノールの１種。目に良いと言われている成分です。そしてコレが、美味しいきゃべつのサインになる訳です！なぜなら、寒さの中で育った・・・</p>
                    <p class="s" align="right">いいね数:521</p></a>
            </div>
        </div>

        <div>
            <article class="a1">
                <div class="mypage" onclick="clicka();">
                    <img class="cloud" src="img/sun.png" alt="太陽" width="300px">
                    <span class="my">マイページ</span>
                </div>
            <div id="tabs">
                <!--五個つくりま～す-->
                <a href="#c0"><div id="c1" class="movecloud" onclick="clickc(this.id);">
                    <img class="cloud" src="img/cloud.png" alt="雲" width="136px">
                    <span class="category"><?php echo $categoryInfResult[0]->categoryName; ?></span>
                </div></a>
                <a href="#c1"><div id="c2" class="movecloud" onclick="clickc(this.id);">
                    <img class="cloud" src="img/cloud.png" alt="雲" width="136px">
                    <span class="category"><?php echo $categoryInfResult[1]->categoryName; ?></span>
                </div></a>
                <a href="#c2"><div id="c3" class="movecloud" onclick="clickc(this.id);">
                    <img class="cloud" src="img/cloud.png" alt="雲" width="136px">
                    <span class="category"><?php echo $categoryInfResult[2]->categoryName; ?></span>
                </div></a>
                <a href="#c3"><div id="c4" class="movecloud" onclick="clickc(this.id);">
                    <img class="cloud" src="img/cloud.png" alt="雲" width="136px">
                    <span class="category"><?php echo $categoryInfResult[3]->categoryName; ?></span>
                </div></a>
                <a href="#c4"><div id="c5" class="movecloud" onclick="clickc(this.id);">
                    <img class="cloud" src="img/cloud.png" alt="雲" width="136px">
                    <span class="category"><?php echo $categoryInfResult[4]->categoryName; ?></span>
                </div></a>
            </div>
                <img src="img/wood.png" class="wood" draggable="false" alt="木">
            </article>

            <article class="a2">
               <?php if(isset($_SESSION['userId'])){ ?>
                <p class="ff">お気に入り</p>
                <a href="http://localhost/jk2/zenkikaihatu/favorite.php"><img  class="icononwood" id="favoritemanager" src="img/apple.png" alt="りんご" height="128"width="135px"></a>
                <p class="cf">クリップボード</p>
                <a href="http://localhost/jk2/zenkikaihatu/clipBoardFolder.php"><img  class="icononwood" id="toclipboard" src="img/apple.png" alt="りんご" height="128"width="135px"></a>
                <p class="af">アカウント管理</p>
                <a href="http://localhost/jk2/zenkikaihatu/accountManagement.php"><img  class="icononwood" id="accountmanager" src="img/apple.png" alt="りんご" height="128"width="135px"></a>
                <p class="pf">投稿済みレス</p>
                <a href="ressInfPage.php?flag=true"><img  class="icononwood" id="tomycon" src="img/apple.png" alt="りんご" height="128"width="135px"></a>
                <!--通知が来ていなかったときの処理
<img class="icononwood" id="notification" src="img/notificationoff.png" alt="巣箱" height="162px"width="158px">-->
                <a href="http://localhost/jk2/zenkikaihatu/noticeProcess.php"><img class="icononwood" id="notification" src="img/notificationon.png" alt="巣箱" height="162px"width="158px"></a>
                
                <?php } ?>
            </article>
            <hr class="kusa" />
        </div>

       
        <?php
        //$cloudId = $_POST['cloudId'];
        $cloudId = "c";
        $tablePage = 1;
        $keepTablePage = $tablePage;
        //$vege
        $cnt = 0;
        $categoryId = substr($cloudId,1,1);
        $threadInfResult1 = $cm->getCategoryThreadInf("1");
        $threadInfResult2 = $cm->getCategoryThreadInf("2");
        $threadInfResult3 = $cm->getCategoryThreadInf("3");
        $threadInfResult4 = $cm->getCategoryThreadInf("4");
        $threadInfResult5 = $cm->getCategoryThreadInf("5");
        $resultCnt = ceil(count($threadInfResult1)/10);
        ?>
        
        <div class=articlecolor>
            <article class="a3">
                <!-- PHPでループ出力　スレタイ10個
<button type="button" onclick="（スレッド画面に移動function引数掲示板ID）" class="gothreadbtn(1~10で++)" id="PHPでＩＤ記入">スレタイ</button>
-->
                <div class="gothreadbtn">
                    
                        <?php
                        for($cnt2 = 0;$cnt2 < 6;$cnt2++){
                            $cloudId = "c".strval($cnt2);
                            if($cnt2 == 0){
                                $threadInfResult = $threadInfResult1;
                            }elseif($cnt2 == 1){
                                $threadInfResult = $threadInfResult2;
                            }elseif($cnt2 == 2){
                                $threadInfResult = $threadInfResult3;
                            }elseif($cnt2 == 3){
                                $threadInfResult = $threadInfResult4;
                            }elseif($cnt2 == 4){
                                $threadInfResult = $threadInfResult5;
                            }
                        if(!empty($threadInfResult)){
                            //$resultCnt = ceil(count($threadInfResult)/10);?>
                        <div id="<?php echo $cloudId; ?>" class="panel">
                            <div class="selection" id="page-<?php echo $tablePage; ?>"><?php
                            $threadcnt = 1;
                            foreach($threadInfResult as $threadInfResult){  
                                $threadNameId = "threadname".strval($threadcnt);
                                $imgName = "img".strval($threadcnt);
                                $gothreadbtn = "gothreadbtn".strval($threadcnt);
                                if(strlen($threadInfResult->threadTitle) >= 20){
                                    $threadName = substr($threadInfResult->threadTitle,0,40) . "...";
                                }else{
                                    $threadName = $threadInfResult->threadTitle;
                                }

                                if($keepTablePage != $tablePage){
                                    echo '<div class="selection" id="page-'.$tablePage.'">';
                                    $keepTablePage = $tablePage;
                                }
                        ?>
                        <button style="background:none;border:hidden;" type="button" onclick="location.href='./ThreadPage.php?id=<?php echo $threadInfResult->threadId; ?>'" class="<?php echo $gothreadbtn; ?>" id="<?php echo $threadInfResult->threadId; ?>">
                            <img src="./img/<?php echo $imgName; ?>.png" alt="ブロッコリー">
                            <span class="<?php echo $threadNameId; ?>"><?php echo $threadName; ?></span>
                        </button>
                            <?php
                                    $threadcnt++;
                                    $cnt++;
                                    if($cnt != 0 and $cnt % 10 == 0){
                                        echo "</div>";
                                        $tablePage = $tablePage + 1;
                                    }
                                    if($threadcnt % 6 == 0){
                                        $threadcnt = 1;
                                    }
                                }
                            unset($threadInfResult);
                            $tablePage = 1;
                            $keepTablePage = $tablePage;
                                ?>  </div></div>
                        <?php
                        }?><?php
                        }
                        ?>
                    </div>
            </article>
        </div>
        <div style="margin-top:90px;padding-left:640px;" id="paging"></div>
    </body>
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
                itemsOnpage:10,
                cssStyle: 'light-theme',
                prevText: "前へ",
                nextText: "次へ",
                onPageClick: function (pageNumber) {
                show(pageNumber);
            }
                                    })
        });

        function show(pageNumber) {
            var page = "#page-" + pageNumber;
            $('.selection').hide();
            $(page).show();
        }

        function clickc(id){
            $('html,body').animate({scrollTop:1360},600,'swing');
        }

        function clicka(){
            $('html,body').animate({scrollTop:745},300,'swing');
        }

    </script>
</html>