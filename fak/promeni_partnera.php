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
			
			$("#validity_form").validity(function() {
	                    $("#biracdatuma","#firma")
	                        .require()
	                    });
		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery("#firma").AddIncSearch({
		        maxListSize: 4,
		        maxMultiMatch: 50,
		        warnMultiMatch: 'top {0} matches ...',
		        warnNoMatch: 'nema poklapanja...'
		    });
		});
	</script>

</head>
<body>
	<div class="nosac_glavni_400">
		<?php
		require("../include/DbConnection.php");

		if (isset($_GET['brojfak'])){
			$brojfak=$_GET['brojfak'];
			$upitdosta = mysql_query("SELECT dosta.datum_d, dosta.sifra_fir, dob_kup.naziv_kup FROM dosta
									LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
									WHERE broj_dost=".$brojfak);
			$nizdosta= mysql_fetch_array($upitdosta);
			$ime_partnera=$nizdosta['naziv_kup'];
			$sifra_partnera=$nizdosta['sifra_fir'];
			$datum_prometa=date("d-m-Y",(strtotime($nizdosta['datum_d'])));
			?>
			<form method="post" id="validity_form">
				<label>Datum</label>
				<input id="biracdatuma" type="text" name="datum" value="<?php echo $datum_prometa;?>" class="date" />
				<label>Partner:</label>
				<select id='firma' name='partnersif' size='1' class='polje_100'>
					<option value='<?php echo $sifra_partnera;?>'><?php echo $ime_partnera;?></option>
						<?php
						$upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
						while($red = mysql_fetch_array($upit))
							{
								$naziv_kup=$red['naziv_kup'];
								$sif_kup=$red['sif_kup'];
								?>
								<option value='<?php echo $sif_kup;?>'><?php echo $naziv_kup;?></option>
								<?php 
							} ?>
				</select>
				<input type="hidden" name="brojfak" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="faktura.php">
				<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		if (isset($_POST['brojfak'])&& ($_POST['partnersif'])&& ($_POST['datum'])){
			$datum_prometa_za_bazu=date("Y-m-d",(strtotime($_POST['datum'])));
			mysql_query("UPDATE dosta SET sifra_fir=".$_POST['partnersif'].", datum_d='".$datum_prometa_za_bazu."' WHERE broj_dost=".$_POST['brojfak']) or die(mysql_error());
			
			?>
			<h2>Ispravljeno...</h2>
			<div class="cf"></div>
			<?php
		}
		?>
	</div>
</body>
</html>