<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
//最初にロードした時にDBからそのユーザーの情報を取り出しておき、入力フォームのデフォルトの値に各情報を入れておく。かつ、表示。
require_once "userManager.php";
require_once "sec.php";
$um = new userManager();
$sec = new sec();
$userInf = $um->accountInfRoad($_SESSION['userId']);
$userId = $userInf[0]->userId;
$userName = $userInf[0]->userName;
$mailAddress = $userInf[0]->mailAddress;

//入力された値をuserManagerに送る
if(!empty($_POST['userName']) && !empty($_POST['passWord']) && !empty($_POST['passWord2']) && !empty($_POST['mailAddress'])){
    $um->accountManagement($_SESSION['userId'],$_POST['userName'],$_POST['passWord'],$_POST['passWord2'],$_POST['mailAddress']);
    header('Location:main.php');
}
?>
   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"　"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="./registerUser_style.css" type="text/css">
        <title>Infarmation</title>
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
            
            function disp(){
                if(window.confirm('確認ダイアログの表示\n本当によろしいですか？')){
                    location.href="./newPass.php";
                }else{
                    window.alert('キャンセルされました'); 
                }
            }
        </script>
    </head>
    <header>
        <div id="header">
            <div style="text-align:center;margin-top:10px;">
                <button type="button" style="background:none;border:hidden;"onclick="location.href='./main.php'" class="inoutbtn"><img src="./img/logo.png" style="display:inline;" class="logo"></button>                
            </div>
        </div>
    </header>
    <body>
        <div class="outer">
            <p class="title">アカウント変更</p>
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
            <form id="form" action="accountManagement.php" method="post">
                <div class="inner">
                    <table>
                        <tr>
                            <td>
                                <p class="p">ユーザーID</p></td>
                            <td colspan="2"><input type="text" value="<?php echo $sec->hsc($userId); ?>" disabled="disabled" placeholder="4文字以上8文字未満" pattern='{4,8}' onblur='this.classList.add("focused")' required class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">ユーザーネーム</p></td>
                            <td colspan="2"><input type="text" name="userName" value="<?php echo $sec->hsc($userName); ?>" placeholder="ユーザーネーム(アカウント名)" required onblur='this.classList.add("focused")' class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">旧パスワード</p></td>
                            <td colspan="2"><input type="password" name="passWord" placeholder="旧パスワード" pattern='(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}' required class="text">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">新パスワード</p></td>
                            <td colspan="2"><input type="password" id="password" name="passWord2" placeholder="新パスワード" pattern='(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}' onkeyup="setPasswordLevel(this.value);" required class="text"><td><input class="pass" type="text" id="level">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="p">メールアドレス</p></td>
                            <td colspan="2"><input  type="email" name="mailAddress" value="<?php echo $sec->hsc($mailAddress); ?>" placeholder="メールアドレス" required onblur='this.classList.add("focused")' class="text">
                            </td>
                        </tr>
                        <tr>
                            <td colspan=3>
                                <button type="button" onclick="disp()" class="resetbutton">パスワード再発行</button>
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


