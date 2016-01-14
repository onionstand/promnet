<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#polje_trazi").focus();
			});
		</script>
		<title>Profaktura</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			require("../include/DbConnection.php");
			$brojprofak=$_POST['broj_profak'];
			/*pretraga proizvoda*/
			if (isset($_POST['metode'])&& ($_POST['search']))
			{
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
				$query = mysql_query("SELECT * FROM roba WHERE $metode LIKE '%$search%' ");
				while ($row = mysql_fetch_array($query)){
					$ime=$row["naziv_robe"];
					$sifra=$row["sifra"];
					$cena=$row["cena_robe"];
					$stanje=$row["stanje"];
					$porez=$row["porez"];
					$jedmere=$row["jed_mere"];
					?>
					<tr>
						<td><?php echo $sifra;?></td>
						<td><?php echo $ime;?></td>
						<td><?php echo $cena;?></td>
						<td><?php echo $stanje;?></td>
						<td><?php echo $porez;?></td>
						<td>
							<form action='profak4.php' method='post'>
								<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
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
			<label>Rucno unesi robu:</label>
			<form action='profak4.php' method='post'>
				<table>
					<tr>
						<th>Ime robe</th>
						<th>Cena</th>
						<th>Jed.mere</th>
						<th>Porez</th>
						<th>Kolicina</th>
						<th>Rabat</th>
					</tr>
					<tr>
						<td><input type='text' name='naziv_robe' style='width:97%;'/></td>
						<td><input type='text' name='cena_r' style='width:97%;'/></td>
						<td><input type='text' name='jed_mere' style='width:97%;'/></td>
						<td><input type='text' name='porez' style='width:97%;'/></td>
						<td><input type='text' name='fak_kol' style='width:97%;'/></td>
						<td><input type='text' name='fak_rab' style='width:97%;'/></td>
					</tr>
				</table>
				<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
				<button type='submit' class='dugme_plavo'>Dodaj</button>
			</form>

			<div class="cf"></div>
			<form method='post'>
				<label>Trazi:</label>
				<input type='hidden' name='broj_profak' value='<?php echo $brojprofak;?>'/>
				<select name='metode' size='1' class='polje_100'>
					<option value='naziv_robe'>naziv robe</option>
					<option value='sifra'>sifra robe</option>
				</select>
				<input type='text' name='search' class='polje_100_92plus4' id='polje_trazi' style='margin-top:0.3em;'>
				<button type='submit' class='dugme_plavo'>Trazi</button>
			</form>
			<div class="cf"></div>
			<form action="profak5.php" method="post">
                <input type="hidden" name="broj_profak" value="<?php echo $brojprofak;?>"/>
                <button type="submit" class="dugme_crveno">Ponisti</button>
             </form>
		</div>
	</body>
</html>