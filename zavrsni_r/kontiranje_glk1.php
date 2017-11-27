<?php
require("../include/DbConnectionPDO.php");?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Glavna Knjiga</title>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<table>
				<tr>
					<th>Konto</th>
					<th>Naziv konta</th>
					<th>Opis</th>
					<th>Duguje</th>
					<th>Potrazuje</th>
					<th>Razlika</th>
				</tr>
				<?php $upit_prikaz_gl_k = 'SELECT glknjiga.brkonta,
											glknjiga.opis,
											SUM(glknjiga.duguje) AS sum_duguje,
											SUM(glknjiga.potraz) AS sum_potraz,
											konto.naziv_kont 
											FROM glknjiga
											LEFT JOIN konto ON glknjiga.brkonta=konto.broj_kont
											GROUP BY brkonta';
				$zbir_sum_duguje=0;
				$zbir_sum_potraz=0;
				foreach ($baza_pdo->query($upit_prikaz_gl_k) as $red_gl_k) {
					$zbir_sum_duguje+=$red_gl_k['sum_duguje'];
					$zbir_sum_potraz+=$red_gl_k['sum_potraz'];
					?>
					<tr>
						<td><?php echo $red_gl_k['brkonta']; ?></td>
						<td><?php echo $red_gl_k['naziv_kont']; ?></td>
						<td><?php echo $red_gl_k['opis']; ?></td>
						<td><?php echo number_format($red_gl_k['sum_duguje'], 2,".",","); ?></td>
						<td><?php echo number_format($red_gl_k['sum_potraz'], 2,".",","); ?></td>
						<td><?php echo number_format($red_gl_k['sum_duguje']-$red_gl_k['sum_potraz'], 2,".",","); ?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td></td>
					<td></td>
					<td>Zbir: </td>
					<td><?php echo number_format($zbir_sum_duguje, 2,".",","); ?></td>
					<td><?php echo number_format($zbir_sum_potraz, 2,".",","); ?></td>
					<td><?php $zbir=$zbir_sum_duguje-$zbir_sum_potraz;echo number_format($zbir, 2,".",",");?></td>
				</tr>
			</table>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
			<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
			<div class="cf"></div>
		</div>
	</body>
</html>