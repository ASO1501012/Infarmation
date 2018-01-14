<?php
require_once "./dbManager.php";

class ThreadManager{

    function CreateThread($threadTitle, $categoryId)
    {
        $threadTitle = $_SESSION["ThreadTitle"];
        $categoryId = $_SESSION["Category"];

        $db = new dbManager();
        $insrt = $db->CreateNewThread($threadTitle, $categoryId);
        header("Location: ThreadPage.php");
    }
}
?>
