<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Blagajna</title>
</head>
<body>
<div class="nosac_glavni_400">
	<?php require("../include/DbConnection.php");
	$broj_blag=$_POST['broj_blag'];
	echo "<h2>Izbrisano.</h2>";
	mysql_query("DELETE FROM blagajna WHERE br_blag='$broj_blag'");
	?>
	<form action="blagizv.php" method="post">
		<button type="submit" class="dugme_zeleno">Dalje</button>
	</form>
	<div class="cf"></div>
</div>
</body>