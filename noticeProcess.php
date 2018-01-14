<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 

//最初にロードした時にDBからそのユーザーの情報を取り出しておき、新しい返信が来ていれば表示。
require_once "noticeManager.php";
require_once "sec.php";
$nm = new noticeManager();
$sec = new sec();
$tablePage = 1;
$keepTablePage = $tablePage;
$cnt = 0;
//最初にユーザーのすべてのレスIDを取得
//そのレスIDが親IDに指定されているレスのIDと件数を取得する
if(isset($_SESSION['userId'])){
    $result = $nm->getNoticeRessInf($_SESSION['userId']);
}
$resultCnt = ceil(count($result)/7);
?>

    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>通知画面</title>
        <link rel="stylesheet" href="./notice_style.css" type="text/css">
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
        <header>
            <div id="header">
                <div style="text-align:center;margin-top:10px;">
                    <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button> 
                </div>
            </div>
        </header>
    <body>
        <p class="title">通知画面</p>
        <table class="selection" id="page-<?php echo $tablePage; ?>" border="1">
        <?php
            if(!empty($result)){
                foreach($result as $result){
                    $threadResult = $nm->getThreadInf($result->threadId);
                    $userResult = $nm->getUserInf($result->userId);
                    if(strlen($result->ressText) >= 45){
                        $ressText = substr($result->ressText,0,90) . "...";
                    }else{
                        $ressText = $result->ressText;
                    }
                    
                    if($keepTablePage != $tablePage){
                        echo '<table class="selection" id="page-'. $tablePage.'"border="1">';
                        $keepTablePage = $tablePage;
                    }
        ?>
                        <tr>
                            <td class="noticetop">
                                <div class="threadName">
                                    <?php
                                        echo "スレッド名：".$threadResult[0]->threadTitle."　";
                                    ?>
                                </div>
                                <div class="userName">
                                    <?php
                                        echo $userResult[0]->userName."　";
                                    ?>
                                </div>
                                <div class="write">
                                    <?php
                                        echo "書込み日時"."　".$result->rightDay;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-top-style:hidden;">
                            <td class="noticebottm">
                                <?php  http://localhost/jk2/zenkikaihatu/ThreadPage.php?id=2
                    echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$threadResult[0]->threadId.'">'."ID:".$result->ressId.$ressText.'</a>'.nl2br("\n").nl2br("\n"); 
                                    ?>
                            </td>
                        </tr>
                    
                <?php
                    $cnt++;
                    if($cnt % 7 == 0){
                        echo "</table>";
                        $tablePage = $tablePage + 1;
                    }
                }
                ?>
        </table>
        <?php
            }
        ?>
        <div style="padding-left:410px;" id="paging"></div>
    </body>

    </html>