<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Faktura</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");
			$brojfak=$_POST['broj_profak'];
			$izzad=$_POST['izzad'];
			$ispor=$_POST['ispor'];
			$odo_rab=$_POST['odo_rab'];
			mysql_query("UPDATE profak
						SET izzad='$izzad', ispor='$ispor', odo_rab='$odo_rab'
						WHERE broj_prof=$brojfak");
			echo "<p>";
			echo "Broj Profakture: " . $brojfak . "<br>";
			echo "Iznos zaduzenja: " . $izzad . "<br>";
			echo "Iznos poreza: " . $ispor . "<br>";
			echo "Odobren rabat: " . $odo_rab . "<br>";
			echo "<p>";
			echo "<h2>Profaktura je zavrsena.</h2>";
			mysql_close($con);
			?>
			<br>
			<a href="../index.php" class="dugme_zeleno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
			<script type="text/javascript">
				window.location = "../index.php"
			</script>
		</div>
	</body>
</html>