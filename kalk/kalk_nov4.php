<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Kalkulacija</title>
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#trazifokus:visible:first").focus();
			});
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php require("../include/DbConnection.php"); 
			$sifra_kalk=$_POST['broj_kalkulaci'];
			$kalkcena=$_POST['kalkcena'];
			$rabat=$_POST['rabat'];
			$pdv=$_POST['porez_pdv'];
			$marza=$_POST['marza'];
			$prod_cena=$_POST['prodajna_cena'];

			$kalkcena_min_rab=($kalkcena/100)*(100-$rabat);


			echo "<p>Broj kalkulacije: ";
			echo $sifra_kalk;
			echo "<br>";
			echo "Proracunata cena: ";
			echo number_format($prod_cena, 2,".","");
			echo "</p>";

			if (isset($_POST['metode'])&& ($_POST['search']))
			{
				$metode=$_POST['metode'];
				$search=$_POST['search'];
				?>
				<table id='tabele'>
					<tr>
						<th>Sifra</th>
						<th>Ime robe</th>
						<th>Cena</th>
						<th>Stanje</th>
						<th>Porez</th>
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
						<td><?php echo $porez;?></td>
						<td>
							<form action='kalk_nov5_star_roba.php' method='post'>
								<input type='hidden' name='sifra_r' value='<?php echo $sifra;?>'/>
								<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>'/>
								<input type='hidden' name='kalkcena_min_rab' value='<?php echo $kalkcena_min_rab;?>'/>
								<input type='hidden' name='kalkcena' value='<?php echo $kalkcena;?>'/>
								<input type='hidden' name='porez_pdv' value='<?php echo $pdv;?>'/>
								<input type='hidden' name='rabat' value='<?php echo $rabat;?>'/>
								<input type='hidden' name='marza' value='<?php echo $marza;?>'/>
								<input type='hidden' name='prodajna_cena' value='<?php echo $cena;?>'/>
								<input type='image' src='../include/images/plus.png' alt='Odaberi' />
							</form>
						</td>
					</tr>
					<?php
				}
				?>
				</table><br />
			<?php
			}
			?>
			<label>Trazi:</label>
			<form method='post'>
				<input type='hidden' name='broj_kalkulaci' value='<?php echo $sifra_kalk;?>'/>
				<input type='hidden' name='kalkcena_min_rab' value='<?php echo $kalkcena_min_rab;?>'/>
				<input type='hidden' name='porez_pdv' value='<?php echo $pdv;?>'/>
				<input type='hidden' name='rabat' value='<?php echo $rabat;?>'/>
				<input type='hidden' name='kalkcena' value='<?php echo $kalkcena;?>'/>
				<input type='hidden' name='marza' value='<?php echo $marza;?>'/>
				<input type='hidden' name='prodajna_cena' value='<?php echo $prod_cena;?>'/>
				<select name='metode' size='1' class='polje_100'>
					<option value='naziv_robe'>naziv robe</option>
					<option value='sifra'>sifra robe</option>
				</select>
				<input type='text' name='search' size='25' class='polje_100_92plus4' id='trazifokus' style='margin-top:0.3em;'>
				<button type='submit' class='dugme_zeleno'>Trazi</button>
			</form>
			<form action="kalk_nov5b_nov_rob.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk; ?>"/>
				<input type='hidden' name='kalkcena_min_rab' value='<?php echo $kalkcena_min_rab;?>'/>
				<input type='hidden' name='kalkcena' value='<?php echo $kalkcena;?>'/>
				<input type='hidden' name='porez_pdv' value='<?php echo $pdv;?>'/>
				<input type='hidden' name='rabat' value='<?php echo $rabat;?>'/>
				<input type='hidden' name='marza' value='<?php echo $marza;?>'/>
				<input type='hidden' name='prodajna_cena' value='<?php echo $prod_cena;?>'/>
				<button type='submit' class='dugme_zeleno'>Nova roba</button>
			</form>
			<form action="kalk_nov6.php" method="post">
				<input type="hidden" name="broj_kalkulaci" value="<?php echo $sifra_kalk; ?>"/>
				<button type="submit" class="dugme_crveno">Ponisti</button>
			</form>
			<div class="cf"></div>
		</div>
	</body>
</html>