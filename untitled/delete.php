<?php
session_start();
require_once 'ResponseManager.php';


if ($_POST['flg'] == 1) {
  if ($_POST['delPass'] == $_POST['delPass_conf']){
    $rm = new ResponseManager();
    $del = $rm->DeleteResponse($_POST['reshid'], $_POST['delPass']);
  }
}
?>
