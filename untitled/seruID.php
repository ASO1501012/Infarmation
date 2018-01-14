
<!DOCTYPE html>
<?php
SESSION_START();

$userId = 'MERIRU';
$_SESSION['userId'] = $userId;

?>

<html lang="ja">
   <head>
	<meta charset="UTF-8">
	<title>お気に入り</title>
   </head>
	<body>
	    <form action="resu.php" method="POST">
		<table style="width:250px; height: 100px; border:1px solid #000000;">
			<tr>
				<td>
					<div align="right">レスID<input type="text" name="ressId" value=""><br></div>
                    <div align="right">ユーザーID<input type="text" name="userId" value=""><br></div>
					<input type="submit" name="ランキング表示" value="前提ボタン">
				</td>
			</tr>
		</table>
	    </form>
	</body>
</html>
