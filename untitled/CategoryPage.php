<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>カテゴリーページ</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <p><a href="createPage.html">スレッド作成</a></p>
<?php
require_once "dbManager.php";
$list = new dbManager();
$list_thread = $list->getThread();?>

<?php foreach($list_thread as $lists) : ?>
<table class="thread">
    <tr>
        <td>
        <a href="ThreadPage.php?id=<?php echo $lists->threadid;?>">
        <?php echo $lists->threadid . ":";
              echo $lists->threadtitle; ?>
        </a>
        </td>
        <th scope='row'>
        <?php
        echo "カテゴリー:";
        switch ($lists->categoryid){
            case 0:
                $cate = "病気・災害情報";
                break;

            case 1:
                $cate = "おもしろ野菜";
                break;

            case 2:
                $cate = "雑談";
                break;

            case 3:
                $cate = "おすすめレシピ";
                break;

            case 4:
             $cate = "報告";
                break;
        }
        echo $cate;
        echo "<br>";
        echo "作成日時:";
        $date = new DateTime($lists->createday);
        echo $date->format('Y-m-d');

        ?>
        </th>
    </tr>
</table>
<?php endforeach ?>
<p><a href="inputscreen.html">戻る</a></p>
</body>

</html>
