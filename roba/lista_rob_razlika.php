<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Prenos Robnih Razlika</title>
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
				<p><b>Lista robnih razlika na dan:</b> <?php echo date("d.m.Y."); ?></p>
			</div>
			<div class="cf"></div>
			<table>
				<tr>
					<th>Sifra</th>
					<th>Naziv robe</th>
					<th>Kol. Knjig.</th>
					<th>Kol. Popis</th>
					<th>Robna razlika</th>
					<th>Cena</th>
					<th>Iznos Knjig.</th>
					<th>Iznos Popis</th>
					<th>Razlike iznos</th>
					<th>RUC</th>
				</tr>
				<?php 
				$upit = mysql_query("SELECT * FROM roba WHERE stanje>=1 OR 	kolicina>=1 ORDER BY naziv_robe ");
				while($niz = mysql_fetch_array($upit))
					{
					
					$stanje=$niz['stanje'];
					$kolicina=$niz['kolicina'];
					$cena_robe=$niz['cena_robe'];
					
					$robna_razlika= $stanje - $kolicina;
					$iznos_knjig= $cena_robe * $stanje;
					$iznos_popis= $cena_robe * $kolicina;
					$razlike_iznos= ($cena_robe * $stanje) - ($cena_robe * $kolicina);
					?>
					<tr>
						<td><?php echo $niz['sifra'];?></td>
						<td><?php echo $niz['naziv_robe'];?></td>
						<td><?php echo $stanje;?></td>
						<td><?php echo $kolicina;?></td>
						<td><?php echo $robna_razlika;?></td>
						<td><?php echo $cena_robe;?></td>
						<td><?php echo $iznos_knjig;?></td>
						<td><?php echo $iznos_popis;?></td>
						<td><?php echo $razlike_iznos;?></td>
						<td><?php echo $niz['ruc'];?></td>
					</tr>
					<?php
					};
				?>
			</table>
			<div class="cf"></div>
			<a class="dugme_crveno_92plus4 print_hide" href="../index.php">Pocetna strana</a>
			<a class="dugme_plavo_92plus4 print_hide" href="#" onClick='window.print()'>Stampa</a>
			<a class="dugme_zeleno_92plus4 print_hide" href="prenos_rob_razlike.php">Eksportuj stanje za prenos u sledecu godinu</a>
		</div>
	</body>
</html>