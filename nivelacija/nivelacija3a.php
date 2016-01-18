<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Nivelacija</title>
	</head>
	<body>
		<?php require("../include/DbConnection.php"); ?>
		<div class="nosac_glavni_400">
			<?php
			$sifra_robe=$_POST['sifra_robe'];
			$satra_sifra=$_POST['stara_sifra'];

			$stari_porez=$_POST['stari_porez'];
			$porez=$_POST['porez_pdv'];

			$upit = mysql_query("SELECT cena_robe, stanje, ruc FROM roba WHERE sifra='$satra_sifra'");
			$niz = mysql_fetch_array($upit);
			$stanje_s=$niz['stanje'];
			$ruc_s=$niz['ruc'];
			$cena_s=$niz['cena_robe'];


			$upit2 = mysql_query("SELECT cena_robe, stanje, ruc FROM roba WHERE sifra='$sifra_robe'");
			$niz2 = mysql_fetch_array($upit2);
			$stanje_n=$niz2['stanje'];
			$ruc_n=$niz2['ruc'];
			$cena_n=$niz2['cena_robe'];


			$br_niv=$_POST['br_niv'];
			$niv_kol=$_POST['niv_kol'];
			$umanjeno_stanje=$stanje_s-$niv_kol;
			$uvecano_stanje=$stanje_n+$niv_kol;
			/*ruc*/  
			$iznos_razlika_u_ceni_nivel_s=($cena_n*$niv_kol)-(($cena_s/100)*(100-$ruc_s)*$niv_kol);
			$iznos_razlika_u_ceni_stanja=$cena_n*$stanje_n*$ruc_n/100;

			$novaruc=($iznos_razlika_u_ceni_nivel_s+$iznos_razlika_u_ceni_stanja)/((($cena_n*$niv_kol)+($cena_n*$stanje_n))/100);

			$iznos_nivelacije=($cena_n*$niv_kol)-($cena_s*$niv_kol);//za bazu



			//$nabavna_cena=($cena_s/100)*(100-$ruc_s);
			//$novaruc0=($cena_n-$nabavna_cena)/($nabavna_cena/100);
			//$novaruc=(100*$novaruc0)/(100+$novaruc0);
			echo "<p>Nova razlika u ceni je: " . $novaruc . "%</p>";
			if ($porez!=$stari_porez) {echo "<h2>Porez nije jednak!</h2>";}
			/*ruc*/  
			$upit_roba = mysql_query("UPDATE roba SET stanje = '$umanjeno_stanje' WHERE sifra = '$satra_sifra'");
			if (!$upit_roba) {die('Invalid query: ' . mysql_error());}
			
			$upit_roba2 = mysql_query("UPDATE roba SET stanje = '$uvecano_stanje' WHERE sifra = '$sifra_robe'");
			if (!$upit_roba2) {die('Invalid query: ' . mysql_error());}
			
			$upit_roba3 = mysql_query("UPDATE roba SET ruc = '$novaruc' WHERE sifra = '$sifra_robe'");
			if (!$upit_roba3) {die('Invalid query: ' . mysql_error());}
			
			$upit_roba4 = mysql_query("INSERT INTO niv_robe (br_niv, srob, srob_niv, koli_niv, iznos_niv) VALUES ('$br_niv', '$satra_sifra', '$sifra_robe', '$niv_kol', '$iznos_nivelacije')");
			if (!$upit_roba4) {die('Invalid query: ' . mysql_error());}
			?>
			<form action="nivelacija4.php" method="post">
				<input type="hidden" name="br_niv" value="<?php echo $br_niv; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>