<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Stara nivelacija</title>
	</head>
	<body>
		<?php require("../include/DbConnection.php"); ?>
		<div class="nosac_sa_tabelom">
			<?php
			$izkalk = mysql_query("SELECT broj_niv, date_format(datum_niv, '%d. %m. %Y.') as datumn FROM nivel");
			?>
			<table>
				<tr>
					<th>Broj nivel.</th>
					<th>Datum</th>
					<th></th>
				</tr>
				<?php
				while($niz = mysql_fetch_array($izkalk))
			  {
			  	?>
			  	<tr>
			  		<td><?php echo $niz['broj_niv'];?></td>
			  		<td><?php echo $niz['datumn'];?></td>
			  		<td>
			  			<form action="nivelacija4.php" method="post">
			  				<input type="hidden" name="br_niv" value="<?php echo  $niz['broj_niv'];?>"/>
			  				<input type="image" src="../include/images/olovka.png" title="Ispravi" />
			  			</form>
			  		</td>
			  	</tr>
			  	<?php
			  }
			  ?>
			</table>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>