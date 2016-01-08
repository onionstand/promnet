<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo robno</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php");
			$brojkalk=$_POST['broj_kalk'];
			$br_k_pis=$_POST['br_k_pis'];
			$ulaz_id=$_POST['ulaz_id'];
			$sif_rob=$_POST['sif_rob'];
			$naziv_robe=$_POST['naziv_robe'];
			$rab_kalk=$_POST['rab_kalk'];
			$k_kol=$_POST['k_kol'];
			$kalk_cena=$_POST['kalk_cena'];
			$kol_kalk=$_POST['kol_kalk'];

			mysql_query("INSERT INTO k_pism_tr (broj_p, sif_rob_p, kolic_p, rabat_p,id_u_i) VALUES
			('$br_k_pis', '$sif_rob', '$k_kol', '$rab_kalk', '$ulaz_id')");

			$dodrob=mysql_query("SELECT * FROM roba
					WHERE sifra='$sif_rob'");
			$row = mysql_fetch_array($dodrob);
			$pretsta=$row['stanje'];
			$robkon=$pretsta-$k_kol;
			mysql_query("UPDATE roba SET stanje = '$robkon'
						WHERE sifra='$sif_rob'");
			?>
			<p>
				Broj knjiznog pisma: <?php echo $br_k_pis;?><br>
				Kalkulacija: <?php echo $brojkalk;?><br>
				Roba: <?php echo $naziv_robe;?><br>
				Kolicina: <?php echo $k_kol;?><br>
				Novo stanje: <?php echo $robkon;?>
			</p>
			<form action='k_pis_r_k4.php' method='post'>
				<input type='hidden' name='br_k_pis' value='<?php echo $br_k_pis;?>'/>
				<button type='submit' class='dugme_zeleno'>Unesi</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>