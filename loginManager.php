<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "dbManager.php";
require_once "userManager.php";

$cnt = new loginManager();
$cnt->loginProcess();

class LoginManager{
    public function loginProcess(){
        if(isset($_POST["userId"],$_POST["passWord"])){
            $dbm = new dbManager(); //$dbmにDBManagerクラスのインスタンス生成
            $um = new userManager();
            $use = $dbm->getUserInf($_POST["userId"]); // $dbmからDBManagerクラスのgetUserにユーザIDとパスワードを送信帰ってきた値を$useに格納
            $log = count($use);
            if($log >= 1) {
                $passcheck = $um->passwordCheck($_POST["passWord"],$use[0]->passWord);
                if($passcheck == true){
                    $_SESSION['userId'] = $_POST["userId"];
                    session_regenerate_id();
                    header('Location:main.php');
                }else{
                    //パスワードが不一致
                    header('Location: loginProcess.php');
                }
            }else{
                //ユーザーIDが不一致
                header('Location: loginProcess.php');
            }   
        }
    }
}
?>