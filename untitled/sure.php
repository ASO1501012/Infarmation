<!DOCTYPE html>

<?php

require_once './RunkingManager.php';
$goodDay = date("Y-m-d");

$value = " ";
$r = array();

//arrayを作り、過去一ヶ月なら一週間に、過去一週間なら一ヶ月にする。
$c = array("過去一ヶ月"=>"過去一週間", "過去一週間"=>"過去一ヶ月");

//numberに値が入っていなかったらfalce
if(!empty($_POST['number'])){
	$chenge = $c[$_POST["number"]];
}else{
	$chenge = "過去一週間";
}

//numberを型まで完全に一致するようにifする。
if(isset($_POST['categoryId']) && isset($_POST['number']) && $_POST['number'] === "過去一ヶ月"){

		//過去一ヶ月の処理
		if (isset($_POST['categoryId']) && isset($_POST['goodDay'])){
			$gm = new RunkingManager();
			$t = $gm->runkingGetter2($_POST['categoryId'], $_POST['goodDay']);
		}

		if(isset($t)){
			$rt = new RunkingManager();
			$r = $rt->runkingThread2($t, $goodDay);
			//numberの値を捨てる
			unset($number);

		}


}else{
		//過去一週間の処理
		if (isset($_POST['categoryId']) && isset($_POST['goodDay'])){
			$gm = new RunkingManager();
			$t = $gm->runkingGetter($_POST['categoryId'], $_POST['goodDay']);
		}

		if(isset($t)){
			$rt = new RunkingManager();
			$r = $rt->runkingThread($t, $goodDay);

		}
		$number = 1;

}






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

					<input type="hidden" name="goodDay" value="<?php echo $_POST['goodDay']; ?>">
					<input type="hidden" name="categoryId" value="<?php echo $_POST['categoryId']; ?>">
					<input type="submit" name="number" value="<?php echo $chenge; ?>"><br>
					<!--タイトルの表示-->
					<H2 align="left"><?php if($chenge === "過去一週間"){echo "過去一ヶ月";}else{ echo "過去一週間";} ?></H2>
					<?php
						//取ってきた配列を表示
						foreach($r as $value){

							echo "内容:".$value->resContent."  いいね:".$value->goodCnt."<br>";


						}


					?>
				</td>
			</tr>
		</table>
	    </form>
	</body>
</html>
<?php

?>
