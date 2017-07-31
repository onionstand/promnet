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
				$("#polje_trazi").focus();

				$("#obaveznaf").validity(function() {
					$("#r_cena")
						.require("Popuni polje.")
					$("#r_naziv_robe")
						.require("Popuni polje.")
					$("#r_jed_mere")
						.require("Popuni polje!")
					$("#r_kolicina")
						.require("Popuni polje!")
					$("#rabat")
						.require("Popuni polje!");
				});
			});
		</script>
		<title>Profaktura</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			require("../include/DbConnectionPDO.php");
			$brojprofak=$_POST['broj_profak'];

			$datfak_upit = "SELECT datum_prof FROM profak WHERE broj_prof=".$brojprofak;
			$result_datfak = $baza_pdo->query($datfak_upit);
			$red_datfak = $result_datfak->fetch();
			$datumzaporez = $red_datfak['datum_prof'];

			/*pretraga proizvoda*/
			if (isset($_POST['metode'])&& ($_POST['search'])){
				$metode=$_POST['metode'];
				$trazi=$_POST['search'];
				?>
				<table>
					<tr>
						<th>Sifra</th>
						<th>Ime robe</th>
						<th>Cena</th>
						<th>Stanje</th>
						<th>Porez</th>
						<th>Kolicina</th>
						<th>Rabat</th>
					</tr>
				<?php
				$terminpretrage = '%'.$trazi.'%';
				$upit = "SELECT * FROM roba WHERE ".$metode." LIKE :search";
				
				$stmt = $baza_pdo->prepare($upit);
				//$stmt->bindParam(':metode', $metode);
				$stmt->bindParam(':search', $terminpretrage);
				$stmt->bindColumn('naziv_robe', $ime);
				$stmt->bindColumn('sifra', $sifra);
				$stmt->bindColumn('cena_robe', $cena);
				$stmt->bindColumn('stanje', $stanje);
				$stmt->bindColumn('porez', $porez);
				$stmt->bindColumn('jed_mere', $jedmere);
				$stmt->execute();
				
				$OK = false;
				$OK = $stmt->rowCount();
				if ($OK) {
					echo "Uneseno";
				}
				else {
					$error = $stmt->errorInfo();
					if (isset($error[2])) {
						$error = $error[2];
					}
				}


				while ($stmt->fetch()) {
				?>
					<tr>
						<td><?php echo $sifra;?></td>
						<td><?php echo $ime;?></td>
						<td><?php echo $cena;?></td>
						<td><?php echo $stanje;?></td>
						<td><?php echo $porez;?></td>
						<form action='profak4.php' method='post'>
						<td>
							<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
							<input type='hidden' name='sifra_robe' value='<?php echo $sifra;?>'/>
							<input type='hidden' name='naziv_robe' value='<?php echo $ime;?>'/>
							<input type='hidden' name='cena_r' value='<?php echo $cena;?>'/>
							<input type='hidden' name='jed_mere' value='<?php echo $jedmere;?>'/>
							<input type='hidden' name='porez' value='<?php echo $porez;?>'/>
							<input type='text' name='fak_kol' class='input' size='4'/>
						</td>
						<td>
							<input type='text' name='fak_rab' class='input' size='4'/>
						</td>
						<td>
							<input type='image' src='../include/images/plus.png' alt='Odaberi' />
						</td>
						</form>
					</tr>
				<?php
				}
				?>
				</table>
			<?php
			}
			/*kraj*/
			/*dodatak za predracun*/
			?>
			<label><b>Rucno unesi robu:</b></label>
			<form action='profak4.php' method='post' id='obaveznaf'>
				<label>Naziv robe:</label>
				<input type='text' name='naziv_robe' class='polje_100_92plus4' id="r_naziv_robe"/>
				<div class="polje_20">
					<label>Cena:</label>
					<input type='text' name='cena_r' class="polje_100_92plus4" id="r_cena"/>
				</div>
				<div class="polje_20">
					<label>Jed.mere:</label>
					<input type='text' name='jed_mere' class="polje_100_92plus4" id="r_jed_mere"/>
				</div>
				<div class="polje_20">
					<label>Porez:</label>
					<select name='porez' class='polje_100_select' id="porez-pdv">
						<option value=''>Odaberi</option>
						<?php 
						$upit_pdv = "SELECT id_poreske_stope, porez_procenat, porez_datum, tarifa_stope
									FROM poreske_stope S
									WHERE porez_datum=(
									SELECT MAX(porez_datum)
									FROM poreske_stope
									WHERE tarifa_stope = S.tarifa_stope
									AND porez_datum <= '$datumzaporez'
									)";
						foreach ($baza_pdo->query($upit_pdv) as $red_pdv) { ?>
							<option value='<?php  echo $red_pdv['tarifa_stope'];?>'><?php  echo $red_pdv['porez_procenat'];?></option>
						<?php }	?>
					</select>
				</div>
				<div class="polje_20">
					<label>Kolicina:</label>
					<input type='text' name='fak_kol' class="polje_100_92plus4" id="r_kolicina"/>
				</div>
				<div class="polje_20">
					<label>Rabat:</label>
					<input type='text' name='fak_rab' class="polje_100_92plus4" id="rabat"/>
				</div>

				<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
				<button type='submit' class='dugme_plavo'>Dodaj</button>
			</form>
			<form method='post'>
				<label><b>Trazi:</b></label>
				<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
				<select name='metode' size='1' class='polje_100'>
					<option value='naziv_robe'>naziv robe</option>
					<option value='sifra'>sifra robe</option>
				</select>
				<input type='text' name='search' size='25' id='polje_trazi' class='polje_100_92plus4' style='margin-top:0.3em;'>
				<button type='submit' class='dugme_plavo'>Trazi</button>
			</form>
			<form action='profak_brisi1.php' method='post'>
				<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
				<button type='submit' class='dugme_crveno'>Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>