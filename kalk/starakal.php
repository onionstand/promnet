<?php require("../include/DbConnection.php"); 
function PretragaPoTerminuFak($ime_polja, $termin_pretrage,$query_tip){

	if ($query_tip==1){
		$upit = mysql_query("SELECT kalk.broj_kalk, kalk.rok_pl, kalk.sif_firme, kalk.faktura, kalk.nabav_vre, kalk.pro_vre, kalk.ukal_porez, date_format(kalk.datum, '%d. %m. %Y.') AS datumf, dob_kup.sif_kup, dob_kup.naziv_kup
			FROM kalk 
			LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
			WHERE ".$ime_polja." LIKE '%".$termin_pretrage."%'
			ORDER BY kalk.broj_kalk");
		if (!$upit) {
    		echo "<h2>Nema rezultata...</h2>";
		}
		//if (!$upit) {die(mysql_error());} nabav_vre
	}

	if ($query_tip==2){
		$upit = mysql_query("SELECT kalk.broj_kalk, kalk.rok_pl, kalk.sif_firme, kalk.faktura, kalk.nabav_vre, kalk.pro_vre, kalk.ukal_porez, date_format(kalk.datum, '%d. %m. %Y.') AS datumf, dob_kup.sif_kup, dob_kup.naziv_kup
			FROM kalk 
			LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
			WHERE ".$ime_polja."=".$termin_pretrage." ORDER BY kalk.broj_kalk");
		if (!$upit) {
    		echo "<h2>Nema rezultata...</h2>";
		}
	}

	if ($query_tip==3){
		$upit = mysql_query("SELECT kalk.broj_kalk, kalk.rok_pl, kalk.sif_firme, kalk.faktura, kalk.nabav_vre, kalk.pro_vre, kalk.ukal_porez, date_format(kalk.datum, '%d. %m. %Y.') AS datumf, dob_kup.sif_kup, dob_kup.naziv_kup
			FROM kalk 
			LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup
			ORDER BY kalk.broj_kalk");
	}

	?>
	<table>
		<tr>
			<th>Broj kalk.</th>
			<th>Broj rac.</th>
			<th>Dobavljac</th>
			<th>Datum</th>
			<th>Placanje</th>
			<th>Prodajna vrednost bez PDV</th>
			<th>Osnovica</th>
			<th>Porez</th>
			<th>Iznos</th>
			<th></th>
		</tr>
	<?php
	$zbir_osnovica=0;
	$zbir_ukal_porez=0;
	$zbir_nabav_vre=0;
	while($niz = mysql_fetch_array($upit))
	{
		$zbir_osnovica = $zbir_osnovica + ($niz['nabav_vre'] - $niz['ukal_porez']);
		$zbir_ukal_porez = $zbir_ukal_porez + $niz['ukal_porez'];
		$zbir_nabav_vre = $zbir_nabav_vre + $niz['nabav_vre'];
		?>
		<tr>
			<td><?php echo $niz['broj_kalk'];?></td>
			<td><?php echo $niz['faktura'];?></td>
			<td><?php echo $niz['naziv_kup'];?></td>
			<td><?php echo $niz['datumf'];?></td>
			<?php $datum_za_pla=date("d.m.Y",strtotime ("+$niz[rok_pl] day"));?>
			<td><?php echo $datum_za_pla;?></td>
			<td><?php echo $niz['pro_vre'];?></td>
			<td><?php echo $niz['nabav_vre'] - $niz['ukal_porez'];?></td>
			<td><?php echo $niz['ukal_porez'];?></td>
			<td><?php echo $niz['nabav_vre'];?></td>
			<td>
				<form action="kalk_nov6.php" method="post">
					<input type="hidden" name="broj_kalkulaci" value="<?php echo $niz['broj_kalk'];?>"/>
					<input type="image" src="../include/images/olovka.png" title="Ispravi" />
				</form>
			</td>
			<!--<td>
				<form action="kalk_brisi2.php" method="post">
					<input type="hidden" name="broj_kalkulaci" value="<?php //echo $niz['broj_kalk'];?>"/>
					<input type="image" src="../include/images/iks.png" title="Brisi" />
				</form>
			</td>
			-->
		</tr>
	<?php
	}
	?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td>Zbir:</td>
			<td><?php echo $zbir_osnovica;?></td>
			<td><?php echo $zbir_ukal_porez;?></td>
			<td><?php echo $zbir_nabav_vre;?></td>

			<td></td>
		</tr>
	</table>
	<?php
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<script type="text/javascript">
	jQuery(document).ready(function() {
		$("#broj_kalk_form").validity(function() {
			$("#broj_kalk")
				.require("Polje nije popunjeno.")
				.match("number","Mora biti broj.");
		});
		$("#ime_part_kalk_form").validity(function() {
			$("#naziv_partnera")
				.require("Polje nije popunjeno.");
		});
		$(".polje_100_92plus4").first().focus();
		
	});
	</script>
	<title>Stara Kalkulacija</title>
</head>
<body>
<div class="nosac_sa_tabelom">
	<?php
	if (isset($_POST['naziv_partnera'])) {
		PretragaPoTerminuFak("dob_kup.naziv_kup", $_POST['naziv_partnera'],1);
	}
	if (isset($_POST['broj_kalk'])) {
		PretragaPoTerminuFak("kalk.broj_kalk", $_POST['broj_kalk'],2);
	}
	if (isset($_POST['sve_kalk'])) {
		PretragaPoTerminuFak("kalk.broj_kalk", $_POST['sve_kalk'],3);
	}
	?>
	<div class="cf"></div>
	<form method="post" id="ime_part_kalk_form">
		<label>Ime partnera:</label>
		<input type="text" name="naziv_partnera" class="polje_100_92plus4" id="naziv_partnera"/>
		<button type="submit" class="dugme_zeleno">Trazi</button>
	</form>
	
	<form method="post" id="broj_kalk_form">
		<label>Broj kalkulacije:</label>
		<input type="text" name="broj_kalk" class="polje_100_92plus4" id="broj_kalk"/>
		<button type="submit" class="dugme_zeleno">Trazi</button>
	</form>
	
	<form method="post">
		<label>Sve kalkulacije:</label>
		<input type="hidden" type="text" name="sve_kalk" class="polje_100_92plus4" value="1"/>
		<button type="submit" class="dugme_zeleno">Pregledaj</button>
	</form>

	<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
	<div class="cf"></div>
</div>
</body>
</html>
