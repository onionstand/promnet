<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil.css">
	<title>Knjizno pismo robno</title>
</head>
<body>
	<div class="nosac_glavni_400">
		<?php require("../include/DbConnection.php");
		$brojfak=$_POST['broj_fak'];
		$br_k_pis=$_POST['br_k_pis'];
		$izlaz_id=$_POST['izlaz_id'];
		$sif_rob=$_POST['sif_rob'];
		$naziv_robe=$_POST['naziv_robe'];
		$rab_dos=$_POST['rab_dos'];
		$koli_dos=$_POST['k_kol'];

		mysql_query("INSERT INTO k_pism_tr (broj_p, sif_rob_p, kolic_p, rabat_p,id_u_i) 
					VALUES ('$br_k_pis', '$sif_rob', '$koli_dos', '$rab_dos', '$izlaz_id')");

		$dodrob=mysql_query("SELECT * FROM roba
							WHERE sifra='$sif_rob'");
		$row = mysql_fetch_array($dodrob);
		$pretsta=$row['stanje'];
		$robkon=$pretsta+$koli_dos;
		mysql_query("UPDATE roba SET stanje = '$robkon'
					WHERE sifra='$sif_rob'");
		?>
		<p>
			Broj knjiznog pisma: <?php echo $br_k_pis;?><br>
			Faktura: <?php echo $brojfak;?><br>
			Roba: <?php echo $naziv_robe;?><br>
			Kolicina: <?php echo $koli_dos;?><br>
			Novo stanje: <?php echo $robkon;?>
		</p>
		<form action='k_pis_r_f4.php' method='post'>
			<input type='hidden' name='br_k_pis' value='<?php echo $br_k_pis;?>'/>
			<button type='submit' class='dugme_zeleno'>Unesi</button>
		</form>
		<div class="cf"></div>
	</div>
</body>
</html>