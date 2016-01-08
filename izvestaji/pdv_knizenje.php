<?php
require("../include/DbConnectionPDO.php");
function UbacivanjeDoprinosaUUsluge($sifusluge, $br_dok_us, $opis_usluge, $konto_usluge, $iznosusluge){
	global $baza_pdo;
	global $datum_za_bazu;
	$upit_za_usluge="INSERT INTO usluge (sifusluge, br_dok_us, opis, datum, kontous, iznosus)
		VALUES (:sifusluge, :br_dok_us, :opis, :datum, :kontous, :iznosus)";
	$stmt_za_usluge = $baza_pdo->prepare($upit_za_usluge);
	$stmt_za_usluge->bindParam(':sifusluge', $sifusluge, PDO::PARAM_STR);
	$stmt_za_usluge->bindParam(':br_dok_us', $br_dok_us, PDO::PARAM_STR);
	$stmt_za_usluge->bindParam(':opis', $opis_usluge, PDO::PARAM_STR);
	$stmt_za_usluge->bindParam(':datum', $datum_za_bazu, PDO::PARAM_STR);
	$stmt_za_usluge->bindParam(':kontous', $konto_usluge, PDO::PARAM_STR);
	$stmt_za_usluge->bindParam(':iznosus', $iznosusluge, PDO::PARAM_STR);
	//$stmt_za_usluge->bindParam(':pdv', '0', PDO::PARAM_STR);
	$stmt_za_usluge->execute();
	$usluge_id = $baza_pdo->lastInsertId();
	$OK_usluga = $stmt_za_usluge->rowCount();
	if (!$OK_usluga) {
		$error = $stmt_za_usluge->errorInfo();
		if (isset($error[2])) {
	  		$error = $error[2];
		}
	}
	return $usluge_id;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html" />
		<meta charset="utf-8">
		<title>Pregled Plata</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	</head>
	<body>
		<div class="nosac_sa_tabelom">
<?php
if (isset($_POST['knjizenje'])) {
	$datum_za_bazu = date("Y-m-d"); 
	$id_usl_k470potr=UbacivanjeDoprinosaUUsluge("14", $_POST['datum_od']." - ".$_POST['datum_do'], "OBRACUN PDV", "470", $_POST['promet_dob_pdv_zbir']);
	$id_usl_k470dug=UbacivanjeDoprinosaUUsluge("14", $_POST['datum_od']." - ".$_POST['datum_do'], "OBRACUN PDV", "470", ($_POST['promet_dob_pdv_zbir']*-1));
	$id_usl_k270potr=UbacivanjeDoprinosaUUsluge("14", $_POST['datum_od']." - ".$_POST['datum_do'], "OBRACUN PDV", "270", $_POST['prethodni_porez_pdv']);
	$id_usl_k270dug=UbacivanjeDoprinosaUUsluge("14", $_POST['datum_od']." - ".$_POST['datum_do'], "OBRACUN PDV", "270", ($_POST['prethodni_porez_pdv']*-1));

	$upit_pdv_unos="INSERT INTO pdv_obracun (
					prom_osn_opst_stop,
					prom_osn_pos_stop,
					prom_pdv_opst_stop,
					prom_pdv_pos_stop,
					prom_osn_zbir,
					prom_pdv_zbir,
					preth_porez_osn,
					preth_porez_pdv,
					poreska_obaveza,
					datum_od,
					datum_do,
					id_usl_k470potr,
					id_usl_k470dug,
					id_usl_k270potr,
					id_usl_k270dug
					)
					VALUES (
					:prom_osn_opst_stop,
					:prom_osn_pos_stop,
					:prom_pdv_opst_stop,
					:prom_pdv_pos_stop,
					:prom_osn_zbir,
					:prom_pdv_zbir,
					:preth_porez_osn,
					:preth_porez_pdv,
					:poreska_obaveza,
					:datum_od,
					:datum_do,
					:id_usl_k470potr,
					:id_usl_k470dug,
					:id_usl_k270potr,
					:id_usl_k270dug)";
	$stmt_pdv_unos = $baza_pdo->prepare($upit_pdv_unos);
	$stmt_pdv_unos->bindParam(':prom_osn_opst_stop', $_POST['promet_dob_opst_stopa'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':prom_osn_pos_stop', $_POST['promet_dob_posebn_stopa'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':prom_pdv_opst_stop', $_POST['promet_dob_opst_stopa_pdv'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':prom_pdv_pos_stop', $_POST['promet_dob_posebn_stopa_pdv'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':prom_osn_zbir', $_POST['promet_dob_zbir'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':prom_pdv_zbir', $_POST['promet_dob_pdv_zbir'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':preth_porez_osn', $_POST['prethodni_porez_osnovica'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':preth_porez_pdv', $_POST['prethodni_porez_pdv'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':poreska_obaveza', $_POST['poreska_obaveza'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':datum_od', $_POST['datum_od'], PDO::PARAM_STR);
	$stmt_pdv_unos->bindParam(':datum_do', $_POST['datum_do'], PDO::PARAM_STR);

	$stmt_pdv_unos->bindParam(':id_usl_k470potr', $id_usl_k470potr, PDO::PARAM_INT);
	$stmt_pdv_unos->bindParam(':id_usl_k470dug', $id_usl_k470dug, PDO::PARAM_INT);
	$stmt_pdv_unos->bindParam(':id_usl_k270potr', $id_usl_k270potr, PDO::PARAM_INT);
	$stmt_pdv_unos->bindParam(':id_usl_k270dug', $id_usl_k270dug, PDO::PARAM_INT);

	$stmt_pdv_unos->execute();
	$OK_usluga = $stmt_pdv_unos->rowCount();
	if (!$OK_usluga) {
		$error = $stmt_pdv_unos->errorInfo();
		if (isset($error[2])) {
	  		$error = $error[2];
		}
	}
	else{ ?>
		<h2>PDV obracunat...</h2>
		<a href="pdv_knizenje.php" class="dugme_zeleno_92plus4 print_hide">Pregled plata</a>
		<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>

	<?php }
}

elseif (isset($_GET['brisi'])) {
	$id_brisi=$_GET['brisi'];

	$upit_za_brisanje_usl = "SELECT
		id_usl_k470potr,
		id_usl_k470dug,
		id_usl_k270potr,
		id_usl_k270dug
		FROM pdv_obracun WHERE id='$id_brisi'";
	
	$result_za_bris_usl = $baza_pdo->query($upit_za_brisanje_usl);
	$row_za_bris_usl = $result_za_bris_usl->fetch();
	$upit_brisi_iz_usluga = 'DELETE FROM usluge
		WHERE br_usluge = ? OR br_usluge = ? OR br_usluge = ? OR br_usluge = ?';
	$stmt_brisi_iz_usluga = $baza_pdo->prepare($upit_brisi_iz_usluga);
	$stmt_brisi_iz_usluga->execute(array(
		$row_za_bris_usl['id_usl_k470potr'],
		$row_za_bris_usl['id_usl_k470dug'],
		$row_za_bris_usl['id_usl_k270potr'],
		$row_za_bris_usl['id_usl_k270dug']
		)
	);
	
	$izbrisano_iz_usluga = $stmt_brisi_iz_usluga->rowCount();
	if (!$izbrisano_iz_usluga) {
		if ($stmt_brisi_iz_usluga->errorCode() == 'HY000') {
			$error = 'That record has dependent files in a child table, and cannot be deleted.';
		}
		else {
			$error = 'There was a problem deleting the record.';
		}
	}

	$upit_brisi = 'DELETE FROM pdv_obracun WHERE id = ?';
	$stmt_brisi = $baza_pdo->prepare($upit_brisi);
	$stmt_brisi->execute(array($id_brisi));
	
	$izbrisano = $stmt_brisi->rowCount();
	if (!$izbrisano) {
		if ($stmt_brisi->errorCode() == 'HY000') {
			$error = 'That record has dependent files in a child table, and cannot be deleted.';
		}
		else {
			$error = 'There was a problem deleting the record.';
		}
	}
	else{ ?>
		<h2>Obrisano...</h2>
		<a href="pdv_knizenje.php" class="dugme_zeleno_92plus4 print_hide">Pregled PDV prijava</a>
		<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>

	<?php }
}

else{

	$upit_pregled_pdv_obr = "SELECT * FROM pdv_obracun";
	$result_pregled_pdv_obr = $baza_pdo->query($upit_pregled_pdv_obr);

	$error = $baza_pdo->errorInfo();
	if (isset($error[2])) die($error[2]);
	?>
	<table>
		<tr>
			<th rowspan="2">ID</th>
			<th colspan="2">Promet dobara i usluga po opstoj stopi</th>
			<th colspan="2">Promet dobara i usluga po posebnoj stopi</th>
			<th rowspan="2">Zbir naknade</th>
			<th rowspan="2">Zbir PDV naknade</th>
			<th colspan="2">Prethodni porez</th>
			<th rowspan="2">Datum od:</th>
			<th rowspan="2">Datum do:</th>
		</tr>
		<tr>
			
			<th>Iznos naknade bez PDV</th>
			<th>PDV</th>
			<th>Iznos naknade bez PDV</th>
			<th>PDV</th>
			

			<th>Iznos naknade bez PDV</th>
			<th>PDV</th>
			

		</tr>
	<?php 
	foreach($result_pregled_pdv_obr as $niz_pregled_pdv_obr){
	?>
		<tr>
			<td><?php echo $niz_pregled_pdv_obr['id'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_osn_opst_stop'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_pdv_opst_stop'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_osn_pos_stop'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_pdv_pos_stop'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_osn_zbir'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['prom_pdv_zbir'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['preth_porez_osn'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['preth_porez_pdv'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['datum_od'];?></td>
			<td><?php echo $niz_pregled_pdv_obr['datum_do'];?></td>
			<td>
				<a href="pdv_knizenje.php?brisi=<?php echo $niz_pregled_pdv_obr['id'];?>">
					<img src='../include/images/mini2.png' alt='Brisi' title='Brisi'>
				</a>
			</td>
		</tr>
	<?php
	}
	?>
	</table>
	<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
	<?php

}
?>
		</div>
	</body>
</html>