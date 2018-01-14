

<!DOCTYPE html>

<?php
session_start();

$userId = 'TOTORI';
$_SESSION['userId'] = $userId;
?>
<html lang="ja">
   <head>
	<meta charset="UTF-8">
	<title>スレッド</title>
   </head>
	<body>
	    <form action="sure.php" method="POST">
		<table style="width:250px; height: 100px; border:1px solid #000000;">
			<tr>
				<td>
                    <div align="right">レスID:<input type="number" name="responseId"><br></div>
					<button type="submit">前提ID</button>
				</td>
			</tr>
		</table>
	    </form>
	</body>
</html>
