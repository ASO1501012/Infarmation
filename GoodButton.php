<!DOCTYPE html>

<?php
require_once './GoodManager.php';
$dolor = "#4682b4";

if (isset( $_POST['userId']) && isset($_POST['ressId'])){
		$GM = new GoodManager();
		$test = $GM->goodcount($_POST['userId'], $_POST['ressId']);
		$u = $test [0];
		$r = $test [1];
		if($u == $_POST['userId'] && $r == $_POST['ressId']){
			$color = "#ee82ee";
		}else{

		}
}


?>
<html lang="ja">
   <head>
	<meta charset="UTF-8">
	<title>スレッド</title>
   </head>
	<body>
	    <form action="GoodButton.php" method="POST">
		<table style="width:250px; height: 100px; border:1px solid #000000;">
			<tr>
				<td>
                    <div align="right">ユーザーID:<input type="text" name="userId"><br></div>
                    <div align="right">レスID:<input type="number" name="ressId"><br></div>
					<?php
//style="width:250px; height:defoult"
						echo '<input type="submit" value="いいね" style="background-color:'.$color.'";>'
						//echo "<input type=\"submit\" value=\"いいね\"
                                                //style=\"background-color:$color;\">"

					?>
				</td>
			</tr>
		</table>
	    </form>
	</body>
</html>
