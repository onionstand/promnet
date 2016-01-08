<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Predracun</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("button:first").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			require("../include/DbConnection.php");

			/*zvanje sifre*/  
			$upit = "SELECT * FROM dob_kup WHERE sif_kup='$_POST[partnersif]'"; 
			$result = mysql_query($upit) or die(mysql_error());
			$red = mysql_fetch_array($result) or die(mysql_error());
			$part=$red['sif_kup'];
			$rokpl=$_POST['rok_placanja'];
			echo "<p>Sifra kupca: " . $part . "<br>Partner: " . $red['naziv_kup'] . "<br>Rok placanja: " . $rokpl . "<br>";

			mysql_query("INSERT INTO profak (datum_prof, sifra_fir, rok)
						VALUES
						(CURDATE(),'$part','$rokpl')");
			$profbr = mysql_insert_id();
			/*prikaz broja fak*/
			echo "Broj profakture: " . $profbr . "<br>";

			$datfak = "SELECT date_format(datum_prof , '%d.%m.%Y') as formatted_date FROM profak WHERE broj_prof=$profbr ";
			$vis = mysql_query($datfak) or die(mysql_error());
			$row_vis = mysql_fetch_assoc($vis);
			echo "Datum: " . $row_vis['formatted_date'] . "</p>";
			?> 
			<div class="cf"></div>
			<form action="profak3a.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $profbr; ?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="fak_brisi1.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $profbr; ?>"/>
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>
