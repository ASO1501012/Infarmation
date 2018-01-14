<?php
require_once "./dbManager.php";

class ThreadManager{

	function CreateThread($threadTitle, $categoryId)
	{
		$db = new dbManager();
		$insrt = $db->CreateNewThread($threadTitle, $categoryId);
		header("Location: CategoryPage.php");
	}
}
?>
