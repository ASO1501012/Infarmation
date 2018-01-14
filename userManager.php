<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once 'dbManager.php';
class userManager{
    //ユーザー登録する際に受け取った値をDBManagerに送る、件数を確認し件数が０でないならすでに登録されているユーザー情報なのでもう一度会員登録を行わせる。
    public function registerUser($userId,$userName,$passWord,$mailAddress){
        $dbm = new dbManager();
        $listcnt = $dbm->getUser($userId,$passWord);
        $listlength = count($listcnt);
        if($listlength == 0){
            $hash = $this->passwordHash($passWord);
            $dbm->registerUser($userId,$userName,$hash,$mailAddress);
            header('Location: main.php');
        }else{
            echo "登録できませんでした。";
            header('Location: registerUser.php');
        }
    }

    /*public function logincheck($userId,$passWord){
        $dbm = new dbManager();
        $um = new UserManager();
        $listcnt = $dbm->getUser($userid);
        $listlength = count($listcnt);
        foreach($listcnt as $list){
            $userName = $list->userName;
        }

        if($listlength >= 1){
            $passcheck = $this->passwordCheck($pass);
            if($passcheck == true){
                $_SESSION['userId'] = $userId;
                $_SESSION['userName'] = $userName;

                session_regenerate_id();
                header('Location:main.php');
            }else{
                //パスワードが不一致
                header('Location: Login.php');
            }
        }else{
            //ユーザーIDが不一致
            header('Location: Login.php');
        }
    }*/

    //パスワードをハッシュ化する
    public function passwordCheck($passWord,$pass){
        $flag = password_verify($passWord,$pass);
        return $flag;
    }

    //パスワードをハッシュ化する
    public function passwordHash($passWord){
        $hash = password_hash($passWord,PASSWORD_DEFAULT);
        return $hash;
    }
    
    //accountManagement.phpで最初にユーザー情報を取得する際に使用、DBManagerにユーザー情報の取得をリクエスト
    public function accountInfRoad($userId){
        $dbm = new dbManager();
        $userInf = $dbm->getUserInf($userId);
        return $userInf;
    }
    
    //アカウント情報の変更をする際にaccountManagement.phpから受け取った引数をDBManagerに渡す。
    public function accountManagement($userId,$userName,$passWord,$passWord2,$mailAddress){
        $hash = $this->passwordHash($passWord2);
        $dbm = new dbManager();
        $result = $dbm->getUserInf($userId);
        if(password_verify($passWord,$result[0]->passWord)){
            $dbm->accountManagement($userId,$userName,$hash,$mailAddress);
        }else{
            header('Location: accountManagement.php');
        }
    }
    
    function mailAddressCheck($mailAddress){
        $mailResult = filter_var($mailAddress, FILTER_VALIDATE_EMAIL);
        if(preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $mailAddress)){
            
        }else{
            echo '正しくないメールアドレスです';
            header('Location: registerUser.php');
        }
    }
    
    public function sendMail($userId){
        $dbm = new dbManager();
        $result = $dbm->getUserInf($userId);
        $mail = $result[0]->mailAddress;
        $newPass = $this->create_passwd();
        $hash = $this->passwordHash($newPass);
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $to      = $mail;
        $subject = 'パスワードの再発行';
        $message = '新しいパスワードはこちらです。ログイン後再設定してください。'."\r\n"."\r\n".'パスワード:'.$newPass
            ."\r\n"."URL:"."http://localhost/jk2/zenkikaihatu/loginProcess.php"."ログイン画面へ";
        $headers = 'From: '. $mail . "\r\n";

        mb_send_mail($to, $subject, $message, $headers);
        
        $dbm->resetPass($userId,$hash);
    }
    
    //パスワード生成
    function create_passwd( $length = 8 ){
        //vars
        $pwd = array();
        $pwd_strings = array(
            "sletter" => range('a', 'z'),
            "cletter" => range('A', 'Z'),
            "number"  => range('0', '9'),
            "symbol"  => array_merge(range('!', '/'), range(':', '?'), range('{', '~')),
        );

        //logic
        while (count($pwd) < $length) {
            // 4種類必ず入れる
            if (count($pwd) < 4) {
                $key = key($pwd_strings);
                next($pwd_strings);
            } else {
                // 後はランダムに取得
                $key = array_rand($pwd_strings);
            }
            $pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
        }
        // 生成したパスワードの順番をランダムに並び替え
        shuffle($pwd);
        //配列ないの文字を連結する
        return implode($pwd);
    }
    
    function registerFolder($userId){
        $dbm = new dbManager();
        $dbm->registerFolder($userId,0,"クリップフォルダ");
    }
    public function registDel($userId, $delPass){
        $dbm = new dbManager();
        $dbm->registDelPass($delPass,$userId);
    }

}
?>
