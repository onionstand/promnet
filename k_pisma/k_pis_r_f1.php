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
		<th>Broj<br />dostavnice</th>
		<th>Datum</th>
		<th>Kupac</th>
	</tr>
<?php
$fak = mysql_query("SELECT dosta.broj_dost, dosta.datum_d, dosta.sifra_fir, dob_kup.sif_kup, dob_kup.naziv_kup FROM dosta LEFT JOIN dob_kup 
ON dosta.sifra_fir=dob_kup.sif_kup ORDER BY dosta.broj_dost");
while($row = mysql_fetch_array($fak))
  {
  	?>
  	<tr>
  		<td><?php echo $row['broj_dost'];?></td>
  		<td><?php echo date("d.m.Y.",(strtotime($row['datum_d'])));?></td>
  		<td><?php echo $row['naziv_kup'];?></td>
  		<td>
  			<form action="k_pis_r_f2.php" method="post">
				<input type="hidden" name="broj_dost" value="<?php echo $row['broj_dost'];?>"/>
				<input type="image" src="../include/images/olovka.png" title="Odaberi" />
			</form>
		</td>
	</tr>
	<?php
  }
  ?>
</table>
<div class="cf"></div>
<a href="../index.php" class="dugme_plavo_92plus4">Pocetna strana</a>
<div class="cf"></div>
</div>
</body>
</html>