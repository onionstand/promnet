<?php
require("../include/DbConnectionPDO.php");

//ponuda
//id_ponude 	datum 	sifra_fir 	rok 	izzad 	ispor 	odo_rab 	napomena 	ponuda_br_rucni 

function staraPonuda(){
	global $baza_pdo;
	$upit_staro_pis = "SELECT ponuda.id_ponude, ponuda.datum, ponuda.sifra_fir, ponuda.rok,
			ponuda.izzad, ponuda.ispor, ponuda.odo_rab, ponuda.napomena, ponuda.ponuda_br_rucni, ponuda.partner_tekst, dob_kup.sif_kup, dob_kup.naziv_kup  FROM ponuda 
			LEFT JOIN dob_kup ON ponuda.sifra_fir=dob_kup.sif_kup
			ORDER BY ponuda.id_ponude";
	foreach ($baza_pdo->query($upit_staro_pis) as $red_pon) {
		$id_ponude=$red_pon['id_ponude'];
		//$datum=$red_pon['datum'];
		$sifra_fir=$red_pon['sifra_fir'];
		$rok=$red_pon['rok'];
		$izzad=$red_pon['izzad'];
		$ispor=$red_pon['ispor'];
		$odo_rab=$red_pon['odo_rab'];
		$napomena=$red_pon['napomena'];
		$ponuda_br_rucni=$red_pon['ponuda_br_rucni'];
		$naziv_kup=$red_pon['naziv_kup'];
		$partner_tekst=$red_pon['partner_tekst'];
		$datum=date("d-m-Y",(strtotime($red_pon['datum'])));
		?>
		<tr>
			<td><?php echo $id_ponude;?></td>
			<td><?php echo $ponuda_br_rucni;?></td>
			<td><?php echo $naziv_kup;?></td>
			<td><?php echo $partner_tekst;?></td>
			<td><?php echo $odo_rab;?></td>
			<td><?php echo $izzad;?></td>
			<td><?php echo $ispor;?></td>
			<td><?php echo $datum;?></td>
			<td>
				<form action="ponuda1.php" method="get">
					<input type="hidden" name="ponuda_stara" value="<?php echo $id_ponude;?>"/>
					<input type="image" src="../include/images/olovka.png" title="Ispravi" />
				</form>
			</td>
		</tr>
		<?php

	}
}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Stara knjizna pisma Finansijska</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<h2>Knjizna pisma Finansijska</h2>
			
			<table id='tabele'>
				<tr>
					<th>Broj</th>
					<th>Broj rucni</th>
					<th>Partner</th>
					<th>Partner tekst</th>
					<th>Rabat</th>
					<th>Iznos</th>
					<th>PDV</th>
					
					
					<th>Datum</th>
					<th></th>
				</tr>
				
				<?php staraPonuda();?>
			</table>
			<a href="ponuda1.php?ponuda_nova=1" class="dugme_plavo_92plus4">Nova ponuda</a>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>