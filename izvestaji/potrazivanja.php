<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
<title>Potrazivanja</title>
</head>
<body>
<div class="nosac_sa_tabelom">
	<?php
	require("../include/DbConnection.php");
	require("../include/ConfigFirma.php");
	?>
	<div class='memorandum screen_hide'><?php echo $inkfirma;?></div>
	<div class="cf"></div>
	<h2>Potrazivanja</h2>
	<table>
		<tr>
			<th>Ime Preduzeca</th>
			<th>Saldo</th>
		</tr>
		<?php 
		$upit=mysql_query("SELECT sif_kup, naziv_kup, stanje FROM dob_kup");
		$arraysaldo=0;
		while($niz = mysql_fetch_array($upit)){
			$sif_kup=$niz['sif_kup'];
			$partner=$niz['naziv_kup'];
			$stanje_part=$niz['stanje'];
			$upit2 = mysql_query("
			SELECT SUM(izlaz_novca) AS bank_izlaz_novca, SUM(ulaz_novca) AS bank_ulaz_novca
			FROM bankaupis WHERE sifra_par ='$sif_kup'");
			$upit3 = mysql_query("
			SELECT SUM(izzad) AS dost_iznos 
			FROM dosta WHERE sifra_fir='$sif_kup'");
			$upit4 = mysql_query("
			SELECT SUM(nabav_vre) AS kalk_iznos 
			FROM kalk WHERE sif_firme='$sif_kup'");
			$upit5 = mysql_query("
			SELECT SUM(iznosus) AS usluge_iznos
			FROM usluge WHERE sifusluge='$sif_kup'");
			$upit6 = mysql_query("
			SELECT SUM(iznos_f) AS pismo_fak, SUM(iznos_k) AS pismo_kalk
			FROM k_pism_r WHERE sif_firme='$sif_kup'");

			$niz2 = mysql_fetch_array($upit2);
			$niz3 = mysql_fetch_array($upit3);
			$niz4 = mysql_fetch_array($upit4);
			$niz5 = mysql_fetch_array($upit5);
			$niz6 = mysql_fetch_array($upit6);

			$saldo=$stanje_part+(($niz3['dost_iznos'])-($niz2['bank_ulaz_novca'])-($niz4['kalk_iznos'])+($niz2['bank_izlaz_novca'])-($niz5['usluge_iznos'])-($niz6['pismo_fak'])+($niz6['pismo_kalk']));
			if ($saldo<>0){
				?>
				<tr>
					<td><?php echo $partner;?></td>
					<td><?php echo $saldo;?></td>
				</tr>
				<?php
				$arraysaldo+=$saldo;
			}
		} ?>
		<tr>
			<td>Ukupno: </td>
			<td>
				<?php echo number_format($arraysaldo, 2,".", " ");?>
			</td>
		</tr>
	</table> 
	<div class="cf"></div>
	<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
	<button class="dugme_plavo" onClick='window.print()' type='button'>Stampa</button>
	<div class="cf"></div>
</div>
</body>