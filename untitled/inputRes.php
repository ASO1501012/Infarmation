<?php
require_once "ResponseManager.php";
session_start();

if (isset($_POST["resContent"]) && isset($_POST["threadId"]) &&  isset($_SESSION['delPass']))
{
    $RM = new ResponseManager();
    $RM->ResponseContribution($_POST["resContent"],$_POST["threadId"],$_SESSION['delPass']);
    unset($_SESSION['delPass']);
} else {
    echo "セッション情報がありません";
}
?>
