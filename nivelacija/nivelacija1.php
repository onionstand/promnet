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
						<th>Kolicina za niv.</th>
					</tr>
				<?php
				$query = mysql_query("SELECT * FROM roba WHERE $metode LIKE '%$search%' ");
				while ($row = mysql_fetch_array($query))
				{
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
						<form action='nivelacija2.php' method='post'>
							<input type='hidden' name='stara_sifra' value='<?php echo $sifra;?>'/>
							<?php if (isset($_POST['br_niv'])) {
								?><input type='hidden' name='br_niv' value='<?php echo $_POST['br_niv'];?>'/>
							<?php } ?>
							<input type='hidden' name='cena_r' value='<?php echo $cena;?>'/>
							<input type='hidden' name='stanje' value='<?php echo $stanje;?>'/>
							<input type='hidden' name='stari_porez' value='<?php echo $porez;?>'/>
						<td align='center'>
							<input type='text' name='niv_kol' value='<?php echo $stanje;?>' class='input' size='4'/>
						</td>
						<td>
							<input type='image' src='../include/images/nivel.png' alt='Odaberi' />
						</td>
						</form>
					</tr>
				<?php
				} ?>
				</table>
				<?php
			} ?>
		<form method='post'>
			<label>Trazi:</label>
			<select name='metode' size='1' class='polje_100'>
				<option value='naziv_robe'>naziv robe</option>
				<option value='sifra'>sifra robe</option>
			</select>
			<?php if (isset($_POST['br_niv']))
			{
				echo "<input type='hidden' name='br_niv' value='" . $_POST['br_niv'] . "'/>";
			}
			?>
			<input type='text' name='search' size='25' class='polje_100_92plus4' style='margin-top:0.3em;'><br \>
			<button type='submit' class='dugme_zeleno'>Trazi</button>
		</form>
		<?php if (isset($_POST['br_niv']))
		{}
		else {
			?>
			<form action='../index.php' method='post'>
				<button type='submit' class='dugme_crveno'>Ponisti</button>
			</form>
		<?php
		}
		?>
		<div class="cf"></div>
	</div>
	</body>
</html>