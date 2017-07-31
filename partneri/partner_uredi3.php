<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Partner</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			require("../include/DbConnection.php");
			$odabpart=$_POST['sifra_kup'];

			mysql_query("UPDATE dob_kup SET
					 naziv_kup = '$_POST[naziv_kup]',
					 postbr = '$_POST[postbr]',
					 mesto_kup = '$_POST[mesto_kup]',
					 ulica_kup = '$_POST[ulica_kup]',
					 rab_ugo = '$_POST[rab_ugo]',
					 ziro_rac  = '$_POST[ziro_rac]',
					 ziro_rac2  = '$_POST[ziro_rac2]',
					 tel = '$_POST[tel]',
					 pib = '$_POST[pib]',
					 mat_br = '$_POST[mat_br]'
					 WHERE sif_kup = '$odabpart'");
			?>
			<table>
				<tr>
					<th>Sifra</th>
					<th>Ime</th>
					<th>Post. broj</th>
					<th>Mesto</th>
					<th>Ulica</th>
					<th>Ugo. rabat</th>
					<th>Ziro racun</th>
					<th>Telefon</th>
					<th>PIB</th>
					<th>Maticni br.</th>
				</tr>
				<?php
				$upit= mysql_query("SELECT * FROM dob_kup WHERE sif_kup = '$odabpart' ");
				while ($partneri1 = mysql_fetch_array($upit))
				{
				$sifrap=$partneri1["sif_kup"];
				?>
				<tr>
					<td><?php echo $sifrap;?></td>
					<td><?php echo $partneri1["naziv_kup"];?></td>
					<td><?php echo $partneri1["postbr"];?></td>
					<td><?php echo $partneri1["mesto_kup"];?></td>
					<td><?php echo $partneri1["ulica_kup"];?></td>
					<td><?php echo $partneri1["rab_ugo"];?></td>
					<td><?php echo $partneri1["ziro_rac"];?></td>
					<td><?php echo $partneri1["ziro_rac2"];?></td>
					<td><?php echo $partneri1["tel"];?></td>
					<td><?php echo $partneri1["pib"];?></td>
					<td><?php echo $partneri1["mat_br"];?></td>
					<td>
						<form action='partner_uredi2.php' method='post'>
							<input type='hidden' name='sifra_p' value='<?php echo $sifrap;?>'/>
							<input type='image' src='../include/images/plus.png' alt='Odaberi' />
						</form>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>