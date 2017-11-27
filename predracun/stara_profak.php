<?php
require("../include/DbConnectionPDO.php");
function PretragaPoTerminuFak($ime_polja, $termin_pretrage,$query_tip){
	global $baza_pdo;
	if ($query_tip==1){
		$upit = "SELECT profak.broj_prof, profak.sifra_fir,
			date_format(profak.datum_prof, '%d. %m. %Y.') AS datumf, profak.izzad, dob_kup.sif_kup, dob_kup.naziv_kup 
			FROM profak
			LEFT JOIN dob_kup ON profak.sifra_fir=dob_kup.sif_kup
			WHERE ".$ime_polja." LIKE '%".$termin_pretrage."%'
			ORDER BY profak.broj_prof";
		if (!$upit) {
    		echo "<h1>Nema rezultata...</h1>";
		}
		//if (!$upit) {die(mysql_error());}
	}

	if ($query_tip==2){
		$upit = "SELECT profak.broj_prof, profak.sifra_fir,
			date_format(profak.datum_prof, '%d. %m. %Y.') AS datumf, profak.izzad, dob_kup.sif_kup, dob_kup.naziv_kup 
			FROM profak
			LEFT JOIN dob_kup ON profak.sifra_fir=dob_kup.sif_kup
			WHERE ".$ime_polja."=".$termin_pretrage."
			ORDER BY profak.broj_prof";
		if (!$upit) {
    		echo "<h1>Nema rezultata...</h1>";
		}
	}

	if ($query_tip==3){
		$upit = "SELECT profak.broj_prof, profak.sifra_fir,
			date_format(profak.datum_prof, '%d. %m. %Y.') AS datumf, profak.izzad, dob_kup.sif_kup, dob_kup.naziv_kup 
			FROM profak
			LEFT JOIN dob_kup ON profak.sifra_fir=dob_kup.sif_kup
			ORDER BY profak.broj_prof";
	}

	?>
	<div class="nosac_sa_tabelom">
	<table>
		<tr>
			<th>ID fak.</th>
			<th>Kupac</th>
			<th>Datum</th>
			<th>Iznos</th>
			<th></th>
		</tr>
	<?php
	foreach ($baza_pdo->query($upit) as $niz)
	{
	?>
		<tr>
			<td><?php echo $niz['broj_prof'];?></td>
			<td><?php echo $niz['naziv_kup'];?></td>
			<td><?php echo $niz['datumf'];?></td>
			<td><?php echo $niz['izzad'];?></td>
			<td>
				<form action="profak5.php" method="post">
					<input type="hidden" name="broj_profak" value="<?php echo $niz['broj_prof'];?>"/>
					<input type="image" src="../include/images/olovka.png" title="Ispravi" />
				</form>
			</td>
			<td>
				<form method="post" action="kloniraj.php">
					<input type="hidden" name="broj_profak" value="<?php echo $niz['broj_prof'];?>"/>
					<input type="image" src="../include/images/kloniraj.png" title="Kloniraj" />
				</form>
			</td>
		</tr>
	<?php
	}
	?>
	</table>
	</div>
	<?php
}
?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
	<script type="text/javascript">
	jQuery(document).ready(function() {
		$("form").validity(function() {
			$("#broj_fakture")
				.match("number","Mora biti broj.");
		});
		$(".polje_100_92plus4").first().focus();
		
	});
	</script>
	<title>Stare fakture</title>
</head>
<body>
<?php 
//brisanje 	
if (isset($_POST['broj_fak'])){
	$brojfak=$_POST['broj_fak'];
	echo "<h2>Izbrisano.</h2>";

	mysql_query("DELETE FROM profakrob WHERE br_profak='$brojfak'");
	mysql_query("DELETE FROM profak WHERE broj_prof='$brojfak'");
}
//brisanje kraj
	if (isset($_POST['naziv_partnera'])) {
		PretragaPoTerminuFak("dob_kup.naziv_kup", $_POST['naziv_partnera'],1);
	}
	if (isset($_POST['broj_fakture'])) {
		PretragaPoTerminuFak("profak.broj_prof", $_POST['broj_fakture'],2);
	}
	if (isset($_POST['sve_fakture'])) {
		PretragaPoTerminuFak("profak.broj_prof", $_POST['sve_fakture'],3);
	}
	?>
	<div class="nosac_glavni_400">
		<div class="cf"></div>
		<form method="post">
			<label>Ime partnera:</label>
			<input type="text" name="naziv_partnera" class="polje_100_92plus4"/>
			<button type="submit" class="dugme_zeleno">Trazi  po partneru</button>
		</form>
		<form method="post">
			<label>Broj fakture:</label>
			<input type="text" name="broj_fakture" class="polje_100_92plus4" id="broj_fakture"/>
			<button type="submit" class="dugme_zeleno">Trazi po broju fakture</button>
		</form>
		<form method="post">
			<label>Sve fakture:</label>
			<input type="hidden" type="text" name="sve_fakture" class="polje_100_92plus4" value="1"/>
			<button type="submit" class="dugme_zeleno">Pregledaj sve fakture</button>
		</form>
		<div class="cf"></div>
		<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
		<div class="cf"></div>
	</div>
</body>