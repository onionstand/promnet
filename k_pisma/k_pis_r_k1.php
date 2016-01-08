<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo robno</title>
	</head>
	<body>
		<?php require("../include/DbConnection.php"); ?>
		<div class="nosac_glavni_400">
			<table>
				<tr>
					<th>Broj<br />kalkulacije</th>
					<th>Datum</th>
					<th>Dobavljac</th>
				</tr>
				<?php
				$kalk = mysql_query("SELECT kalk.broj_kalk, kalk.datum, kalk.sif_firme, dob_kup.sif_kup, dob_kup.naziv_kup FROM kalk LEFT JOIN dob_kup 
				ON kalk.sif_firme=dob_kup.sif_kup ORDER BY kalk.broj_kalk");
				while($row = mysql_fetch_array($kalk))
					{ ?>
			 			<tr>
			 				<td><?php echo $row['broj_kalk'];?></td>
			 				<td><?php echo date("d.m.Y.",(strtotime($row['datum'])));?></td>
			 				<td><?php echo $row['naziv_kup'];?></td>
			 				<td>
			 					<form action="k_pis_r_k2.php" method="post">
			 						<input type="hidden" name="broj_kalk" value="<?php echo $row['broj_kalk'];?>"/>
			 						<input type="image" src="../include/images/olovka.png" title="Odaberi" />
			 					</form>
			 				</td>
			 			</tr>
			 			<?php
			  } ?>
			</table>
			<a href="../index.php" class="dugme_zeleno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>