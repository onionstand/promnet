<?php
require("../include/DbConnectionPDO.php");

function staraRoba(){
	global $baza_pdo;
	$upit_staro_pis = "SELECT k_pism_fin.id, k_pism_fin.id_firme, k_pism_fin.opis, k_pism_fin.osnovica,
			k_pism_fin.pdv, k_pism_fin.zbir, k_pism_fin.duguje_potr, k_pism_fin.propratni_dok, k_pism_fin.datum, dob_kup.sif_kup, dob_kup.naziv_kup  FROM k_pism_fin 
			LEFT JOIN dob_kup ON k_pism_fin.id_firme=dob_kup.sif_kup
			ORDER BY k_pism_fin.id";
	foreach ($baza_pdo->query($upit_staro_pis) as $red_staro_pis) {
		$id_pisma=$red_staro_pis['id'];
		$partner=$red_staro_pis['naziv_kup'];
		$opis=$red_staro_pis['opis'];
		$osnovica=$red_staro_pis['osnovica'];
		$pdv=$red_staro_pis['pdv'];
		$zbir=$red_staro_pis['zbir'];
		$duguje_potr=$red_staro_pis['duguje_potr'];
		$propratni_dok=$red_staro_pis['propratni_dok'];
		$datum=date("d-m-Y",(strtotime($red_staro_pis['datum'])));
		?>
		<tr>
			<td><?php echo $id_pisma;?></td>
			<td><?php echo $partner;?></td>
			<td><?php echo $opis;?></td>
			<td><?php echo $osnovica;?></td>
			<td><?php echo $pdv;?></td>
			<td><?php echo $zbir;?></td>
			<td><?php if(isset($duguje_potr) && $duguje_potr == 1) {echo "duguje"; } if(isset($duguje_potr) && $duguje_potr == 2) {echo "potrazuje"; }?></td>
			<td><?php echo $propratni_dok;?></td>
			<td><?php echo $datum;?></td>
			<td>
				<form action="k_pis_fin1.php" method="get">
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
		<title>Stara knjizna pisma Finansijska</title>
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
					<th>Duguje / potrazuje</th>
					<th>Broj dokumenta</th>
					<th>Datum</th>
					<th></th>
				</tr>
				
				<?php staraRoba();?>
			</table>
			<a href="k_pis_fin1.php?novo_pismo=1" class="dugme_plavo_92plus4">Novo Pismo</a>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>