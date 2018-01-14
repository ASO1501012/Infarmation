<?php
require_once "ThreadManager.php";
session_start();

if (isset($_POST["threadTitle"]) && isset($_POST["categoryId"]))
{
    $TM = new ThreadManager();
    $TM->CreateThread($_POST["threadTitle"],$_POST["categoryId"]);
} else {
    echo "セッション情報がありません";
}
?>