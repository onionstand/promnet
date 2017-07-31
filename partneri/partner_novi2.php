<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Partner nov</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");
			$sql="INSERT INTO dob_kup (naziv_kup, postbr, mesto_kup, ulica_kup, rab_ugo, ziro_rac, ziro_rac2, tel, pib, mat_br)
			VALUES
			('$_POST[naziv_kup]','$_POST[postbr]','$_POST[mesto_kup]','$_POST[ulica_kup]','$_POST[rab_ugo]','$_POST[ziro_rac]', '$_POST[ziro_rac2]', '$_POST[tel]','$_POST[pib]','$_POST[mat_br]')";
			if (!mysql_query($sql,$con)) {
				die('Error: ' . mysql_error());
			}
			echo "<h2>Partner je dodat.</h2>";
			?>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_zeleno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>