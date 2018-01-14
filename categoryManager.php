<?php
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once "dbManager.php";

class categoryManager{
    public function getCategoryInf(){
        $dbm = new dbManager(); 
        $categoryInfResult = $dbm->getCategoryInf();
        return $categoryInfResult;
    }
    
    public function getCategoryThreadInf($categoryId){
        $dbm = new dbManager();
        $threadInfResult = $dbm->getCategoryThreadInf($categoryId);
        return $threadInfResult;
    }
}
?>