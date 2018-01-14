<?php
// アプリケーション設定
define('CONSUMER_KEY', '987268277342-cl3rsi4qsp2q647kbq6sah040jdlga0u.apps.googleusercontent.com');
define('CALLBACK_URL', 'http://localhost/jk2/zenkikaihatu/oauth.php');

// URL
define('AUTH_URL', 'https://accounts.google.com/o/oauth2/auth');


$params = array(
    'client_id' => CONSUMER_KEY,
    'redirect_uri' => CALLBACK_URL,
    'scope' => 'https://www.googleapis.com/auth/userinfo.profile email',
    'response_type' => 'code',
);

// 認証ページにリダイレクト
header("Location: " . AUTH_URL . '?' . http_build_query($params));
?>