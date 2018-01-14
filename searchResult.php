<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "keySearchManager.php";
require_once "noticeManager.php";

$km = new keySearchManager();
$result = $_SESSION['searchResult'];
$resultType = $_SESSION['resultTypeId'];
$nm = new noticeManager();
$tablePage = 1;
$keepTablePage = $tablePage;
$cnt = 0;

if($resultType == 0){
    typeDivide($result);
}else if($resultType == 1){
    typeDivide($result);
}else if($resultType == 2){
    typeDivide($result);
}
function keySearch($typeId,$keyWord){
    $dbm = new dbManager();
    $searchResult =$dbm->keySearch($typeId,$keyWord);
    $_SESSION['searchResult'] = $searchResult;
    $listlength = count($searchResult);

    if($listlength >= 1){
        header('Location: searchResult.php');
    }else{
        //検索結果なし
        header('Location: main.php');
    }
}

function typeDivide($serachResult){
    $sec = new sec();
    $result = $serachResult;
    $resultType = $_SESSION['resultTypeId'];
    if($resultType == 0){
        foreach($result as $result){
            $kekka = ("ID:".$result["threadId"]." ".$result["threadTitle"]);
            //echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
        }
    }else if($resultType == 1){
        foreach($result as $result){
            $kekka = ("ID:".$result["ressId"]." ".$result["ressText"]);
            //echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
        }
    }else if($resultType == 2){
        foreach($result as $result){
            $kekka = ("ID:".$result["folderId"]." ".$result["folderName"]);
            //echo '<a href="http://localhost/jk2/zenkikaihatu/main.php">'.$sec->hsc($kekka).'</a>'.nl2br("\n");
        }
    }
}
$resultCnt = ceil(count($result)/7);
?>
   

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Infarmation</title>
        <link rel="stylesheet" href="./search_style.css" type="text/css">
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
        <p class="title">検索画面</p>
        <table class="selection" id="page-<?php echo $tablePage; ?>" border="1">
            <?php
            if(!empty($result)){
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーースレッド検索ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
                if($resultType == 0){
                    foreach($result as $result){
                        if(strlen($result["threadTitle"]) >= 45){
                            $threadTitle = substr($result["threadTitle"],0,90) . "...";
                        }else{
                            $threadTitle = $result["threadTitle"];
                        }

                        if($keepTablePage != $tablePage){
                            echo '<table class="selection" id="page-'. $tablePage.'"border="1">';
                            $keepTablePage = $tablePage;
                        }
            ?>
                       
                        <tr>
                            <td class="noticetop">
                                <div class="noticebottm">
                                    <?php
                                echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$result["threadId"].'">'.$threadTitle.'</a>'
                                    ?>
                                </div>
                            </td>
                        </tr>

            <?php
                        $cnt++;
                        if($cnt % 7 == 0){
                            echo "</table>";
                            $tablePage = $tablePage + 1;
                        }
                    }
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーレス検索ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
                }elseif($resultType == 1){
                    foreach($result as $result){
                        if(strlen($result["ressText"]) >= 45){
                            $ressText = substr($result["ressText"],0,90) . "...";
                        }else{
                            $ressText = $result["ressText"];
                        }

                        if($keepTablePage != $tablePage){
                            echo '<table class="selection" id="page-'. $tablePage.'"border="1">';
                            $keepTablePage = $tablePage;
                        }
            ?>
            <tr>
                <td class="noticetop">
                    <div class="noticebottm">
                        <?php
                        echo '<a href="http://localhost/jk2/zenkikaihatu/ThreadPage.php?id='.$result["threadId"].'">'.$ressText.'</a>'
                        ?>
                    </div>
                </td>
            </tr>

            <?php
                            $cnt++;
                        if($cnt % 7 == 0){
                            echo "</table>";
                            $tablePage = $tablePage + 1;
                        }
                    }
//ーーーーーーーーーーーーーーーーーーーーーーーーーーーーークリップボード検索ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
                }elseif($resultType == 2){
                    var_dump($result);
                    foreach($result as $result){
                        if(strlen($result["folderName"]) >= 45){
                            $folderName = substr($result["folderName"],0,90) . "...";
                        }else{
                            $folderName = $result["folderName"];
                        }

                        if($keepTablePage != $tablePage){
                            echo '<table class="selection" id="page-'. $tablePage.'"border="1">';
                            $keepTablePage = $tablePage;
                        }
            ?>
            <tr>
                <td class="noticetop">
                    <div class="noticebottm">
                        <?php
                        echo '<a href="http://localhost/jk2/zenkikaihatu/clipBoardFolder.php">'.$folderName.'</a>'
                        ?>
                    </div>
                </td>
            </tr>

            <?php
                            $cnt++;
                        if($cnt % 7 == 0){
                            echo "</table>";
                            $tablePage = $tablePage + 1;
                        }
                    }
                }
            ?>
        </table>
        <?php
            }
        ?>
        <div style="padding-left:415px;" id="paging"></div>

    </body>
</html>
