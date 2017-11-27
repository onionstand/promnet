<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Lager lista</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<h2>Lager lista na dan <?php echo date("d.m.Y.")?></h2>
			<table>
				<tr>
					<th>Sifra</th>
					<th>Naziv robe</th>
					<th>Cena</th>
					<th>Porez</th>
					<th>Stanje</th>
				</tr>
				<?php require("../include/DbConnection.php");
				$upit = mysql_query("SELECT * FROM roba WHERE stanje!=0 ORDER BY naziv_robe ");
				while($niz = mysql_fetch_array($upit))
					{
					echo "<tr>";
					echo "<td>" . $niz['sifra'] . "</td>";
					echo "<td>" . $niz['naziv_robe'] . "</td>";
					echo "<td>" . $niz['cena_robe'] . "</td>";
					echo "<td>" . $niz['porez'] . "</td>";
					echo "<td>" . $niz['stanje'] . "</td>";
					echo "</tr>";
					}
				?>
				<tr>
					<td></td>
					<td>Ukupno:</td>
					<?php
					$upit2 = mysql_query("SELECT SUM(cena_robe*stanje) AS sum_cena_robe
					FROM roba
					WHERE stanje>=1 ORDER BY naziv_robe");
					$niz_sum = mysql_fetch_array($upit2);
					$sum_cena_robe=$niz_sum['sum_cena_robe'];
					?>
					<td colspan="3"><?php echo number_format ($sum_cena_robe, 2,"."," ");?></td>
				</tr>
			</table>
			<div class="cf"></div>
			<a class="dugme_crveno_92plus4 print_hide" href="../index.php">Pocetna strana</a>
			<a class="dugme_plavo_92plus4 print_hide" href="#" onClick='window.print()'>Stampa</a>
		</div>
	</body>
</html>