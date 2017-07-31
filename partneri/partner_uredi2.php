<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Partner</title>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");

			$odabpart=$_POST['sifra_p'];
			$upit = "SELECT * FROM dob_kup WHERE sif_kup='$odabpart'"; 
			$partner = mysql_query($upit) or die(mysql_error());
			$parred = mysql_fetch_array($partner) or die(mysql_error());
			?>
			<form action='partner_uredi3.php' method='post'>
				<label>Sifra partnera:</label>
				<input type='text' name='sifra_kup1' class='polje_100_92plus4' value='<?php echo $parred["sif_kup"];?>' disabled='disabled'/>
				<input type='hidden' name='sifra_kup' class='polje_100_92plus4' value='<?php echo $parred["sif_kup"];?>'/>
				<label>Naziv kupca:</label>
				<input type='text' name='naziv_kup' class='polje_100_92plus4' value='<?php echo $parred["naziv_kup"];?>'/>
				<label>Postanski broj:</label>
				<input type='text' name='postbr' class='polje_100_92plus4' value='<?php echo $parred["postbr"];?>'/>
				<label>Mesto:</label>
				<input type='text' name='mesto_kup' class='polje_100_92plus4' value='<?php echo $parred["mesto_kup"];?>'/>
				<label>Ulica i broj:</label>
				<input type='text' name='ulica_kup' class='polje_100_92plus4' value='<?php echo $parred["ulica_kup"];?>'/>
				<label>Rabat ugovoren:</label>
				<input type='text' name='rab_ugo' class='polje_100_92plus4' value='<?php echo $parred["rab_ugo"];?>'/>
				<label>Ziro racun:</label>
				<input type='text' name='ziro_rac' class='polje_100_92plus4' value='<?php echo $parred["ziro_rac"];?>'/>
				<label>Ziro racun 2:</label>
				<input type='text' name='ziro_rac2' class='polje_100_92plus4' value='<?php echo $parred["ziro_rac2"];?>'/>
				<label>Telefon:</label>
				<input type='text' name='tel' class='polje_100_92plus4' value='<?php echo $parred["tel"];?>'/>
				<label>PIB:</label>
				<input type='text' name='pib' class='polje_100_92plus4' value='<?php echo $parred["pib"];?>'/>
				<label>Maticni broj:</label>
				<input type='text' name='mat_br' class='polje_100_92plus4' value='<?php echo $parred["mat_br"];?>'/>
				<button type='submit' class='dugme_zeleno'>Unesi</button>
			</form>
			<form action="../index.php" method="post">
				<button type="submit" class="dugme_crveno">Otkazi</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>