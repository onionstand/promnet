<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Stara knjizna pisma</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<h2>Knjizna pisma</h2>
			<?php require("../include/DbConnection.php"); 
			$upit = mysql_query("SELECT k_pism_r.broj_k, k_pism_r.kod_p, date_format(k_pism_r.dat_k, '%d. %m. %Y.') AS datumf, k_pism_r.dos_kal, k_pism_r.partner 
			FROM k_pism_r");
			?>
			<table id='tabele'>
				<tr>
					<th>Broj k.pisma</th>
					<th>Partner</th>
					<th>Datum</th>
					<th>Broj dokumenta</th>
					<th></th>
				</tr>
			<?php
			while($niz = mysql_fetch_array($upit))
			{
				$kod_k_p=$niz['kod_p'];
				$broj_k=$niz['broj_k'];
				?>
				<tr>
					<td><?php echo $broj_k;?></td>
					<td><?php echo $niz['partner'];?></td>
					<td><?php echo $niz['datumf'];?></td>
					<td><?php echo $niz['dos_kal'];?></td>
					<?php IF ($kod_k_p==1){	?>
					<td>
						<form action="k_pis_r_k4.php" method="post">
							<input type="hidden" name="br_k_pis" value="<?php echo $broj_k;?>"/>
							<input type="image" src="../include/images/olovka.png" title="Ispravi" />
						</form>
					</td>
					<?php }
					IF ($kod_k_p==2){?>
					<td>
						<form action="k_pis_r_f4.php" method="post">
							<input type="hidden" name="br_k_pis" value="<?php echo $broj_k;?>"/>
							<input type="image" src="../include/images/olovka.png" title="Ispravi" />
						</form>
					</td>
					<?php } ?>
				</tr>
			<?php
			} ?>
			</table>
			<br />
			<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>