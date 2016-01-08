<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo</title>
	</head> 
	<body>
		<div class="nosac_glavni_400">
			<?php require("../include/DbConnection.php");
			$br_k_pis=$_POST['broj_k_pis_tr'];
			$id_k_pis=$_POST['id_k_pis_tr'];
			$broj_kalk=$_POST['broj_kalk'];

			$upit=mysql_query("SELECT * FROM k_pism_tr WHERE id_k='$id_k_pis'");
			$red=mysql_fetch_array($upit);
			$kol_k_p=$red['kolic_p'];
			$sifrob=$red['sif_rob_p'];
			$robsta=mysql_query("SELECT stanje FROM roba WHERE sifra='$sifrob'");
			$robsta2=mysql_fetch_array($robsta);
			$robsta3=$robsta2['stanje'];

			mysql_query("DELETE FROM k_pism_tr WHERE id_k='$id_k_pis' AND broj_p='$br_k_pis'");

			$izmenastanja=$robsta3+$kol_k_p;
			mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$sifrob'");
			echo "<h2>Izbrisano!</h2>";
			echo "<p>Stanje robe: " . $izmenastanja."</p>";
			?>
			<form action="k_pis_r_k4.php" method="post">
				<input type="hidden" name="br_k_pis" value="<?php echo $br_k_pis; ?>"/>
				<input type="hidden" name="broj_kalk" value="<?php echo $broj_kalk; ?>"/>
				<button type="submit" class="dugme_zeleno">Dalje</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>