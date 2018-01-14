<?php

// アプリケーション設定
define('CONSUMER_KEY', '987268277342-cl3rsi4qsp2q647kbq6sah040jdlga0u.apps.googleusercontent.com');
define('CONSUMER_SECRET', 'HBd7dIHjMq-jUvvVG-lLJfMG');
define('CALLBACK_URL', 'http://localhost/jk2/zenkikaihatu/oauth.php');

// URL
define('TOKEN_URL', 'https://accounts.google.com/o/oauth2/token');
define('INFO_URL', 'https://www.googleapis.com/oauth2/v1/userinfo');
if(!empty($_GET['code'])){
    $code = $_GET['code'];   
}else{
    $code = $_POST['code'];
}
$params = array(
    'code' => $code,
    'grant_type' => 'authorization_code',
    'redirect_uri' => CALLBACK_URL,
    'client_id' => CONSUMER_KEY,
    'client_secret' => CONSUMER_SECRET,
);

// POST送信
$options = array('http' => array(
    'method' => 'POST',
    'content' => http_build_query($params),
    'header' => implode("\r\n", array(
        'Content-Type: application/x-www-form-urlencoded'
    ))
));

// アクセストークンの取得
$res = file_get_contents(TOKEN_URL, false, stream_context_create($options));

// レスポンス取得
$token = json_decode($res, true);
if(isset($token['error'])){
    echo 'エラー発生';
    exit;
}

$access_token = $token['access_token'];

$params = array('access_token' => $access_token);

// ユーザー情報取得
$res = file_get_contents(INFO_URL . '?' . http_build_query($params));

$result = json_decode($res, true);

if(!empty($result['id']) && !empty($result['name']) && !empty($_POST['passWord']) && !empty($result['email'])){
    require_once "userManager.php";
    $um = new userManager();
    //$um->mailAddressCheck($_POST['mailAddress']);
    $um->registerUser($result['id'],$result['name'],$_POST['passWord'],$result['email']);
    header('Location:main.php');
}
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Googleでログイン</title>
    </head>
    <body>
        <h2>登録ユーザー情報</h2>
        <form action="oauth.php" method="post">
            <div style="width:300px;margin-right:auto;margin-left:auto;padding:30px;border:solid 1px #000000;">
                <table>
                    <tr><td style="width:150px;"><div style="text-align:right">ユーザID:</td><td><input type="text" name="userId" value="<?php echo $result['id']; ?>" size="20"></td></tr>
                    <tr><td style="width:150px;"><div style="text-align:right">ユーザーネーム:</td><td><input type="text" name="userName" value="<?php echo $result['name']; ?>" size="20"></td></tr>
                    <tr><td style="width:150px;"><div style="text-align:right">パスワード:</td><td><input type="pass" name="passWord" size="20"></td></tr>
                    <tr><td style="width:150px;"><div style="text-align:right">メールアドレス:</td><td><input type="text" name="mailAddress" value="<?php echo $result['email']; ?>" size="20"></td></tr>
                    <input type="hidden" name="code" value="<?php echo $code; ?>"
                    <tr><td colspan=2><input type="submit" value="登録" style="background-color:#8181E4;color:#FFFFFF;width:260px;margin-top:50px;margin-left:auto;margin-right:auto;border:0;"></td></tr>
                    <input type="button" value="GoogleAccountで登録する" onclick="location.href='./index.php'">
                </table>
            </div>
        </form>
    </body>
</html>