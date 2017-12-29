<?php
require("../include/DbConnection.php");
function PretragaPoTerminuFak($ime_polja, $termin_pretrage,$query_tip){
	if ($query_tip==1){
		$upit = mysql_query("SELECT dosta.broj_dost, dosta.sifra_fir,
			date_format(dosta.datum_d, '%d. %m. %Y.') AS datumf, dosta.izzad, dosta.ispor,
			date_format(dosta.racun_poslat, '%d. %m. %Y.') AS datum_rac_poslat,
			dob_kup.sif_kup, dob_kup.naziv_kup 
			FROM dosta
			LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
			WHERE ".$ime_polja." LIKE '%".$termin_pretrage."%'
			ORDER BY dosta.broj_dost");
		if (!$upit) {
    		echo "<h1>Nema rezultata...</h1>";
		}
		//if (!$upit) {die(mysql_error());}
	}

	if ($query_tip==2){
		$upit = mysql_query("SELECT dosta.broj_dost, dosta.sifra_fir,
			date_format(dosta.datum_d, '%d. %m. %Y.') AS datumf,
			dosta.izzad,
			dosta.ispor,
			date_format(dosta.racun_poslat, '%d. %m. %Y.') AS datum_rac_poslat,
			dob_kup.sif_kup, dob_kup.naziv_kup 
			FROM dosta
			LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
			WHERE ".$ime_polja."=".$termin_pretrage."
			ORDER BY dosta.broj_dost");
		if (!$upit) {
    		echo "<h1>Nema rezultata...</h1>";
		}
	}

	if ($query_tip==3){
		$upit = mysql_query("SELECT dosta.broj_dost, dosta.sifra_fir,
			date_format(dosta.datum_d, '%d. %m. %Y.') AS datumf, 
			dosta.izzad, 
			dosta.ispor,
			date_format(dosta.racun_poslat, '%d. %m. %Y.') AS datum_rac_poslat, 
			dob_kup.sif_kup, 
			dob_kup.naziv_kup 
			FROM dosta
			LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
			ORDER BY dosta.broj_dost");
	}

	?>
	<div class="nosac_sa_tabelom">
	<table>
		<tr>
			<th>ID fak.</th>
			<th>Br. fak.</th>
			<th>Kupac</th>
			<th>Datum</th>
			<th>Iznos</th>
			<th>Porez</th>
			<th>Racun<br>poslat</th>
			<th></th>
			<th></th>
		</tr>
	<?php
	$zbir_izzad=0;
	$zbir_ispor=0;
	while($niz = mysql_fetch_array($upit))
	{
		$zbir_izzad=$zbir_izzad + $niz['izzad'];
		$zbir_ispor=$zbir_ispor + $niz['ispor'];
	?>
		<tr>
			<td><?php echo $niz['broj_dost'];?></td>
			<td><?php echo $niz['racun_rucni'];?></td>
			<td><?php echo $niz['naziv_kup'];?></td>
			<td><?php echo $niz['datumf'];?></td>
			<td><?php echo $niz['izzad'];?></td>
			<td><?php echo $niz['ispor'];?></td>
			<td><?php if ($niz['datum_rac_poslat']) {
				echo $niz['datum_rac_poslat'];
			}
			else{ echo "<span class='crveno'>Nije!</span>";}
			?>	
			</td>
			<td>
				<form action="faktura.php" method="post">
					<input type="hidden" name="broj_fak_stampa" value="<?php echo $niz['broj_dost'];?>"/>
					<input type="image" src="../include/images/olovka.png" title="Ispravi" />
				</form>
			</td>
			<td>
				<form method="post" action="poslat_racun.php">
					<input type="hidden" name="broj_fak" value="<?php echo $niz['broj_dost'];?>"/>
					<input type="image" src="../include/images/ikona_kalendar.png" title="Unesi datum slanja racuna" />
				</form>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>Zbir:</td>
		<td><b><?php echo $zbir_izzad;?></b></td>
		<td><b><?php echo $zbir_ispor;?></b></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
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
//brisanje fak
/*
if (isset($_POST['broj_fak'])){
	$brojfak=$_POST['broj_fak'];
	echo "<h2>Izbrisano.</h2>";

	$result = mysql_query("SELECT izlaz.koli_dos, izlaz.srob_dos, roba.stanje, (roba.stanje+izlaz.koli_dos) AS izmenastanja, roba.sifra 
	FROM izlaz RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra WHERE br_dos='$brojfak'");
	while($row = mysql_fetch_array($result))
	  {
		$prestanje3=$row['koli_dos'];
		$sifrob=$row['srob_dos'];
		$robsta3=$row['stanje'];
		$izmenastanja=$row['izmenastanja'];
		mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$sifrob'");
		echo "Stanje robe " . $sifrob . ": " ;
		echo $izmenastanja;
		echo "<br>";
	  }
	mysql_query("DELETE FROM izlaz WHERE br_dos='$brojfak'");
	mysql_query("DELETE FROM dosta WHERE broj_dost='$brojfak'");
	}
*/
//brisanje fak kraj
	if (isset($_POST['naziv_partnera'])) {
		PretragaPoTerminuFak("dob_kup.naziv_kup", $_POST['naziv_partnera'],1);
	}
	if (isset($_POST['broj_fakture'])) {
		PretragaPoTerminuFak("dosta.broj_dost", $_POST['broj_fakture'],2);
	}
	if (isset($_POST['sve_fakture'])) {
		PretragaPoTerminuFak("dosta.broj_dost", $_POST['sve_fakture'],3);
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
