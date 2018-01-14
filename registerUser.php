<?php
// アップロードしたFacebook SDKのfacebook.phpまでのパス
//require_once("facebook.php");
// appIdとsecretを入力。appIdとsecretはDashboardで確認できます。
//$config = array(
    //'appId' => '366072663794368', 
    //'secret' => '7b92d0f41bdfe0560e97cc171856f09d'
//);
// 下記の様に$configを引数に持たせて、インスタンス化させます
//$facebook = new Facebook($config);

if(!empty($_POST['userId']) && !empty($_POST['userName']) && !empty($_POST['passWord']) && !empty($_POST['passWord2']) && !empty($_POST['mailAddress'])){
    if($_POST['passWord'] == $_POST['passWord2']){
        require_once "userManager.php";
        $um = new userManager();
        //$um->mailAddressCheck($_POST['mailAddress']);
        $um->registerUser($_POST['userId'],$_POST['userName'],$_POST['passWord'],$_POST['mailAddress']);
        $um->registerFolder($_POST['userId']);
        header('Location:main.php');
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="./registerUser_style.css" type="text/css">
        <link rel="stylesheet" href="./js.css">
        <link rel="stylesheet" href="./js.scss">
        <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="./js/passwordchecker.js"></script>
        <script type="text/javascript">
            function setPasswordLevel(password) {
                var level = getPasswordLevel(password);
                var text = "";

                if (level == 1) { text = "弱い";}
                if (level == 2) { text = "やや弱い";}
                if (level == 3) { text = "普通";}
                if (level == 4) { text = "やや強い";}
                if (level == 5) { text = "強い";}

                document.getElementById("level").value = text;
            }
        </script>

        <!-- <script type="text/javascript" src="./js/passwordchecker.js"></script>
        <script type="text/javascript">
            function setPasswordLevel(password) {
                var level = getPasswordLevel(password);
                var text = "";

                if (level == 1) { text = "弱い";}
                if (level == 2) { text = "やや弱い";}
                if (level == 3) { text = "普通";}
                if (level == 4) { text = "やや強い";}
                if (level == 5) { text = "強い";}

                document.getElementById("level").value = text;
            }
        </script>-->
        <title>Infarmation</title>
    </head>
    <header>
        <div id="header">
            <div style="text-align:center;margin-top:10px;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <body>
        <div>
            <p class="title">会員登録</p>
            <!--<!-- FaceBookログイン 
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '366072663794368',
                    xfbml      : true,
                    version    : 'v2.9'
                });
                FB.AppEvents.logPageView();
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <?php // $facebook->getLoginUrl()でログインのURLを生成します。?>
        <a href="<?php //echo $facebook->getLoginUrl();?>">FaceBookログイン</a>
onblur='this.classList.add("focused")'
        <div
             class="fb-like"
             data-share="true"
             data-width="450"
             data-show-faces="true">
</div>-->
            <form id="form" action="registerUser.php" method="post">
                <div>
                    <table>
                        <tr>
                            <td>
                                <p class="p">ユーザーID</p></td>
                            <td colspan="2"><input type="text" name="userId" placeholder="4文字以上8文字未満" pattern='{4,8}' onblur='this.classList.add("focused")' required class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">ユーザーネーム</p></td>
                            <td colspan="2"><input type="text" name="userName" placeholder="ユーザーネーム(アカウント名)" required onblur='this.classList.add("focused")' class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">パスワード</p></td>
                            <td colspan="2"><input type="password" id="password" name="passWord" placeholder="小文字,大文字,数字それぞれを含む8文字以上" pattern='(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}' required  onkeyup="setPasswordLevel(this.value);" class="text">
                                <td><input class="pass" type="text" id="level">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">再入力</p></td>
                            <td colspan="2"><input class="text" type="password" name="passWord2" placeholder="再入力してください" pattern='(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}' onblur='this.classList.add("focused")' required class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">メールアドレス</p></td>
                            <td colspan="2"><input type="email" name="mailAddress" placeholder="メールアドレス" required onblur='this.classList.add("focused")' class="text">
                            </td>
                        </tr>
                        <tr>
                            <td colspan=3>
                                <input class="formbutton" type="submit" value="登　録">
                            </td>
                        </tr>
                        <!--<input type="button" value="GoogleAccountでログインする" onclick="location.href='./index.php'">-->
                    </table>
                </div>
            </form>
        </div>
    </body>

    </html>