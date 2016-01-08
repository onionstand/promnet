<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$(".dugme_zeleno").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_glavni_400">
			<?php
			/*1*/
			require("../include/DbConnection.php");

			/*zvanje sifre*/  
			$sifra=mysql_query("SELECT * FROM dob_kup WHERE sif_kup='$_POST[partnersif]'");
			while($row = mysql_fetch_array($sifra))
			{
				$naziv_kupca = $row['naziv_kup'];
				$sida=$row['sif_kup'];?>
				<p>Sifra kupca: <?php echo $sida; ?><br>
				Dobavljac: <?php echo $naziv_kupca;?><br>
				<?php
			}
			/*ubacivanje podataka*/  
			$sql="INSERT INTO kalk (datum,sif_firme, faktura, dostav, rok_pl)
			VALUES
			(CURDATE(),'$sida','$_POST[faktura]','$_POST[dostavnica]','$_POST[rok_placanja]')";
			if (!mysql_query($sql,$con))
			{
			die('Greska: ' . mysql_error());
			}
			$BrojKalk=mysql_insert_id();
			?>
			Broj kalkulacije: <?php echo $BrojKalk;?><br>
			<?php
						  
			//podsetnik

			$datum_za_pla=date("Y/m/d",strtotime ("+$_POST[rok_placanja] day"));

			mysql_query("INSERT INTO pods_kalk (partner, poziv_na_b, b_kalkulacije,datum_za_plac)
			VALUES
			('$naziv_kupca','$_POST[faktura]', '$BrojKalk', '$datum_za_pla')");

			/*rok placanja*/  
			$kalkbr3 = mysql_query("SELECT rok_pl FROM kalk
			WHERE broj_kalk=$BrojKalk");
			while($kalkbr4 = mysql_fetch_array($kalkbr3))
			{
				?>
				Rok placanja: <?php echo $kalkbr4['rok_pl'];?><br>
				<?php 
			}
			/*datum*/
			  
			$datkal = "SELECT date_format(datum, '%d.%m.%Y') as formatted_date FROM kalk WHERE broj_kalk=$BrojKalk ";
				$vis = mysql_query($datkal) or die(mysql_error());
				$row_vis = mysql_fetch_assoc($vis);
			?> 
			Datum: <?php echo $row_vis['formatted_date'];?></p>
			<div class="cf"></div>
			<form action="kalk_nov3.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $BrojKalk; ?>" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form action="kalk_brisi.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $BrojKalk; ?>" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>