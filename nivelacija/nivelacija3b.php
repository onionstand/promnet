<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Nivelacija</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php"); 
			/*zvanje sifre kalk*/ 
			$stara_sifra=$_POST['stara_sifra'];
			$stari_porez=$_POST['stari_porez'];
			$br_niv=$_POST['br_niv'];
			$niv_kol=$_POST['niv_kol'];
			echo "Broj nivelacije: " . $br_niv . "<br />";


			if (isset($_POST['ime_rob']) && ($_POST['niv_kol']) && ($_POST['jed_mere']) && ($_POST['porez_pdv']))
			//provera podataka
			  {
			$br_niv=$_POST['br_niv'];
			$pdv=$_POST['porez_pdv'];
			/*prodajna cena*/
			$prod_cena=$_POST['prodajna_cena'];
			/*ruc*/
			$upit = mysql_query("SELECT cena_robe, stanje, ruc FROM roba WHERE sifra='$stara_sifra'");
			$niz = mysql_fetch_array($upit);
			$stanje_s=$niz['stanje'];
			$ruc_s=$niz['ruc'];
			$cena_s=$niz['cena_robe'];
			$umanjeno_stanje=$stanje_s-$niv_kol;


			/*ruc*/
			$iznos_razlika_u_ceni=($prod_cena*$niv_kol)-(($cena_s/100)*(100-$ruc_s)*$niv_kol);
			$ruc=$iznos_razlika_u_ceni/(($prod_cena*$niv_kol)/100);

			$iznos_nivelacije=($prod_cena*$niv_kol)-($cena_s*$niv_kol); //za bazu

			$ubacir="INSERT INTO roba (naziv_robe, cena_robe, porez, stanje, jed_mere, ruc)
			VALUES
			('".$_POST['ime_rob']."','".$prod_cena."', '".$_POST['porez_pdv']."', '".$niv_kol."', '".$_POST['jed_mere']."','".$ruc."')";
			mysql_query($ubacir);
			$sifrarobe3 = mysql_insert_id();
			/*dodavanje robe*/

			$upit_nivrob = mysql_query("INSERT INTO niv_robe (br_niv, srob, srob_niv, koli_niv, iznos_niv) VALUES ('$br_niv', '$stara_sifra', '$sifrarobe3', '$niv_kol', '$iznos_nivelacije')");
			if (!$upit_nivrob) {die('Invalid query: ' . mysql_error());}

			$upit_roba = mysql_query("UPDATE roba SET stanje = '$umanjeno_stanje' WHERE sifra='$stara_sifra'");
			if (!$upit_roba) {die('Invalid query: ' . mysql_error());}

			echo "<p>Roba je uneta.</p>";
			echo '<form action="nivelacija4.php" method="post">
			<input type="hidden" name="br_niv" value="';echo $br_niv; echo '"/>
			<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>';


			}
			else { ?>

			<h2>Sva polja moraju biti popunjena!</h2>
			<form action="" method="post">
				<label>Ime robe:</label>
				<input type="text" name="ime_rob"  class="polje_100_92plus4"/>
				<label>Kolicina:</label>
				<input type="text" readonly="readonly" name="niv_kol" value="<?php echo $_POST['niv_kol'];?>" size="6" class="polje_100_92plus4"/>
				<label>Jed. mere:</label>
				<input type="text" name="jed_mere"  class="polje_100_92plus4"/>
				<label>Poreska tarifa:</label>
				<input type="text" name="porez_pdv"  value="<?php echo $stari_porez;?>" class="polje_100_92plus4"/>
				<label>Prodajna cena:</label>
				<input type="text" name="prodajna_cena" class="polje_100_92plus4"/>
				
				<input type="hidden" name="br_niv" value="<?php echo $_POST['br_niv'];?>" class="input"/>
				<input type="hidden" name="stara_sifra" value="<?php echo $stara_sifra;?>" class="input"/>
				<input type="hidden" name="stari_porez" value="<?php echo $stari_porez;?>" class="input"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<?php } ?>
			<div class="cf"></div>
		</div>
	</body>
</html>