<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php"); 
			$sifra_kalk=$_POST['broj_kalkulaci'];
			mysql_query("UPDATE kalk
			SET nabav_vre='$_POST[nab_vr]', pro_vre='$_POST[pro_vr]', ukal_porez='$_POST[por_vr]', odora='$_POST[rab_vr]' 
			WHERE broj_kalk=$sifra_kalk");

			mysql_query("UPDATE pods_kalk SET iznos='$_POST[nab_vr]'
			WHERE b_kalkulacije=$sifra_kalk");

			echo "<p>Kalkulacija je zavrsena.</p>";
			mysql_close($con);
			?>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
			<div class="cf"></div>
		</div>
		<script type="text/javascript">
			window.location = "../index.php"
		</script>
	</body>
</html>