<!DOCTYPE html>
<head>
	<meta charset="utf-8">
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
		$partner_upit=mysql_query("SELECT sif_kup, naziv_kup, stanje FROM dob_kup");
		$arraysaldo=0;
		while($partner_niz = mysql_fetch_array($partner_upit)){
			$sif_kup=$partner_niz['sif_kup'];
			$partner=$partner_niz['naziv_kup'];
			$stanje_part=$partner_niz['stanje'];

			$banka_upit = mysql_query("
			SELECT SUM(izlaz_novca) AS bank_izlaz_novca, SUM(ulaz_novca) AS bank_ulaz_novca
			FROM bankaupis WHERE sifra_par ='$sif_kup'");

			$dostavnice_upit = mysql_query("
			SELECT SUM(izzad) AS dost_iznos 
			FROM dosta WHERE sifra_fir='$sif_kup'");

			$kalk_upit = mysql_query("
			SELECT SUM(nabav_vre) AS kalk_iznos 
			FROM kalk WHERE sif_firme='$sif_kup'");

			$usluge_upit = mysql_query("
			SELECT SUM(iznosus) AS usluge_iznos
			FROM usluge WHERE sifusluge='$sif_kup'");

			$knjiz_pis_r_upit = mysql_query("
			SELECT SUM(iznos_f) AS pismo_fak, SUM(iznos_k) AS pismo_kalk
			FROM k_pism_r WHERE sif_firme='$sif_kup'");

			$banka_niz = mysql_fetch_array($banka_upit);
			$dostavnice_niz = mysql_fetch_array($dostavnice_upit);
			$kalk_niz = mysql_fetch_array($kalk_upit);
			$usluge_niz = mysql_fetch_array($usluge_upit);
			$knjiz_pis_r_niz = mysql_fetch_array($knjiz_pis_r_upit);

			$saldo=$stanje_part+(($dostavnice_niz['dost_iznos'])-($banka_niz['bank_ulaz_novca'])-($kalk_niz['kalk_iznos'])+($banka_niz['bank_izlaz_novca'])-($usluge_niz['usluge_iznos'])-($knjiz_pis_r_niz['pismo_fak'])+($knjiz_pis_r_niz['pismo_kalk']));
			if ($saldo<>0){
				?>
				<tr>
					<td><?php echo $partner;?></td>
					<td><?php echo number_format($saldo, 2,".",",");?></td>
				</tr>
				<?php
				$arraysaldo+=$saldo;
			}
		} ?>
		<tr>
			<td>Ukupno: </td>
			<td>
				<?php echo number_format($arraysaldo, 2,".", ",");?>
			</td>
		</tr>
	</table> 
	<div class="cf"></div>
	<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
	<button class="dugme_plavo" onClick='window.print()' type='button'>Stampa</button>
	<div class="cf"></div>
</div>
</body>