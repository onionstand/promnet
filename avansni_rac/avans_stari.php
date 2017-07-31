<?php
require("../include/DbConnectionPDO.php");

//id 	id_firme 	opis   osnovica 	porez 	zbir 	datum 

function staraRoba(){
	global $baza_pdo;
	$upit_staro_pis = "SELECT avans_rac.id, avans_rac.id_firme, avans_rac.opis, avans_rac.osnovica,
			avans_rac.porez, avans_rac.zbir, avans_rac.datum, dob_kup.sif_kup, dob_kup.naziv_kup  FROM avans_rac 
			LEFT JOIN dob_kup ON avans_rac.id_firme=dob_kup.sif_kup
			ORDER BY avans_rac.id";
	foreach ($baza_pdo->query($upit_staro_pis) as $red_staro_pis) {
		$id_pisma=$red_staro_pis['id'];
		$partner=$red_staro_pis['naziv_kup'];
		$opis=$red_staro_pis['opis'];
		$osnovica=$red_staro_pis['osnovica'];
		$pdv=$red_staro_pis['porez'];
		$zbir=$red_staro_pis['zbir'];
		$datum=date("d-m-Y",(strtotime($red_staro_pis['datum'])));
		?>
		<tr>
			<td><?php echo $id_pisma;?></td>
			<td><?php echo $partner;?></td>
			<td><?php echo $opis;?></td>
			<td><?php echo $osnovica;?></td>
			<td><?php echo $pdv;?></td>
			<td><?php echo $zbir;?></td>
			<td><?php echo $datum;?></td>
			<td>
				<form action="avans1.php" method="get">
					<input type="hidden" name="satro_pismo" value="<?php echo $id_pisma;?>"/>
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
		<title>Avansni računi</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<h2>Knjizna pisma Finansijska</h2>
			
			<table id='tabele'>
				<tr>
					<th>Broj k.pisma</th>
					<th>Partner</th>
					<th>Opis</th>
					<th>Osnovica</th>
					<th>PDV</th>
					<th>Zbir</th>
					<th>Datum</th>
					<th></th>
				</tr>
				
				<?php staraRoba();?>
			</table>
			<a href="avans1.php?novo_pismo=1" class="dugme_plavo_92plus4">Novi avansni racun</a>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>