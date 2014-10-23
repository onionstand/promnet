<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Knjizno pismo robno</title>
		<script type="text/javascript">
			function showHint(str,str2) {
				if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else {// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","upis_stanja_partnera2.php?kol="+str+"&id="+str2,true);
				xmlhttp.send();
			}
			function pisi(tekst){
				document.write(tekst);
			}
		</script>
	</head>
	<body>
		<div class="nosac_sa_tabelom">
			<table class='tabele'>
				<tr>
					<th>Sifra</th>
					<th>Ime partnera</th>
					<th>PIB</th>
					<th>Stanje</th>
				</tr>
				<?php 
				require("../include/DbConnection.php");
				$upit=mysql_query("SELECT * FROM dob_kup");

				while ($niz=mysql_fetch_array($upit))
				{
				$sifra=$niz['sif_kup'];
				$naziv_kup=$niz['naziv_kup'];
				$pib=$niz['pib'];
				$stanje=$niz['stanje'];
				?>	
				<tr>
					<td><?php echo $sifra; ?></td>
					<td><?php echo $naziv_kup; ?></td>
					<td><?php echo $pib; ?></td>
					<td><form action=""><input type="text"  onblur="showHint(this.value,<?php echo $sifra; ?>)" value="<?php echo $stanje; ?>"/></form></td>
				</tr>
				<?php } ?>	
			</table>
			<div class="cf"></div>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
			<button class="dugme_plavo" onClick='window.print()' type='button'>Stampa</button>
			<div class="cf"></div>
		</div>
	</body>
</html>