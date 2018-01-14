<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once 'loginManager.php';
if(isset($_SESSION['userId'])){
    header("Location:main.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="./loginProcess_style.css" type="text/css">
        <title>ログイン</title>
    </head>
    <header>
        <div id="header">
            <div style="text-align:center;margin-top:10px;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <body>
        <form id="form" action="loginManager.php" method="POST">
            <p class="title">ログイン</p>
            <table>
                <tr>
                    <td class="td_top">
                        <p class="p">ユーザーID</p>
                        <input type="text" class="text" name="userId"  placeholder="ユーザーIDを入力">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="p">パスワード</p>
                        <input class="text" type="password" name="passWord" placeholder="パスワードを入力">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="formbutton" type="submit" value="ログイン">
                    </td>
                </tr>
            </table>    
        </form>
    </body>
</html>
