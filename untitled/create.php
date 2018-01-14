<?php
require_once "ThreadManager.php";
session_start();

if (isset($_SESSION["ThreadTitle"]) && isset($_SESSION["Category"]))
{
    $TM = new ThreadManager();
    $TM->CreateThread($_SESSION["ThreadTitle"],$_SESSION["Category"]);
} else {
    echo "セッション情報がありません";
}
?>