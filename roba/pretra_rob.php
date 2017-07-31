<?php
require("../include/DbConnection.php");
function PretragaPoTerminu($ime_polja, $termin_pretrage,$query_tip){
	if ($query_tip==1){
		$upit = mysql_query("SELECT * FROM roba WHERE ".$ime_polja." LIKE '%".$termin_pretrage."%'");
		if (!$upit) {
    		echo "<h2>Nema rezultata...</h2>";
		}
	}
	if ($query_tip==2){
		$upit = mysql_query("SELECT * FROM roba WHERE ".$ime_polja."=".$termin_pretrage);
		if (!$upit) {
    		echo "<h2>Nema rezultata...</h2>";
		}
	}
	if ($query_tip==3){
		$upit = mysql_query("SELECT * FROM roba");
	}

	?>
	<table class="sortable">
			<tr>
				<th>Sifra</th>
				<th>Sifra knjig.</th>
				<th>Naziv robe</th>
				<th>Cena</th>
				<th>J.mere</th>
				<th>Porez</th>
				<th>Stanje</th>
				<th>RUC</th>
			</tr>
	<?php
	while($niz = mysql_fetch_array($upit)){
	?>
		<tr>
			<td><?php echo $niz['sifra'];?></td>
			<td><?php echo $niz['sifra_knjig'];?></td>
			<td><?php echo $niz['naziv_robe'];?></td>
			<td><?php echo $niz['cena_robe'];?></td>
			<td><?php echo $niz['jed_mere'];?></td>
			<td><?php echo $niz['porez'];?></td>
			<td><?php echo $niz['stanje'];?></td>
			<td><?php echo $niz['ruc'];?></td>
			<td><form action="kartica_rob.php" method="post">
					<input type="hidden" name="sifra_robe" value="<?php echo $niz['sifra'];?>"/>
					<input type="image" src="../include/images/kartica.png" title="Pogledaj karticu"/>
				</form>
			</td>
			<td><form action="promeni_ime_rob.php" method="post">
					<input type="hidden" name="sifra_robe" value="<?php echo $niz['sifra'];?>"/>
					<input type="image" src="../include/images/olovka.png" title="Promeni naziv"/>
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

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script src="../include/sorttable.js"></script>
		<title>Roba</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
				<?php
				if (isset($_POST['ime_robe'])) {
					PretragaPoTerminu("naziv_robe", $_POST['ime_robe'],1);
				}
				if (isset($_POST['sifra_robe'])) {
					PretragaPoTerminu("sifra", $_POST['sifra_robe'],2);
				}
				if (isset($_POST['sva_roba'])) {
					PretragaPoTerminu("sifra", $_POST['sva_roba'],3);
				}
				?>
			<div class="cf"></div>
			<form method="post">
				<label>Ime robe:</label>
				<input type="text" name="ime_robe" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_plavo">Trazi</button>
			</form>
			<form method="post">
				<label>Sifra robe:</label>
				<input type="text" name="sifra_robe" class="polje_100_92plus4"/>
				<button type="submit" class="dugme_plavo">Trazi</button>
			</form>
			<form method="post">
				<label>Sva roba:</label>
				<input type="hidden" type="text" name="sva_roba" class="polje_100_92plus4" value="1"/>
				<button type="submit" class="dugme_plavo">Prikazi</button>
			</form>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<div class="cf"></div>
		</div>
	</body>
</html>