<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Popisna lista</title>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			require("../include/DbConnection.php");
			include("../include/ConfigFirma.php");
			?>
				<div class="memorandum screen_hide">
					<?php echo $inkfirma; ?>
				</div>
				<div class="cf"></div>
				<div>
					<h2><b>Popisna Lista:</b> <?php echo date("d.m.Y."); ?></h2>
				</div>
				<div class="cf"></div>
				<table class='tabele'>
					<tr>
						<th>R.Br.</th>
						<th>Sifra</th>
						<th>Ime robe</th>
						<th>Kolicina</th>
						<th>Stanje<br />lager</th>
						<th>Cena</th>
						<th>Vrednost<br />Robe</th>
					</tr>
					
					<?php 
					$i=1;
					$upit=mysql_query("SELECT * FROM roba WHERE stanje != 0 ORDER BY naziv_robe");
					while ($niz=mysql_fetch_array($upit)){
						$sifra=$niz['sifra'];
						$naziv_robe=$niz['naziv_robe'];
						$cena_robe=$niz['cena_robe'];
						$stanje=$niz['stanje']; // stanje se ne koristi
						$upisana_kolicina=$niz['kolicina']; //stanje sa popisa, koristi se
						?>
					
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $sifra; ?></td>
						<td><?php echo $naziv_robe; ?></td>
						<td><?php echo $upisana_kolicina; ?></td>
						<td><?php echo $stanje; ?></td>
						<td><?php echo $cena_robe; ?></td>
						<td><?php echo number_format(($cena_robe*$upisana_kolicina), 2, '.', ''); ?></td>
					</tr>
					<?php } ?>	
				</table>
				<div class="cf"></div>
				<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
				<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
				<div class="cf"></div>
		</div>
	</body>
</html>