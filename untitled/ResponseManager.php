<?php
require_once "dbManager.php";

class ResponseManager{
    function ResponseContribution($resContent, $threadId, $delPass){
        $resContent = $_POST["resContent"];
        $threadId = $_POST["threadId"];

        if(isset($_SESSION["userId"])){
            $userId = $_SESSION["userId"];
        }else{
            $_SESSION["userId"] = 'null3';
            $userId = $_SESSION["userId"];
        }

        $db = new dbManager();
        $insrt = $db->RegistResponse($resContent, $threadId, $userId,$delPass);
    }

    function DeleteResponse($resId, $delPass){
      $db = new dbManager();
      $delete = $db->deleteResponse($resId, $delPass);
    }

    }

?>
