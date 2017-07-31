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
	                    $("#biracdatuma","#firma","#broj_racuna_part")
	                        .require()
	                    });
		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery("#firma").AddIncSearch({
		        maxListSize: 4,
		        maxMultiMatch: 50,
		        selectBoxHeight: 400,
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

		if (isset($_GET['brojkalku'])){
			$brojkalku=$_GET['brojkalku'];
			$upitkalk = mysql_query("SELECT kalk.datum, kalk.dostav, kalk.sif_firme, kalk.rok_pl, dob_kup.naziv_kup FROM kalk
									LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
									WHERE broj_kalk=".$brojkalku);
			$nizkalk= mysql_fetch_array($upitkalk);
			$ime_partnera=$nizkalk['naziv_kup'];
			$sifra_partnera=$nizkalk['sif_firme'];
			$broj_racuna_part=$nizkalk['dostav'];
			$rok_placanja=$nizkalk['rok_pl'];
			$datum_prometa=date("d-m-Y",(strtotime($nizkalk['datum'])));
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
				<label>Rok placanja:</label>
				<input type="text" name="rok_placanja" class="polje_100_92plus4" id="rok_placanja" value="<?php echo $rok_placanja;?>"/>
				<label>Broj racuna:</label>
				<input type="text" name="broj_racuna_part" class="polje_100_92plus4" id="broj_racuna_part" value="<?php echo $broj_racuna_part;?>"/>

				<input type="hidden" name="brojkalku" value="<?php echo $brojkalku;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="kalk_nov6.php">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $brojkalku;?>"/>
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		if (isset($_POST['brojkalku'])&& ($_POST['partnersif'])&& ($_POST['datum'])){
			
			$datum_prometa_za_bazu=date("Y-m-d",(strtotime($_POST['datum'])));
			mysql_query("UPDATE kalk SET sif_firme=".$_POST['partnersif'].", datum='".$datum_prometa_za_bazu."', rok_pl=".$_POST['rok_placanja'].", dostav='".$_POST['broj_racuna_part']."' ,
				faktura='".$_POST['broj_racuna_part']."'
				WHERE broj_kalk=".$_POST['brojkalku']) or die(mysql_error());
			

			$datum_za_pla=date("Y-m-d",(strtotime ("$_POST[datum]+$_POST[rok_placanja] day")));
			mysql_query("UPDATE pods_kalk SET poziv_na_b='".$_POST['broj_racuna_part']."', datum_za_plac='".$datum_za_pla."' WHERE b_kalkulacije=".$_POST['brojkalku']) or die(mysql_error());
			

			?>
			<h2>Ispravljeno!</h2>
			<p>
			Ispravljen datum: <?php echo $datum_prometa_za_bazu;?><br>
			Ispravljen Partner: <?php echo $_POST['partnersif'];?><br>
			Ispravljen broj racuna: <?php echo $_POST['broj_racuna_part'];?><br>
			</p>
			<div class="cf"></div>
			<?php
			
		
		}
		?>
	</div>
</body>
</html>