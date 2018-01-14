<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Infarmation</title>
        <link rel="stylesheet" href="./favorite_style.css" type="text/css">
    </head>
    <header>
        <div id="header">
            <div style="text-align:center;margin-top:10px;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <body>



        <?php
        SESSION_START();
        require_once './registerManager.php';
        require_once './ressInf.php';
        if(isset($_SESSION['userId']) && isset($_POST['favoriteUserId']) && isset($_POST['deleteflg'])){
            $rm = new registerManager();
            $d = $rm->deleteUserSerch($_SESSION['userId'], $_POST['favoriteUserId']);
        }

        if(isset($_SESSION['userId'])){
            $rm = new registerManager();
            $value = $rm->userList($_SESSION['userId']);
        }
        
        
        
        //$ressId = $_POST['ressId'];
        //$userId = $_POST['userId'];
        
        $userId = $_SESSION['userId'];
        ?>
        <p class="title">お気に入りユーザー</p>
        <?php if(isset($value)){ ?>
            <table>
                <tr>
                <?php foreach($value as $value){ 
                        $rm = new registerManager();
                        $result = $rm->getUserInf($value);
                    ?>
                    <td class="td_top">
                    <form action="favorite.php" method="POST">
                        <input type="hidden" name="favoriteUserId" value=<?php echo $value; ?>>
                        <input type="hidden" name="deleteflg" value="deleteflg">
                        <button type='submit' class="button" name='action'><span><img src="./img/hosi.png"></span><span><img src="./img/hosi_orange.png"></span></button>
                    </form>
                            <p class="p"><?php echo $result[0]->userName; ?></p>
                        <form action="ressInfPage.php" method="POST">
                            <input type="hidden" name="favoriteUserId2" value=<?php echo $value; ?>>
                            <input type="hidden" name="userId" value=<?php echo $userId; ?>>
                            <input type="submit" class="formbutton" name="move"  value="投稿済みレス一覧へ">
                        </form>
                    </td>
                <?php } ?>
                </tr>
            </table>
        <?php }else{ ?>
                お気に入りしているユーザーはいません。    
        <?php } ?>
    </body>
</html>