<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Izvod</title>

	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
	<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			$( "#biracdatuma_prom" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			
			$("#validity_form").validity(function() {
	                    $("#biracdatuma")
	                        .require()
	                    });
		});
	</script>

</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnection.php");

		if (isset($_POST['broj_fak'])){
			$brojfak=$_POST['broj_fak'];
			$upitdosta = mysql_query("SELECT dosta.datum_d, dosta.sifra_fir, dosta.datum_prom, dosta.racun_poslat, dob_kup.naziv_kup FROM dosta
									LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
									WHERE broj_dost=".$brojfak);
			$nizdosta= mysql_fetch_array($upitdosta);
			$ime_partnera=$nizdosta['naziv_kup'];
			$sifra_partnera=$nizdosta['sifra_fir'];
			if ($nizdosta['racun_poslat']) {
				$racun_poslat=date("d-m-Y",(strtotime($nizdosta['racun_poslat'])));
			}
			
			?>
			<form method="post" id="validity_form">
				<label>Datum slanja racuna: </label>
				<input id="biracdatuma" type="text" name="racun_poslat_dat" value="<?php if ($racun_poslat) {echo $racun_poslat;} ?>" class="date" />
				<input type="hidden" name="brojfak" value="<?php echo $brojfak;?>"/>
				<input type="hidden" name="ime_part" value="<?php echo $ime_partnera;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<div class="cf"></div>
			<a href="faktura_stare.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>
			<?php
		}
		if (isset($_POST['brojfak'])){	
			$racun_poslat_dat=date("Y-m-d",(strtotime($_POST['racun_poslat_dat'])));
			mysql_query("UPDATE dosta SET racun_poslat='".$racun_poslat_dat."'WHERE broj_dost=".$_POST['brojfak']) or die(mysql_error());
			
			?>
			<h2>Uneseno!</h2>
			<p>Ispravljen br. racuna za : <?php echo $_POST['ime_part']; ?><br>
			ID dostavnice <?php echo $_POST['brojfak']; ?>
			</p>
			<a href="faktura_stare.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>

			<?php
		}
		?>
	</div>
</body>
</html>