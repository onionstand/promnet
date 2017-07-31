<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<title>Robna kartica</title>
	</head>
	<body>
	<div class="nosac_sa_tabelom">
	<?php require("../include/DbConnection.php");$srob=$_POST['sifra_robe'];?>
	<h2>Robna kartica</h2>
	<?php
	$upit3 = mysql_query("SELECT naziv_robe, sifra, cena_robe, stanje, poc_stanje FROM roba WHERE sifra='$srob' ");
	$niz3= mysql_fetch_array($upit3);
	$poc_stanje=$niz3['poc_stanje'];
	echo "<p>";
	echo "Ime robe: " . $niz3['naziv_robe'] . "<br>Sifra: " . $niz3['sifra'] . "<br>";
	echo "Cena robe: " . $niz3['cena_robe'] . "<br>";
	echo "Stanje: " . $niz3['stanje'];
	echo "</p>";
	?>
	<!--kalk-->
	<table>
	<tr>
		<th>Dokument</th>
		<th>Broj dok.</th>
		<th>Kolicina</th>
		<th>Datum</th>
		<th>Partner</th>
	</tr>
	<tr>
		<td>Pocetno stanje</td>
		<td></td>
		<td><?php echo $poc_stanje;?></td>
		<td></td>
		<td></td>
	</tr>
	<?php 
	$upit = mysql_query("SELECT ulaz.br_kal as a1, ulaz.kol_kalk as a2, kalk.datum as a3, dob_kup.naziv_kup as a4,'Kalkulacija' as a5 FROM ulaz  
						RIGHT JOIN kalk ON ulaz.br_kal=kalk.broj_kalk 
						LEFT JOIN dob_kup ON kalk.sif_firme=dob_kup.sif_kup 
						WHERE srob_kal='$srob' 
						UNION ALL
						SELECT izlaz.br_dos as a1, izlaz.koli_dos as a2, dosta.datum_d as a3, dob_kup.naziv_kup as a4,'Faktura' as a5 FROM izlaz  
						RIGHT JOIN dosta ON izlaz.br_dos=dosta.broj_dost 
						LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup 
						WHERE srob_dos='$srob'
						UNION ALL
						SELECT niv_robe.br_niv as a1, niv_robe.koli_niv as a2, nivel.datum_niv as a3, niv_robe.srob_niv as a4 ,'Nivelacija-' as a5 FROM niv_robe  
						RIGHT JOIN nivel ON niv_robe.br_niv=nivel.broj_niv 
						WHERE srob='$srob'
						UNION ALL
						SELECT niv_robe.br_niv as a1, niv_robe.koli_niv as a2, nivel.datum_niv as a3, niv_robe.srob as a4 ,'Nivelacija+' as a5 FROM niv_robe  
						RIGHT JOIN nivel ON niv_robe.br_niv=nivel.broj_niv 
						WHERE srob_niv='$srob'
						UNION ALL
						SELECT k_pism_tr.broj_p as a1, k_pism_tr.kolic_p as a2, k_pism_r.dat_k as a3, k_pism_r.partner as a4,'Knjizno pismo kalk.' as a5 FROM k_pism_tr  
						RIGHT JOIN k_pism_r ON k_pism_tr.broj_p=k_pism_r.broj_k 
						WHERE sif_rob_p='$srob' AND kod_p=1
						UNION ALL
						SELECT k_pism_tr.broj_p as a1, k_pism_tr.kolic_p as a2, k_pism_r.dat_k as a3, k_pism_r.partner as a4,'Knjizno pismo fak.' as a5 FROM k_pism_tr  
						RIGHT JOIN k_pism_r ON k_pism_tr.broj_p=k_pism_r.broj_k 
						WHERE sif_rob_p='$srob' AND kod_p=2
						ORDER BY a3 
						");

	while($niz = mysql_fetch_array($upit))
	{
		?>
		<tr>
			<td>
				<?php
					if ($niz['a5']=="Kalkulacija") { ?>
					 	<form action="../kalk/kalk_nov6.php" method="post" target="_blank">
							<input type="hidden" name="broj_kalkulaci" value="<?php echo $niz['a1'];?>"/>
							<input type="submit" title="<?php echo $niz['a5'];?>" value="<?php echo $niz['a5'];?>"/>
						</form>
					 <?php }
					 elseif ($niz['a5']=="Faktura") { ?>
					 	<form action="../fak/faktura.php" method="post" target="_blank">
							<input type="hidden" name="broj_fak_stampa" value="<?php echo $niz['a1'];?>"/>
							<input type="submit" title="<?php echo $niz['a5'];?>" value="<?php echo $niz['a5'];?>"/>
						</form>
					 <?php }
					 else echo $niz['a5'];
				?>
			</td>
			<td><?php echo $niz['a1'];?></td>
			<td><?php echo $niz['a2'];?></td>
			<td><?php echo $niz['a3'];?></td>
			<td><?php echo $niz['a4'];?></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td colspan='2'>Ukupno:</td>
			<?php
			$upit3 = mysql_query("SELECT SUM(kol_kalk) AS ukupno FROM ulaz WHERE srob_kal='$srob'");
			$niz3 = mysql_fetch_array($upit3);
			$kalkrob=$niz3['ukupno'];

			$upit4 = mysql_query("SELECT SUM(koli_dos) AS ukupno FROM izlaz WHERE srob_dos='$srob'");
			$niz4 = mysql_fetch_array($upit4);
			$dosrob=$niz4['ukupno'];

			$upit5 = mysql_query("SELECT SUM(koli_niv) AS ukupno FROM niv_robe WHERE srob='$srob'");
			$niz5 = mysql_fetch_array($upit5);
			$nivelarobm=$niz5['ukupno'];

			$upit6 = mysql_query("SELECT SUM(koli_niv) AS ukupno FROM niv_robe WHERE srob_niv='$srob'");
			$niz6 = mysql_fetch_array($upit6);
			$nivelarobp=$niz6['ukupno'];

			$upit7 = mysql_query("SELECT SUM(kolic_p) AS ukupno FROM k_pism_tr 
			RIGHT JOIN k_pism_r ON k_pism_tr.broj_p=k_pism_r.broj_k 
			WHERE sif_rob_p='$srob' AND kod_p=1");
			$niz7 = mysql_fetch_array($upit7);
			$k_p_k=$niz7['ukupno'];

			$upit8 = mysql_query("SELECT SUM(kolic_p) AS ukupno FROM k_pism_tr 
			RIGHT JOIN k_pism_r ON k_pism_tr.broj_p=k_pism_r.broj_k 
			WHERE sif_rob_p='$srob' AND kod_p=2");
			$niz8 = mysql_fetch_array($upit8);
			$k_p_d=$niz8['ukupno'];

			$izracunato=$poc_stanje+$kalkrob-$dosrob-$nivelarobm+$nivelarobp-$k_p_k+$k_p_d;
			?>
			<td><strong><?php echo $izracunato;?></strong></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<a href="pretra_rob.php" class="dugme_crveno_92plus4 print_hide">Vrati se nazad</a>
	<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
	<button class="dugme_plavo print_hide" onClick='window.print()' type='button'>Stampa</button>
	<div class="cf"></div>
	</div>
	</body>
</html>