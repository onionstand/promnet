<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<title>Analiticka kartica</title>
</head>
<body>
<div class="nosac_sa_tabelom">
<?php require("../include/DbConnection.php");require("../include/ConfigFirma.php");$sif_kup=$_POST['partnersif'];

$upit3 = mysql_query("SELECT * FROM dob_kup WHERE sif_kup='$sif_kup' ");
$niz3= mysql_fetch_array($upit3);
$stanje_part=$niz3['stanje'];
?>
	<div class='memorandum screen_hide'><?php echo $inkfirma;?></div>
	<div class="cf"></div>
	<h2 style="text-align:center;">Analiticka kartica na dan <?php echo date("d.m.Y.");?></h2>
	<p>
		Partner: <?php echo $niz3['naziv_kup'];?><br/>
		Sifra: <?php echo $sif_kup;?><br/>
		<?php echo $niz3['mesto_kup'];?><br/>
		<?php echo $niz3['ulica_kup'];?><br/>
	</p>
<div class="cf"></div>
	<table>
		<tr>
			<th>Br. dok.</th>
			<th>Vrsta</th>
			<th>Datum</th>
			<th>Duguje</th>
			<th>Potrazuje</th>
			<th>Saldo</th>
			<th>Poziv</th>
		</tr>
		<tr>
			<td>Pocetno s.</td>
			<td></td>
			<td></td>
			<td>
				<?php
				if ($stanje_part>0){
					echo number_format($stanje_part, 2,".",",");
					$zbir_a4=$stanje_part;
				}
				else {
					$zbir_a4=0;
				}
				?>
			</td>
			<td>
				<?php
				if ($stanje_part<0){
					echo number_format(($stanje_part*-1), 2,".",",");
					$zbir_a5=$stanje_part*-1;
				}
				else {
					$zbir_a5=0;
				}
				?>
			</td>
			<td><?php echo number_format($stanje_part, 2,".",",");?></td>
			<td></td>
		</tr>
		<?php 

		$saldo=$stanje_part;
		$upit=mysql_query(
		"SELECT bankaupis.br_izvoda AS a1, 'UPL' AS a2, bankaupis.datum_izv AS a3, bankaupis.izlaz_novca AS a4, 0 AS a5, bankaupis.broj_dok AS a6
		FROM bankaupis
		RIGHT JOIN banke ON bankaupis.banka=banke.id_banke 
		WHERE sifra_par ='$sif_kup'
		UNION ALL
		SELECT broj_kalk AS a1,'KAL' AS a2,datum AS a3,0 AS a4, nabav_vre AS a5, 'X' AS a6 
		FROM kalk 
		WHERE sif_firme='$sif_kup'
		UNION ALL
		SELECT br_usluge AS a1,'USL' AS a2,datum AS a3,0 AS a4, iznosus AS a5, 'X' AS a6 
		FROM usluge 
		WHERE sifusluge='$sif_kup'
		UNION ALL
		SELECT broj_k AS a1,'PIS K' AS a2,dat_k AS a3,iznos_k AS a4, 0 AS a5, 'X' AS a6 
		FROM k_pism_r 
		WHERE sif_firme='$sif_kup' AND kod_p=1
		ORDER BY a3 
		");

		while($niz = mysql_fetch_array($upit))
		{
			$dat=$niz['a3'];
			$datumrad=strtotime( $dat );
			$datum_formatiran=date("d.m.Y",$datumrad);

			$saldo+=$niz['a4']-$niz['a5'];
			$zbir_a4+=$niz['a4'];
			$zbir_a5+=$niz['a5'];

			echo "<tr>";
			echo "<td>" . $niz['a1'] . "</td>";
			echo "<td>" . $niz['a2'] . "</td>";
			echo "<td>" .$datum_formatiran . "</td>";
			echo "<td>" . number_format($niz['a4'], 2,".",",") . "</td>";
			echo "<td>" . number_format($niz['a5'], 2,".",",") . "</td>";
			echo "<td>" . number_format($saldo, 2,".",",") . "</td>";
			echo "<td>" . $niz['a6'] . "</td>";
			echo "</tr>";
		}
		?>
		<tr>
			<td></td>
			<td></td>
			<td>Zbir:</td>
			<td><?php echo number_format($zbir_a4, 2,".",",");?></td>
			<td><?php echo number_format($zbir_a5, 2,".",",");?></td>
			<td><?php echo number_format($saldo, 2,".",",");?></td>
		</tr>
	</table>
<div class="cf"></div>
<a href="kartica0.php" class="dugme_crveno_92plus4 print_hide">Nazad</a>
<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
<div class="cf"></div>
</div>
</body>