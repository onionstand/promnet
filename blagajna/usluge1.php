<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
	<title>Usluge</title>
	<script src="../include/jquery/jquery-1.6.2.min.js"></script>

	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>

	<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<script>
		$(function() {
			$( "#birac_datuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
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

			jQuery("#konto").AddIncSearch({
				maxListSize: 4,
				maxMultiMatch: 50,
				selectBoxHeight: 400,
				warnMultiMatch: 'top {0} matches ...',
				warnNoMatch: 'nema poklapanja...'
			});
		});
	</script>
	<script type="text/javascript">
				$(function() { 
					$("#form_unos").validity(function() {
					
						$("#birac_datuma")
							.require("Polje je neophodno...");
							
						$("#firma")
							.require("Polje je neophodno...");
							
						$("#naziv_usluge")
							.require("Polje je neophodno...");
					
						$("#iznos")
							.require("Polje je neophodno...")
							.match("number","Mora biti broj.");
					});
				});
	</script>
</head>
<body>
<?php
require("../include/DbConnection.php");

if (isset($_POST['datum'])&&($_POST['partner_sif'])&&($_POST['nazivusluge'])&&($_POST['iznos_u']))
{
	$dan=$_POST['datum'];
	$dan2=strtotime( $dan );
	$datum=date("Y-m-d",$dan2);
	$brojracuna=$_POST['brojracuna'];
	$iznos_u=$_POST['iznos_u'];
	//niza stopa
	$result_porez_niza_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 10)
							AND tarifa_stope = 10
							AND porez_datum <= '$datum'");
	$row_porez_niza_stopa = (mysql_fetch_array($result_porez_niza_stopa));
	$procenat_nize_stope=$row_porez_niza_stopa['porez_procenat'];
	//visa stopa
	$result_porez_visa_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 20)
							AND tarifa_stope = 20
							AND porez_datum <= '$datum'");
	$row_porez_visa_stopa = (mysql_fetch_array($result_porez_visa_stopa));
	$procenat_vise_stope=$row_porez_visa_stopa['porez_procenat'];
	//
	if (isset($_POST['oduzmipdv']))
		{	
			$pdv_izn=$_POST['oduzmipdv'];
		}
	else{$oduzmipdv=0;
		$pdv_izn=0;}
		
	$nazivusluge=$_POST['nazivusluge'];
	$konto=$_POST['kont'];
	$partner_sif=$_POST['partner_sif'];
	$upit2 = mysql_query("SELECT naziv_kup FROM dob_kup WHERE sif_kup='$partner_sif'");
	while($red2 = mysql_fetch_array($upit2))
	  {$partner=$red2['naziv_kup'];}

	mysql_query("INSERT INTO usluge (sifusluge,br_dok_us,opis,datum,kontous,iznosus,pdv)
	VALUES ('$partner_sif','$brojracuna','$nazivusluge','$datum','$konto','$iznos_u','$pdv_izn')");
	$br_usluge=mysql_insert_id();
	
		
		$upit4 = mysql_query("SELECT * FROM usluge WHERE br_usluge='$br_usluge' ");
	while($niz4 = mysql_fetch_array($upit4))
		{
			$sifusluge=$niz4['sifusluge'];
			$br_dok_us=$niz4['br_dok_us'];
			$datum=$niz4['datum'];
			$br_konta2=$niz4['kontous'];
			$iznosus=$niz4['iznosus'];
			$pdv_izn2=$niz4['pdv'];
		}
		?>
	<div class="nosac_glavni_400">
		<p style="text-align:center">
			Broj usluge: <?php echo $br_usluge;?><br>
			Partner: <?php echo $sifusluge;?><br>
			Broj dokumenta: <?php echo $br_dok_us;?><br>
			Datum: <?php echo $datum;?><br>
			Konto: <?php echo $br_konta2;?><br>
			Iznos: <?php echo $iznosus;?><br>
			PDV: <?php echo $pdv_izn2;?>
		</p>
		<div class="cf"></div>
		<a href="../index.php" class="dugme_zeleno_92plus4">
			Pocetna strana
		</a>
	</div>
	<?php
}
else {
	?>
	<div class="nosac_glavni_400">
		<h2>Usluge</h2>
		<form method="post" id="form_unos">
			<label>Partner:</label>
			<select id='firma' name='partner_sif' size='1' class='polje_100'>
				<option value=''>Odaberi ... </option>
					<?php
					$upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
					while($red = mysql_fetch_array($upit))
					{
						$naziv_kup=$red['naziv_kup'];
						$sif_kup=$red['sif_kup'];
						?>
						<option value='<?php echo $sif_kup;?>'><?php echo $naziv_kup;?></option>
					<?php } ?>
			</select>
			<label>Datum:</label>
			<input id="birac_datuma" type="text" name="datum" value="" class="polje_100_92plus4" />
			<label>Broj racuna:</label>
			<input type="text" name="brojracuna" class="polje_100_92plus4"/>
			<label>Naziv usluge:</label>
			<input id="naziv_usluge" type="text" name="nazivusluge" class="polje_100_92plus4"/>
			<label>Iznos usluge:</label>
			<input id="iznos" type="text" name="iznos_u" class="polje_100_92plus4"/>
			<label>PDV za oduzimanje:</label>
			<input id="oduzmipdv" type="text" name="oduzmipdv" class="polje_100_92plus4" value="0"/>
			<label>Konto:</label>
			<select id='konto' name='kont' size='1' class='polje_100'>
				<option value=''>Odaberi ... </option>
					<?php 
					$upit = mysql_query("SELECT * FROM konto");
					while($red = mysql_fetch_array($upit))
					{
						$konto=$red['naziv_kont'];
						$broj_kont=$red['broj_kont'];
						?>
						<option value='<?php echo $broj_kont;?>'><?php echo $konto . " - " . $broj_kont;?></option>
					<?php } ?>
			</select>
			<div class="cf"></div>
			<button type="submit" class="dugme_zeleno">Unesi</button>
		</form>
		<form action="../index.php" method="post">
			<button type="submit" class="dugme_crveno">Ponisti</button>
		</form>
		<div class="cf"></div>
	</div>
<?php } ?>

</body>
</html>