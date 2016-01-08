<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo robno</title>
		<script type="text/javascript">
			function showHint(str,str2){
				if (window.XMLHttpRequest){
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else {
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","upis_stanja2.php?kol="+str+"&id="+str2,true);
				xmlhttp.send();
			}
			function pisi(tekst){
				document.write(tekst);
			}
		</script>
	</head>
	<body>
	<div class="nosac_sa_tabelom">
		<table>
			<tr><th>Sifra</th>
				<th>Ime robe</th>
				<th>Cena</th>
				<th>Kolicina</th>
				<th>Upis kolicine</th>
			</tr>
		<?php 
		require("../include/DbConnection.php");
		$upit=mysql_query("SELECT * FROM roba ORDER BY naziv_robe");

		while ($niz=mysql_fetch_array($upit)) {
			$sifra=$niz['sifra'];
			$naziv_robe=$niz['naziv_robe'];
			$cena_robe=$niz['cena_robe'];
			$stanje=$niz['stanje'];
			$upis_kolicina=$niz['kolicina'];
			?>
				
			<tr>
				<td><?php echo $sifra; ?></td>
				<td><?php echo $naziv_robe; ?></td>
				<td><?php echo $cena_robe; ?></td>
				<td><?php echo $stanje; ?></td>
				<td><form action=""><input type="text" onblur="showHint(this.value,<?php echo $sifra; ?>)" value="<?php echo $upis_kolicina; ?>"/></form></td>
			</tr>
		<?php 
		} ?>	
		</table>
		<div class="cf"></div>
		<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
		<button class="dugme_zeleno print_hide" onClick='window.print()' type='button'>Stampa</button>
		<div class="cf"></div>
	</div>
	</body>
</html>