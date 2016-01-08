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
			<?php require("../include/DbConnectionPDO.php");
			if (isset($_GET['id_plate_brisanje'])) {
				$id_plate_brisanje=$_GET['id_plate_brisanje'];

				$upit_iz_plate_za_usl = "SELECT
				id_usluge_doprinosi
				FROM plate WHERE id_plate='$id_plate_brisanje'";

				$result_iz_plate_za_usl = $baza_pdo->query($upit_iz_plate_za_usl);
				$row_iz_plate_za_usl = $result_iz_plate_za_usl->fetch();
				$row_iz_plate_za_usl_niz=explode(",", $row_iz_plate_za_usl['id_usluge_doprinosi']);

				foreach ($row_iz_plate_za_usl_niz as $id_za_brisanje) {
				
					$upit_brisi_iz_usluga = 'DELETE FROM usluge WHERE br_usluge = ?';
					$stmt_brisi_iz_usluga = $baza_pdo->prepare($upit_brisi_iz_usluga);
					$stmt_brisi_iz_usluga->execute(
							array(
									$id_za_brisanje
								)
						);
					
					$izbrisano_iz_usluga = $stmt_brisi_iz_usluga->rowCount();
					if (!$izbrisano_iz_usluga) {
						if ($stmt_brisi_iz_usluga->errorCode() == 'HY000') {
							$error = 'That record has dependent files in a child table, and cannot be deleted.';
						} else {
							$error = 'There was a problem deleting the record.';
						}
					}
				}




				$upit_brisi = 'DELETE FROM plate WHERE id_plate = ?';
				$stmt_brisi = $baza_pdo->prepare($upit_brisi);
				$stmt_brisi->execute(array($id_plate_brisanje));
				
				$izbrisano = $stmt_brisi->rowCount();
				if (!$izbrisano) {
					if ($stmt_brisi->errorCode() == 'HY000') {
						$error = 'That record has dependent files in a child table, and cannot be deleted.';
					} else {
						$error = 'There was a problem deleting the record.';
					}
				}
			}

			if (isset($_GET['id_plate'])) {
				$id_plate = (int) $_GET['id_plate'];
				$sql = "SELECT * FROM plate WHERE id_plate = $id_plate";
				$result = $baza_pdo->query($sql);
				$red = $result->fetch();
				
				$error = $baza_pdo->errorInfo();
				if (isset($error[2])) die($error[2]);

				$datum=$red['datum_plate'];
				$danstr=strtotime( $datum );
				$datum_za_bazu=date("d.m.Y",$danstr);
				?>
				<h2>Obracun plate</h2>
				<table>
					<tr>
						<th colspan="2">Podaci o primaocu</th>
						<th colspan="2">Trenutno vazeci elementi obracuna</th>
					</tr>
					<tr>
						<td>Datum:</td>
						<td><?php echo $datum_za_bazu;?></td>
						<td>Iznos poreskog umanjenja:</td>
						<td><?php echo $red['iznos_por_umanje'];?></td>
					</tr>
					<tr>
						<td>Redni broj:</td>
						<td><?php echo $red['redni_br'];?></td>
						<td>Poreska stopa:</td>
						<td><?php echo $red['poreska_stopa'];?></td>
					</tr>
					<tr>
						<td>Vrsta identifikacije primaoca prihoda:</td>
						<td><?php echo $red['vrsta_ind_prim_prih'];?></td>
						<td>PIO osiguranje na teret radnika (stopa):</td>
						<td><?php echo $red['pio_radnika_stopa'];?></td>
					</tr>
					<tr>
						<td>Podatak za identifikaciju lica:</td>
						<td><?php echo $red['jmbg'];?></td>
						<td>Zdravstveno osiguranje na teret radnika (stopa):</td>
						<td><?php echo $red['zdrav_radnika_stopa'];?></td>
					</tr>
					<tr>
						<td>Prezime:</td>
						<td><?php echo $red['prezime'];?></td>
						<td>Zaposljavanje na teret radnika (stopa):</td>
						<td><?php echo $red['zapos_radnika_stopa'];?></td>
					</tr>
					<tr>
						<td>Ime:</td>
						<td><?php echo $red['ime'];?></td>
						<td>Ukupno na teret radnika:</td>
						<td><?php echo $red['ukupno_ter_radnik'];?></td>
					</tr>
					<tr>
						<td>Sifra opstine prebivalista:</td>
						<td><?php echo $red['sifra_opstine'];?></td>
						<td>PIO osiguranje na teret preduzeca (stopa):</td>
						<td><?php echo $red['pio_preduz_stopa'];?></td>
					</tr>
					<tr>
						<td>Sifra vrste prihoda:</td>
						<td><?php echo $red['sifra_vrste_prih'];?></td>
						<td>Zdravstveno osiguranje na teret preduzeca (stopa):</td>
						<td><?php echo $red['zdrav_predu_stopa'];?></td>
					</tr>
					<tr>
						<td>Broj kalendarskih dana:</td>
						<td><?php echo $red['broj_dana'];?></td>
						<td>Zaposljavanje na teret preduzeca (stopa):</td>
						<td><?php echo $red['zapos_preduz_stopa'];?></td>
					</tr>
					<tr>
						<td>Broj sati:</td>
						<td><?php echo $red['broj_sati'];?></td>
						<td>Ukupno na teret preduzeca:</td>
						<td><?php echo $red['ukupno_ter_predu'];?></td>
					</tr>
				</table>
				<br>
				<table>
					<tr>
						<th colspan="2">Obracun</th>
					</tr>
					<tr>
						<td>Neto zarada:</td>
						<td><?php echo number_format($red['neto_zarada'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Bruto zarada:</td>
						<td><?php echo number_format($red['bruto_zarada'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Poresko umanjenje:</td>
						<td><?php echo number_format($red['poresko_umanj'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Osnovica za obracun poreza:</td>
						<td><?php echo number_format($red['osnovica_za_porez'], 0, '.', '');?></td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td>Porez na licna primanja za uplatu:</td>
						<td><?php echo number_format($red['porez_na_licna_prim'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>PIO osiguranje na teret radnika za uplatu:</td>
						<td><?php echo number_format($red['pio_radnik_uplat'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Zdravstveno osiguranje na teret radnika za uplatu:</td>
						<td><?php echo number_format($red['zdrav_radnik_upl'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Zaposljavanje na teret radnika za uplatu:</td>
						<td><?php echo number_format($red['zaposl_radnik_upl'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<b>
								<?php
								$ukupno_ter_tadnika=
								number_format($red['porez_na_licna_prim'], 0, '.', '')+
								number_format($red['pio_radnik_uplat'], 0, '.', '')+
								number_format($red['zdrav_radnik_upl'], 0, '.', '')+
								number_format($red['zaposl_radnik_upl'], 0, '.', '');
								echo $ukupno_ter_tadnika;
								?>
							</b>
						</td>
					</tr>
					<tr>
						<td>PIO osiguranje na teret preduzeca za uplatu:</td>
						<td><?php echo number_format($red['pio_preduz_uplat'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Zdravstveno osiguranje na teret preduzeca za uplatu:</td>
						<td><?php echo number_format($red['zdravstv_preduz_upl'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td>Zaposljavanje na teret preduzeca za uplatu:</td>
						<td><?php echo number_format($red['zaposlj_preduz_upl'], 0, '.', '');?></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<b>
								<?php
								$ukupno_ter_poslodavca=
								number_format($red['pio_preduz_uplat'], 0, '.', '')+
								number_format($red['zdravstv_preduz_upl'], 0, '.', '')+
								number_format($red['zaposlj_preduz_upl'], 0, '.', '');
								echo $ukupno_ter_poslodavca;
								?>
							</b>
						</td>
					</tr>
					<tr>
						<td>Ukupni doprinosi i porez za uplatu:</td>
						<td>
							<strong>
								<?php //echo $red['ukupni_doprinosi'];
								echo $ukupno_ter_poslodavca + $ukupno_ter_tadnika;
								?>
							</strong>
						</td>
					</tr>
				</table>
				<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
				<a href="plate_pregled.php" class="dugme_zeleno_92plus4 print_hide">Pregledaj plate</a>
				<a href="plate_isplatni_list.php?id_plate=<?php echo $red['id_plate'];?>" class="dugme_zeleno_92plus4 print_hide">Isplatni list</a>
				<a href="plate_pregled.php?id_plate_brisanje=<?php echo $red['id_plate'];?>" class="dugme_crveno_92plus4 print_hide">Obrisi platu</a>
				<?php
			}
			else{
				$sql = "SELECT * FROM plate";
				$result = $baza_pdo->query($sql);

				$error = $baza_pdo->errorInfo();
				if (isset($error[2])) die($error[2]);
				?>
				<table>
					<tr>
						<th>ID Plate</th>
						<th>Datum Plate</th>
						<th>Ime</th>
						<th>Neto zarada</th>
						<th>Bruto zarada</th>
						<th>Ukupni doprinosi</th>
						<th></th>
					</tr>
					<?php 
					foreach($result as $niz){
						?>
						<tr>
							<td><?php echo $niz['id_plate'];?></td>
							<td><?php echo $niz['datum_plate'];?></td>
							<td><?php echo $niz['ime'] . " " . $niz['prezime'];?></td>
							<td><?php echo $niz['neto_zarada'];?></td>
							<td><?php echo $niz['bruto_zarada'];?></td>
							<td><?php echo $niz['ukupni_doprinosi'];?></td>
							<td>
								<a href="plate_pregled.php?id_plate=<?php echo $niz['id_plate'];?>">
									<img src='../include/images/olovka.png' alt='Pregledaj' title='Pregledaj'>
								</a>
							</td>
						</tr>
					<?php
					}
					?>
				</table>
			<?php
			}
			?>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna</a>
		</div>
	</body>
</html>