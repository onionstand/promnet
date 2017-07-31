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
			if (isset($_GET['br_ponude'])){
				$brojprofak=$_GET['br_ponude'];
			}

			if (isset($_POST['br_ponude'])){
				$brojprofak=$_POST['br_ponude'];
			}
			

			$datfak_upit = "SELECT datum FROM ponuda WHERE id_ponude=".$brojprofak;
			$result_datfak = $baza_pdo->query($datfak_upit);
			$red_datfak = $result_datfak->fetch();
			$datumzaporez = $red_datfak['datum'];

			/*pretraga proizvoda*/
			if (isset($_POST['metode'])&& ($_POST['search'])){
				$metode=$_POST['metode'];
				$search=$_POST['search'];
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
				$upit = "SELECT * FROM roba WHERE ".$metode." LIKE :search";
				$terminpretrage = '%'.$search.'%';
				$stmt = $baza_pdo->prepare($upit);
				//$stmt->bindParam(':metode', $metode, PDO::PARAM_STR);
				$stmt->bindParam(':search', $terminpretrage, PDO::PARAM_STR);
				$stmt->bindColumn('naziv_robe', $ime);
				$stmt->bindColumn('sifra', $sifra);
				$stmt->bindColumn('cena_robe', $cena);
				$stmt->bindColumn('stanje', $stanje);
				$stmt->bindColumn('porez', $porez);
				$stmt->bindColumn('jed_mere', $jedmere);
				$stmt->execute();

				while ($stmt->fetch()) {
					?>
					<tr>
						<td><?php echo $sifra;?></td>
						<td><?php echo $ime;?></td>
						<td><?php echo $cena;?></td>
						<td><?php echo $stanje;?></td>
						<td><?php echo $porez;?></td>
						<td>
							<form action='ponuda_pretraga2.php' method='post'>
								<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
								<input type='hidden' name='sifra_robe' value='<?php echo $sifra;?>'/>
								<input type='hidden' name='naziv_robe' value='<?php echo $ime;?>'/>
								<input type='hidden' name='cena_r' value='<?php echo $cena;?>'/>
								<input type='hidden' name='jed_mere' value='<?php echo $jedmere;?>'/>
								<input type='hidden' name='porez' value='<?php echo $porez;?>'/>
								<input type='text' name='fak_kol' class='input' size='4'/>
						</td>
						<td>
								<input type='text' name='fak_rab' size='4'/>
						</td>
						<td>
								<input type='image' src='../include/images/plus.png' alt='Odaberi' />
							</form>
						</td>
					</tr>
					<?php
				}?>
				</table>
			<?php
			}

			?>

			<div class="cf"></div>
			<form method='post'>
				<label><b>Trazi:</b></label>
				<input type='hidden' name='br_ponude' value='<?php echo $brojprofak;?>'/>
				<select name='metode' size='1' class='polje_100'>
					<option value='naziv_robe'>naziv robe</option>
					<option value='sifra'>sifra robe</option>
				</select>
				<input type='text' name='search' class='polje_100_92plus4' id='polje_trazi' style='margin-top:0.3em;'>
				<button type='submit' class='dugme_plavo'>Trazi</button>
			</form>
			<div class="cf"></div>
			<form action="ponuda1.php" method="get">
                <input type="hidden" name="ponuda_stara" value="<?php echo $brojprofak;?>"/>
                <button type="submit" class="dugme_crveno">Ponisti</button>
             </form>
		</div>
	</body>
</html>