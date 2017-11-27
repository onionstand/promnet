<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Promena partnera</title>

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
		require("../include/DbConnectionPDO.php");

		if (isset($_GET['brojfak'])){
			$brojfak=$_GET['brojfak'];


			$upitdosta = "SELECT profak.datum_prof, profak.sifra_fir, dob_kup.naziv_kup FROM profak
									LEFT JOIN dob_kup ON profak.sifra_fir=dob_kup.sif_kup
									WHERE broj_prof=:broj_prof";
			$stmt = $baza_pdo->prepare($upitdosta);
			$stmt->bindParam(':broj_prof', $brojfak, PDO::PARAM_INT);
			
			$stmt->bindColumn('naziv_kup', $ime_partnera);
			$stmt->bindColumn('sifra_fir', $sifra_partnera);
			$stmt->bindColumn('datum_prof', $datum_prof);
			$stmt->execute();
			$stmt->fetch();


			$datum_prometa=date("d-m-Y",(strtotime($datum_prof)));
			?>
			<form method="post" id="validity_form">
				<label>Datum</label>
				<input id="biracdatuma" type="text" name="datum" value="<?php echo $datum_prometa;?>" class="date" />
				<label>Partner:</label>
				<select id='firma' name='partnersif' size='1' class='polje_100'>
					<option value='<?php echo $sifra_partnera;?>'><?php echo $ime_partnera;?></option>
						<?php
						$upit = "SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup";
						foreach ($baza_pdo->query($upit) as $red) {
							$naziv_kup=$red['naziv_kup'];
							$sif_kup=$red['sif_kup'];
							?>
							<option value='<?php echo $sif_kup;?>'><?php echo $naziv_kup;?></option>
							<?php
						} ?>
				</select>
				<label>Broj predracuna:</label>
				<input type="hidden" name="brojfak" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_zeleno">Unesi</button>
			</form>
			<form method="post" action="profak5.php">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak;?>"/>
				<button type="submit" class="dugme_crveno">Nazad</button>
			</form>
			<div class="cf"></div>
			<?php
		}
		if (isset($_POST['brojfak'])&& ($_POST['partnersif'])&& ($_POST['datum'])){

			$datum_prometa_za_bazu=date("Y-m-d",(strtotime($_POST['datum'])));

			$upit_profak_unos = "UPDATE profak SET sifra_fir=:sifra_fir, datum_prof=:datum_prof WHERE broj_prof=:broj_prof";
			$stmt_profak_unos = $baza_pdo->prepare($upit_profak_unos);

			$stmt_profak_unos->bindParam(':sifra_fir', $_POST['partnersif'], PDO::PARAM_STR);
			$stmt_profak_unos->bindParam(':datum_prof', $datum_prometa_za_bazu, PDO::PARAM_STR);
			$stmt_profak_unos->bindParam(':broj_prof', $_POST['brojfak'], PDO::PARAM_INT);
			$stmt_profak_unos->execute() or die(print_r($stmt_profak_unos->errorInfo(), true));
				
			?>
			<h2>Ispravljeno!</h2>
			<p>Ispravljen br. predracuna : <?php echo $_POST['brojfak'];?><br>
			Ispravljen datum: <?php echo $datum_prometa_za_bazu;?><br>
			Ispravljen Partner: <?php echo $_POST['partnersif'];?>
			</p>
			<div class="cf"></div>
			<?php
		}
		?>
	</div>
</body>
</html>