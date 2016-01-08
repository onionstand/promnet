<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Izvod</title>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnection.php");
		$id_bank=$_POST['id_banke'];
		$broj_izvoda=$_POST['broj_izvoda'];
		$id_upl=$_POST['id_upl'];
		$datum=$_POST['datum'];

		if (!mysql_query("DELETE FROM bankaupis WHERE id_upl='$id_upl' AND br_izvoda='$broj_izvoda'"))
		{die('Greska: ' . mysql_error());}
		else
		echo "<h2>Unos je obrisan.</h2>";
		?>
		<form action="izvod5.php" method="post">
		<input type="hidden" name="id_banke" value="<?php echo $id_bank; ?>"/>
		<input type="hidden" name="datum" value="<?php echo $datum; ?>"/>
		<input type="hidden" name="broj_izvoda" value="<?php echo $broj_izvoda; ?>"/>
		<button type="submit" class="dugme_zeleno">Dalje</button>
		</form>
		<div class="cf"></div>
	</div>
</body>
</html>