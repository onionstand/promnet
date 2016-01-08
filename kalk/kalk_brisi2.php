<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			$brojkalk=$_POST['broj_kalkulaci'];
			echo "<h2>Izbrisano!</h2>";

			$result = mysql_query("SELECT ulaz.kol_kalk, ulaz.cena_k, ulaz.rab_kalk, ulaz.srob_kal, roba.cena_robe, roba.stanje, roba.ruc, (roba.stanje-ulaz.kol_kalk) AS izmenastanja, roba.sifra 
			FROM ulaz RIGHT JOIN roba ON ulaz.srob_kal=roba.sifra WHERE br_kal='$brojkalk'");
			while($row = mysql_fetch_array($result)){
				$cena_kalk=$row['cena_k'];
				$cena_robe=$row['cena_robe'];
				$rob_ruc=$row['ruc'];
				$prestanje3=$row['kol_kalk'];
				$sifrob=$row['srob_kal'];
				$robsta3=$row['stanje'];
				$izmenastanja=$row['izmenastanja'];
				$rabat2=$row['rab_kalk'];
		
				$kalkcena_min_rab=($cena_kalk/100)*(100-$rabat2);
				$m1=($cena_robe/($kalkcena_min_rab/100))-100;
				$m2=($m1*100)/(100+$m1);
				$novaruc=(($robsta3*$rob_ruc)-($prestanje3*$m2))/($robsta3-$prestanje3);
		
				mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$sifrob'");
				mysql_query("UPDATE roba SET ruc = '$novaruc' WHERE sifra='$sifrob'");
				?>
				<p>
					Stanje robe <?php echo $sifrob;?>: <?php echo $izmenastanja;?><br>
					RUC robe <?php echo $sifrob;?>: <?php echo $novaruc;?>
				</p>
			<?php
			}
			mysql_query("DELETE FROM ulaz WHERE br_kal='$brojkalk'");
			mysql_query("DELETE FROM kalk WHERE broj_kalk='$brojkalk'");
			mysql_query("DELETE FROM pods_kalk WHERE b_kalkulacije='$brojkalk'");

			?>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>