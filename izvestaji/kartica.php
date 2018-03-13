<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">

	<!-- Export Table -->
	<link rel="stylesheet" type="text/css" href="../include/table_export/tableexport.css">
	<script type="text/javascript" src="../include/table_export/FileSaver.min.js"></script>
	<script type="text/javascript" src="../include/table_export/tableexport.min.js"></script>
	

	<title>Analiticka kartica</title>
</head>
<body>
<div class="nosac_sa_tabelom">
<?php require("../include/DbConnection.php");
require("../include/ConfigFirma.php");
$sif_kup=$_POST['partnersif'];

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
	<table id="default-table">
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
		"SELECT bankaupis.br_izvoda AS a1, 'UPL' AS a2, bankaupis.datum_izv AS a3, bankaupis.izlaz_novca AS a4, bankaupis.ulaz_novca AS a5, bankaupis.broj_dok AS a6, bankaupis.banka AS a7
		FROM bankaupis
		RIGHT JOIN banke ON bankaupis.banka=banke.id_banke 
		WHERE sifra_par ='$sif_kup'
		UNION ALL
		SELECT broj_dost AS a1,'DOS' AS a2,datum_d AS a3,izzad AS a4, 0 AS a5, 'X' AS a6 , 'X' AS a7
		FROM dosta 
		WHERE sifra_fir='$sif_kup'
		UNION ALL
		SELECT broj_kalk AS a1,'KAL' AS a2,datum AS a3,0 AS a4, nabav_vre AS a5, faktura AS a6, 'X' AS a7
		FROM kalk 
		WHERE sif_firme='$sif_kup'
		UNION ALL
		SELECT br_usluge AS a1,'USL' AS a2,datum AS a3,0 AS a4, iznosus AS a5, br_dok_us AS a6, 'X' AS a7
		FROM usluge 
		WHERE sifusluge='$sif_kup'
		UNION ALL
		SELECT broj_k AS a1,'PIS_K' AS a2,dat_k AS a3,iznos_k AS a4, 0 AS a5, 'X' AS a6, 'X' AS a7
		FROM k_pism_r 
		WHERE sif_firme='$sif_kup' AND kod_p=1
		UNION ALL
		SELECT broj_k AS a1,'PIS_F' AS a2,dat_k AS a3,0 AS a4, iznos_f AS a5, 'X' AS a6, 'X' AS a7
		FROM k_pism_r 
		WHERE sif_firme='$sif_kup' AND kod_p=2
		UNION ALL
		SELECT id AS a1, 'FIN_KP' AS a2, datum AS a3,zbir AS a4, 0 AS a5, propratni_dok AS a6, 'X' AS a7
		FROM k_pism_fin 
		WHERE id_firme='$sif_kup' AND duguje_potr=1
		UNION ALL
		SELECT id AS a1, 'FIN_KP' AS a2, datum AS a3, 0 AS a4, zbir AS a5, propratni_dok AS a6, 'X' AS a7
		FROM k_pism_fin 
		WHERE id_firme='$sif_kup' AND duguje_potr=2
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

			switch ($niz['a2']) {
				case "UPL":
					?>
					<td>
						<form class="print_hide" action="../banka/izvod5.php" method="post">
							<input type="hidden" name="broj_izvoda" value="<?php echo $niz['a1'];?>"/>
							<input type="hidden" name="id_banke" value="<?php echo $niz['a7'];?>"/>
							<input type="submit" value="UPL">
						</form>
						<span class="screen_hide">UPL</span>
					</td>
					<?php
					break;

				case "DOS":
					?>
					<td>
						<form class="print_hide" action="../fak/faktura.php" method="post">
							<input type="hidden" name="broj_fak_stampa" value="<?php echo $niz['a1'];?>"/>
							<input type="submit" value="DOS">
						</form>
						<span class="screen_hide">DOS</span>
					</td>
					<?php
					break;
				
				case "KAL":
					?>
					<td>
						<form class="print_hide" action="../kalk/kalk_nov6.php" method="post">
							<input type="hidden" name="broj_kalkulaci" value="<?php echo $niz['a1'];?>"/>
							<input type="submit" value="KAL">
						</form>
						<span class="screen_hide">KAL</span>
					</td>
					<?php
					break;

				case "USL":
					echo "<td>USL</td>";
					break;
				case "PIS_K":
					echo "<td>PIS_K</td>";
					break;
				case "PIS_F":
					echo "<td>PIS_F</td>";
					break;
				case "FIN_KP":
					echo "<td>FIN_KP</td>";
					break;
				default:
				echo "<td>----</td>";
}


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

	<?php //echo mysql_error();?>
<div class="cf"></div>
<a href="kartica0.php" class="dugme_crveno_92plus4 print_hide">Nazad</a>
<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
<div class="cf"></div>
</div>

<script>
	    var DefaultTable = document.getElementById('default-table');
	    new TableExport(DefaultTable, {
	        headers: true,                              // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
	        footers: true,                              // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
	        formats: ['csv', 'txt'],            // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
	        filename: 'kartica',                             // (id, String), filename for the downloaded file, (default: 'id')
	        bootstrap: false,                           // (Boolean), style buttons using bootstrap, (default: false)
	        position: 'top',                         // (top, bottom), position of the caption element relative to table, (default: 'bottom')
	        ignoreRows: null,                           // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
	        ignoreCols: null,                           // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
	        ignoreCSS: '.tableexport-ignore',           // (selector, selector[]), selector(s) to exclude cells from the exported file(s) (default: '.tableexport-ignore')
	        emptyCSS: '.tableexport-empty',             // (selector, selector[]), selector(s) to replace cells with an empty string in the exported file(s) (default: '.tableexport-empty')
	        trimWhitespace: true                        // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: true)
	    });
	</script>
</body>
</html>