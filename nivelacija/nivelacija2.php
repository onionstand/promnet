<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Nivelacija</title>
	</head>
	<body>
		<?php require("../include/DbConnection.php"); ?>
		<div class="nosac_sa_tabelom">
			<?php
			$sifra_r=$_POST['stara_sifra'];
			$niv_kol=$_POST['niv_kol'];
			$stari_porez=$_POST['stari_porez'];

			if (isset($_POST['br_niv'])){
				$br_niv=$_POST['br_niv'];
			}
			else {
				mysql_query("INSERT INTO nivel (broj_niv,datum_niv) VALUES (DEFAULT, CURDATE())");
				$br_niv = mysql_insert_id();

				$upit = mysql_query("SELECT date_format(datum_niv, '%d. %m. %Y.') as formatted_date FROM nivel WHERE broj_niv='$br_niv'");
				while($niz = mysql_fetch_array($upit)){
					echo "Broj nivelacije: ". $br_niv . "<br>Datum: " . $niz['formatted_date'];
				}
			}	
				
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
					</tr>
				<?php
				$query = mysql_query("SELECT * FROM roba WHERE $metode LIKE '%$search%' ");
				while ($row = mysql_fetch_array($query)){
					$ime=$row["naziv_robe"];
					$sifra=$row["sifra"];
					$cena=$row["cena_robe"];
					$stanje=$row["stanje"];
					$porez=$row["porez"];
					?>		
					<tr>
						<td><?php echo $sifra;?></td>
						<td><?php echo $ime;?></td>
						<td><?php echo $cena;?></td>
						<td><?php echo $stanje;?></td>
						<td>
							<form action='nivelacija3a.php' method='post'>
								<input type='hidden' name='sifra_robe' value='<?php echo $sifra;?>'/>
								<input type='hidden' name='cena_r' value='<?php echo $cena;?>'/>
								<input type='hidden' name='stanje' value='<?php echo $stanje;?>'/>
								<input type='hidden' name='br_niv' value='<?php echo $br_niv;?>'/>
								<input type='hidden' name='stara_sifra' value='<?php echo $sifra_r;?>'/>
								<input type='hidden' name='niv_kol' value='<?php echo $niv_kol;?>'/>
								<input type='hidden' name='porez_pdv' value='<?php echo $porez;?>'/>
								<input type='hidden' name='stari_porez' value='<?php echo $stari_porez;?>'/>
								<input type='image' src='../include/images/nivel.png' alt='Odaberi' />
							</form>
						</td>
					</tr>
					<?php
				}
				?>
				</table>
			<?php	
			}
			?>
			<form method='post'>
				<label>Trazi:</label>
				<select name='metode' size='1' class='polje_100'>
					<option value='naziv_robe'>naziv robe</option>
					<option value='sifra'>sifra robe</option>
				</select>
				<input type='text' name='search' size='25' class='polje_100_92plus4' style='margin-top:0.3em;'>
				<button type='submit' class='dugme_plavo'>Trazi</button>
				<input type='hidden' name='stara_sifra' value='<?php echo $sifra_r; ?>'/>
				<input type='hidden' name='niv_kol' value='<?php echo $niv_kol;?>'/>
				<input type='hidden' name='br_niv' value='<?php echo $br_niv;?>'/>
				<input type='hidden' name='stari_porez' value='<?php echo $stari_porez;?>'/>
			</form>

			<form action="nivelacija3b.php" method="post">
				<input type='hidden' name='stara_sifra' value='<?php echo $sifra_r; ?>'/>
				<input type='hidden' name='niv_kol' value='<?php echo $niv_kol;?>'/>
				<input type='hidden' name='br_niv' value='<?php echo $br_niv;?>'/>
				<input type='hidden' name='stari_porez' value='<?php echo $stari_porez;?>'/>
				<button type='submit' class='dugme_zeleno'>Nova roba</button>
			</form>
			<?php if (isset($_POST['br_niv']))
			{}
			else {
				?>
				<form action='../index.php' method='post'>
					<input type='hidden' name='br_niv' value='<?php echo $br_niv;?>'/>
					<button type='submit' class='dugme_crveno'>Ponisti</button>
				</form>
			<?php
			}
			?>
			<div class="cf"></div>
		</div>
	</body>
</html>