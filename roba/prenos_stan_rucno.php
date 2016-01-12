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
	 	$("#forma_prenos").validity(function() {
	 		$(".polje_100_92plus4")
	 		.require()
	 	});
	 });
	</script>
	<title>Prenos stanja - rucni</title>
</head>
<body>
	<div class="nosac_sa_tabelom">
	<?php
	require('../include/DbConnection.php');
	
	if (isset($_POST['id_robe']))
	{
		$id_robe=$_POST['id_robe'];
		mysql_query("DELETE FROM prenos_stan WHERE id='$id_robe'")
		or die(mysql_error());
	}
	
	if (isset($_POST['naziv_robe'])&& ($_POST['cena_robe'])&& ($_POST['porez'])&& ($_POST['jed_mere'])&&  ($_POST['kolicina']))
	{
		$naziv_robe=$_POST['naziv_robe'];
		$cena_robe=$_POST['cena_robe'];
		$porez=$_POST['porez'];
		$jed_mere=$_POST['jed_mere'];
		$ruc=$_POST['ruc'];
		$kolicina=$_POST['kolicina'];
		mysql_query("INSERT INTO prenos_stan (naziv_robe, cena_robe, porez, jed_mere, ruc, kolicina) VALUES
		('".$naziv_robe."', '".$cena_robe."', '".$porez."', '".$jed_mere."', '".$ruc."', '".$kolicina."') ")
		or die(mysql_error());
	}
	else{
		echo "<p>GRESKA! Nesto je pogresno uneseno.</p>";
	}
	
	
	$query= 'SELECT * FROM prenos_stan ORDER BY naziv_robe';
			$result = mysql_query($query) or die ("Error in query: $query " . mysql_error());
			$num_results = mysql_num_rows($result);
			if ($num_results > 0){
				?>
				<table>
					<tr>
						<td>Naziv Robe: </td>
						<td>Cena robe: </td>
						<td>Porez: </td>
						<td>Jed. mere: </td>
						<td>RUC: </td>
						<td>Kolicina: </td>
					</tr>
				<?php
				
				while($row = mysql_fetch_array($result))
				{
					$naziv_robe1=$row['naziv_robe'];
					$cena_robe1=$row['cena_robe'];
					$porez1=$row['porez'];
					$jed_mere1=$row['jed_mere'];
					$ruc1=$row['ruc'];
					$kolicina1=$row['kolicina'];
					$id=$row['id'];
					?>
					<tr>
						<td><?php echo $naziv_robe1; ?></td>
						<td><?php echo $cena_robe1; ?></td>
						<td><?php echo $porez1; ?></td>
						<td><?php echo $jed_mere1; ?></td>
						<td><?php echo $ruc1; ?></td>
						<td><?php echo $kolicina1; ?></td>
						<td><form action="" method="post">
						<input type="hidden" name="id_robe" value="<?php echo $id;?>"/>
						<input type="image" src="../include/images/iks.png" title="Brisi" />
						</form></td>
					</tr>
					<?php
					
				}
				?>
				</table>
				<?php
				
			}
			else{
				echo "<p>Nema podataka</p>";
			}
	
	?>
	<h2>Unos:</h2>
	<form action="" method="post" id="forma_prenos">
		<label>Naziv Robe:</label>
		<input type="text" name="naziv_robe" class="polje_100_92plus4" />
		<label>Cena robe:</label>
		<input type="text" name="cena_robe" class="polje_100_92plus4"/>
		<label>Porez:</label>
		<input type="text" name="porez" class="polje_100_92plus4"/>
		<label>Jed. mere:</label>
		<input type="text" name="jed_mere" class="polje_100_92plus4"/>
		<label>RUC:</label>
		<input type="text" name="ruc" class="polje_100_92plus4"/>
		<label>Kolicina:</label>
		<input type="text" name="kolicina" class="polje_100_92plus4"/>
		<button type="submit" class="dugme_zeleno">Unesi</button>
	</form>
	<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
	<div class="cf"></div>
	</div>
</body>
</html>