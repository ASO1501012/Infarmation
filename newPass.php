<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "userManager.php";
$um = new userManager();
$um->sendMail($_SESSION['userId']);

header('Location:accountManagement.php');
?>