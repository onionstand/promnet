<!DOCTYPE html>
<html>
<head><meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Nivelacija</title>
</head> 
<body>
	<div class="nosac_glavni_400">
		<?php require("../include/DbConnection.php"); 
		$br_niv=$_POST['br_niv'];
		$id_niv_robe=$_POST['id_niv_robe'];

		$upit_nivelacija=mysql_query("SELECT * FROM niv_robe WHERE id='$id_niv_robe'");
		$red_nivelacija=mysql_fetch_array($upit_nivelacija);
		$koli_niv=$red_nivelacija['koli_niv'];
		$srob=$red_nivelacija['srob'];
		$srob_niv=$red_nivelacija['srob_niv'];

		$upit_roba_srob=mysql_query("SELECT * FROM roba WHERE sifra='$srob'");
		$red_roba_srob=mysql_fetch_array($upit_roba_srob);
		$robsta_dodavanje=$red_roba_srob['stanje'];
		$cena_robe_dodavanje=$red_roba_srob['cena_robe'];
		$ruc_dodavanje=$red_roba_srob['ruc'];
		$izmenastanja=$robsta_dodavanje+$koli_niv;

		$upit_roba_srob_niv=mysql_query("SELECT * FROM roba WHERE sifra='$srob_niv'");
		$red_roba_srob_niv=mysql_fetch_array($upit_roba_srob_niv);
		$robsta_oduzimanje=$red_roba_srob_niv['stanje'];
		$cena_robe_oduzimanje=$red_roba_srob_niv['cena_robe'];
		$ruc_oduzimanje=$red_roba_srob_niv['ruc'];
		$izmenastanja2=$robsta_oduzimanje-$koli_niv;

		$novaruc=(($robsta_oduzimanje*$ruc_oduzimanje)-($koli_niv*$ruc_dodavanje))/($robsta_oduzimanje-$koli_niv);

		mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$srob'");
		mysql_query("UPDATE roba SET stanje = '$izmenastanja2', ruc = '$novaruc' WHERE sifra='$srob_niv'");
		?>
		<h2>Izbrisano.</h2>
		<p>
			Nova RUC. <?php echo $novaruc;?><br>
			Novo stanje <?php echo $red_roba_srob['naziv_robe']." - ".$red_roba_srob['sifra']." - ".$izmenastanja;?><br>
			Novo stanje <?php echo $red_roba_srob_niv['naziv_robe']." - ".$red_roba_srob_niv['sifra']." - ".$izmenastanja2;?>
		</p>
		<?php
		mysql_query("DELETE FROM niv_robe WHERE id='$id_niv_robe' AND br_niv='$br_niv'");
		?>
		<form action="nivelacija4.php" method="post">
			<input type="hidden" name="br_niv" value="<?php echo $br_niv; ?>"/>
			<button type="submit" class="button_unesi">Dalje</button>
		</form>
		<div class="cf"></div>
	</div>
</body>
</html>