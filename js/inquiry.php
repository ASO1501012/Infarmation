<?php
// レスポンスデータを定義します。
$res = array('is_success' => false);

// 各POSTデータを取り出します。
$userId = array_key_exists('userId', $_POST) ? trim($_POST['userId']) : '';
$userName = array_key_exists('userName', $_POST) ? trim($_POST['userName']) : '';
$passWord = array_key_exists('passWord', $_POST) ? trim($_POST['passWord']) : '';
$mailAddress = array_key_exists('mailAddress', $_POST) ? trim($_POST['mailAddress']) : '';

// エラーメッセージを格納する配列を定義します。
$errors = array();

if ($userId === '') {
$errors[] = array('classname' => 'userId', 'message' => 'IDを入力してください。');
}
if ($userName === '') {
    $errors[] = array('classname' =>  'userName', 'message' => '名前を入力してください。');
}
if ($passWord === '') {
$errors[] = array('classname' => 'passWord', 'message' => 'パスワードを入力してください。');
}
if ($mailAddress === '') {
    $errors[] = array('classname' =>  'mailAddress', 'message'  => 'メールアドレスを入力してください。');
}

if (count($errors) == 0) {
// エラーが無い場合
$res['is_success'] = true;
} else {
// エラーがある場合は、レスポンスデータに追加します。
$res['errors'] = $errors;
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($res);