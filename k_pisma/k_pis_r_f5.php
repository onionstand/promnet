<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");
			$br_k_pis=$_POST['br_k_pis'];
			$izzad=$_POST['iznos'];
			$pdv=$_POST['ispor'];
			$odo_rab=$_POST['odo_rab'];
			$partner=$_POST['partner'];
			$sif_firme=$_POST['sif_firme'];
			mysql_query("UPDATE k_pism_r
						SET iznos_f='$izzad', vel_rab_k='$odo_rab', vel_por_k='$pdv', partner='$partner', sif_firme='$sif_firme' 
						WHERE broj_k=$br_k_pis");
			?>
			<p>Broj Knjiznog pisma: <?php echo $br_k_pis;?><br>
				Iznos: <?php echo $izzad;?><br>
				Iznos poreza: <?php echo $pdv;?><br>
				Iznos rabata: <?php echo $odo_rab;?><br>
				Knjizno pismo je zavrseno
			</p>
			<a href="../index.php" class="dugme_zeleno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>