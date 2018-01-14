<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
if(isset($_SESSION['userId'])){
    $_SESSION = array();
    session_destroy();
    header('Location:main.php');
}
header('Location: main.php');
?>