<?php
//エラーチェックを行う
function error_check($threadTitle) {
    //エラーフラグ(0=エラーなし、1=エラーあり)とエラーメッセージを保存する変数
    $error = 0;
    $error_mes = "";


    //タイトルのエラーチェック
    if($threadTitle == "") {
        $error=1;
        $error_mes .="タイトルを入力してください。<br>";
    } else if(strlen($threadTitle) > 30) {
        $error=1;
        $error_mes .="タイトルは30文字以内で入力してください。<br>";
    }

    //エラーがあった場合
    if($error){
        //エラーチェックした内容をセッションに保存する。
        $_SESSION["error_mes"] = $error_mes;

        //入力フォームへ遷移させる。
        header("Location: CategoryPage.html");
        exit();
    }
}

?>