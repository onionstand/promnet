<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Faktura</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnectionPDO.php");
			$brojfak=$_POST['broj_profak'];
			$izzad=$_POST['izzad'];
			$ispor=$_POST['ispor'];
			$odo_rab=$_POST['odo_rab'];
			echo "<p>";
			echo "Broj Profakture: " . $brojfak . "<br>";
			echo "Iznos zaduzenja: " . $izzad . "<br>";
			echo "Iznos poreza: " . $ispor . "<br>";
			echo "Odobren rabat: " . $odo_rab . "<br>";
			echo "</p>";


			$upit_profak_unos = "UPDATE profak
								SET izzad=:izzad, ispor=:ispor, odo_rab=:odo_rab
								WHERE broj_prof=:brojfak";
			$stmt_profak_unos = $baza_pdo->prepare($upit_profak_unos);

			$stmt_profak_unos->bindParam(':izzad', $izzad, PDO::PARAM_STR);
			$stmt_profak_unos->bindParam(':ispor', $ispor, PDO::PARAM_STR);
			$stmt_profak_unos->bindParam(':odo_rab', $odo_rab, PDO::PARAM_STR);
			$stmt_profak_unos->bindParam(':brojfak', $brojfak, PDO::PARAM_STR);
			$stmt_profak_unos->execute() or die(print_r($stmt_profak_unos->errorInfo(), true));
			
			
			echo "<h2>Profaktura je zavrsena.</h2>";
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