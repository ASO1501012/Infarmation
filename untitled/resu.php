<!DOCTYPE html>

<?php
SESSION_START();
require_once './RegistManager.php';
require_once './DBRegistTbl.php';
$color = "#FFFFFF";//白 #FFFFFF

$favoriteUserId = $_POST['userId'];




    if (isset($_SESSION['userId']) && isset($favoriteUserId) && isset($_POST['flg'])){
	    $rm = new RegistManager();
	    $t = $rm->userSerch($_SESSION['userId'], $favoriteUserId);
        //var_dump($t);
    }

	if (isset( $_SESSION['userId']) && isset($favoriteUserId)){

		$RM = new RegistManager();
		$test = $RM->flg($_SESSION['userId'], $favoriteUserId);
        //flagの情報を入れた$testを判定して０なら青、１なら赤を$colorに入れる
		if($test == 1){
			$color = "#FFFF66";//黄色 #FFFF66
		}

	}





	$ressId = $_POST['ressId'];
	$userId = $_POST['userId'];



?>
<html lang="ja">
   <head>
	<meta charset="UTF-8">
	<title>スレッド</title>
   </head>
	<body>
	    <form action="resu.php" method="POST">
		<table style="width:250px; height: 100px; border:1px solid #000000;">
			<tr>
				<td>

					<input type="hidden" name="ressId" value="<?php echo $_POST['ressId']; ?>">
					<input type="hidden" name="userId" value="<?php echo $_POST['userId']; ?>">
					<input type="hidden" name="flg" value="flg">
					<input type="submit" name="number" value="お気に入り" style="background-color:<?php echo $color;  ?>">



				</td>
			</tr>
		</table>
	    </form>

	</body>
</html>
<?php

?>
