<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Nivelacija</title>
</head> 
<body>
	<div class="nosac_glavni_400">
		<?php require("../include/DbConnection.php"); 
		$br_niv=$_POST['br_niv'];
		$id_niv_robe=$_POST['id_niv_robe'];

		$prestanje=mysql_query("SELECT * FROM niv_robe WHERE id='$id_niv_robe'");
		$prestanje2=mysql_fetch_array($prestanje);
		$koli_niv=$prestanje2['koli_niv'];
		$srob=$prestanje2['srob'];
		$srob_niv=$prestanje2['srob_niv'];

		$robsta=mysql_query("SELECT * FROM roba WHERE sifra='$srob'");
		$robsta2=mysql_fetch_array($robsta);
		$robsta_dodavanje=$robsta2['stanje'];
		$cena_robe_dodavanje=$robsta2['cena_robe'];
		$ruc_dodavanje=$robsta2['ruc'];
		$izmenastanja=$robsta_dodavanje+$koli_niv;

		$robsta4=mysql_query("SELECT * FROM roba WHERE sifra='$srob_niv'");
		$robsta5=mysql_fetch_array($robsta4);
		$robsta_oduzimanje=$robsta5['stanje'];
		$cena_robe_oduzimanje=$robsta5['cena_robe'];
		$ruc_oduzimanje=$robsta5['ruc'];
		$izmenastanja2=$robsta_oduzimanje-$koli_niv;

		$iznos_razlika_u_ceni_nivel_s=$cena_robe_dodavanje*$koli_niv*$ruc_dodavanje/100;
		$iznos_razlika_u_ceni_stanja=$cena_robe_oduzimanje*$robsta_oduzimanje*$ruc_oduzimanje/100;

		$novaruc=($iznos_razlika_u_ceni_stanja-$iznos_razlika_u_ceni_nivel_s)/((($cena_robe_oduzimanje*$robsta_oduzimanje)-($cena_robe_dodavanje*$koli_niv))/100);

		mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$srob'");
		mysql_query("UPDATE roba SET stanje = '$izmenastanja2', ruc = '$novaruc' WHERE sifra='$srob_niv'");
		?>
		<h2>Izbrisano.</h2>
		<p>
			Iznos razlika u ceni nivel. <?php echo $iznos_razlika_u_ceni_nivel_s;?><br>
			Iznos razlika u ceni stanja. <?php echo $iznos_razlika_u_ceni_stanja;?>
		</p>
		<?php
		mysql_query("DELETE FROM niv_robe WHERE id='$id_niv_robe' AND br_niv='$br_niv'");
		?>
		<form action="nivelacija4.php" method="post">
			<input type="hidden" name="br_niv" value="<?php echo $br_niv; ?>"/>
			<button type="submit" class="dugme_zeleno">Dalje</button>
		</form>
		<div class="cf"></div>
	</div>
</body>
</html>