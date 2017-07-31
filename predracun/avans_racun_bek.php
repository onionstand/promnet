<?php
require("../include/DbConnection.php");
function OsnovicaZaPdv($tarifa_osnovice,$datumzaporez,$brojfak){
	$pdv_na_osnovicu_upit = mysql_query("SELECT SUM(((koli_profak*((cena_profak/100)*(100-rab_dos))/100)*(100+
					(SELECT porez_procenat FROM poreske_stope
					WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
					AND tarifa_stope = porez
					AND porez_datum <= '$datumzaporez')
				))-(koli_profak*((cena_profak/100)*(100-rab_dos)))) AS ukupporez,
			SUM(koli_profak*((cena_profak/100)*(100-rab_dos))) AS osnovica_za_osnovicu,
			porez AS porez_za_osnovicu
			FROM profakrob
			WHERE br_profak='$brojfak'
			AND porez='$tarifa_osnovice'");
	$pdv_na_osnovicu_red = (mysql_fetch_array($pdv_na_osnovicu_upit));
	IF ($pdv_na_osnovicu_red['ukupporez']>0){
		?>
		<tr>
			<td style="border:none;">
				<?php
				$upit_za_procenat_osnovice=mysql_query("SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) 
							FROM poreske_stope WHERE tarifa_stope = ". $pdv_na_osnovicu_red['porez_za_osnovicu'].")
							AND tarifa_stope = ". $pdv_na_osnovicu_red['porez_za_osnovicu']."
							AND porez_datum <= '$datumzaporez'");
				$red_za_procenat_osnovice = (mysql_fetch_array($upit_za_procenat_osnovice));
				echo $red_za_procenat_osnovice['porez_procenat'];?>% PDV na osnovicu <?php echo number_format($pdv_na_osnovicu_red['osnovica_za_osnovicu'], 2,".",",");?> :
			</td>
			<td>
				<?php echo number_format($pdv_na_osnovicu_red['ukupporez'], 2,".",",");?>
			</td>
		</tr>
	<?php 
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
		<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#dodaj_robu").focus();
			});
		</script>
		<title>Faktura</title>
	</head> 
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			$brojfak=$_GET['broj_profak'];

			$siffirme_upit = mysql_query("SELECT sifra_fir FROM profak
			WHERE broj_prof='$brojfak'");
			while($sifrafirme_red = mysql_fetch_array($siffirme_upit))
			  {
			  $siffirme=$sifrafirme_red['sifra_fir'];
			  }

			$datkal_upit = mysql_query ("SELECT datum_prof, date_format(datum_prof, '%d. %m. %Y.') as formatted_date, rok FROM profak WHERE broj_prof=$brojfak ");
			$datkal_red= mysql_fetch_array ($datkal_upit);
			$datumdos=$datkal_red['formatted_date'];
			$datumzaporez= $datkal_red['datum_prof'];
			$rokpl=$datkal_red['rok'];

			$dob_kup_upit = mysql_query("SELECT * FROM dob_kup
			WHERE sif_kup='$siffirme'");

			while($dob_kup_red = mysql_fetch_array($dob_kup_upit))
			  {
			  $kupac=$dob_kup_red['naziv_kup'];
			  $ulica_kup=$dob_kup_red['ulica_kup'];
			  $post_br=$dob_kup_red['postbr'];
			  $mesto_kup=$dob_kup_red['mesto_kup'];
			  $pib=$dob_kup_red['pib'];
			  $mat_br=$dob_kup_red['mat_br'];
			  }

			include("../include/ConfigFirma.php");
			?>
			<div class="memorandum screen_hide">
			<?php echo $inkfirma;?>
			</div>
			<div class="nosac_zaglavlja_fakture screen_hide">
				<div class="zaglavlje_fakture_desni">
					AVANSNI RAČUN BR. <b><?php echo $brojfak;?></b>
					<br />
					Mesto i datum izdavanja: <b><?php echo $inkfirma_mir . ", " . $datumdos;?></b>
				</div>
				<div class="zaglavlje_fakture_levi">
					Kupac:
					<br />
					<b><?php echo $kupac;?></b>
					<br />
					<b><?php echo $ulica_kup;?></b>
					<br />
					<b><?php echo $post_br . " " . $mesto_kup;?></b>
					<br />
					PIB <b><?php echo $pib;?></b>
					<br />
					MAT.BR. <b><?php echo $mat_br;?></b>
				</div>
			</div>
			<p class="print_hide">
				Broj avansnog računa: <?php echo $brojfak;?><br>
				Kupac: <?php echo $kupac;?><br>
				Datum: <?php echo $datumdos;?>
			</p>
			<div class="cf"></div>
			<table class="sirina100">
			<tr>
				<th>Opis</th>
				<th>Ukupno</th>
				<th class="print_hide"></th>
			</tr>
			<?php
			$i=1;
			$prikaz_roba_upit = mysql_query("SELECT id_rob, br_profak, koli_profak, cena_profak, rab_dos,
									cena_profak*koli_profak AS ukupdos, naziv_robe, porez,
									
									(SELECT porez_procenat FROM poreske_stope
										WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
										AND tarifa_stope = porez
										AND porez_datum <= '$datumzaporez') AS robaporez,

									(((((cena_profak/100)*(100-rab_dos))/100)*(100+
									
									(SELECT porez_procenat FROM poreske_stope
										WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
										AND tarifa_stope = porez
										AND porez_datum <= '$datumzaporez')
									
									))*koli_profak) AS ukupdospor, jed_mere 
									FROM profakrob WHERE br_profak='$brojfak'");
			while($prikaz_roba_red = mysql_fetch_array($prikaz_roba_upit)) { ?>
				<tr>
					<td style="text-align:left;"><?php echo $prikaz_roba_red['naziv_robe'];?></td>
					<td><?php echo number_format($prikaz_roba_red['ukupdos'], 2,".",",");?></td>
					<td class="print_hide">
						<form action='profak_brisi2.php' method='post'>
							<input type='hidden' name='broj_fak' value='<?php echo $brojfak;?>'/>
							<input type='hidden' name='id_rob' value='<?php echo $prikaz_roba_red['id_rob'];?>'/>
							<input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
						</form>
					</td>
				</tr>
			<?php }


			IF ($provera_rabata_red['ukuprab'] != 0){
				?>
				</table>
				<h1>Rabat mora biti 0.</h1>
				<?php
			}
			ELSE {
				?>
				<tr>
					<td style="border:none;">Osnovica:</td>
					<td>
						<?php $zbir_upit = mysql_query("SELECT SUM(cena_profak*koli_profak) AS ukupiznul FROM profakrob WHERE br_profak='$brojfak'");
						$zbir_red=(mysql_fetch_array($zbir_upit));
						echo number_format ($zbir_red['ukupiznul'], 2,".",",");?>
					</td>
					
				</tr>
				<?php 
					//osnovica 10
					OsnovicaZaPdv(10,$datumzaporez,$brojfak);
					//osnovica 20
					OsnovicaZaPdv(20,$datumzaporez,$brojfak);
				?>
				<tr>
					<td style="border:none;">Ukupan PDV:</td>
					<td><?php $ukupan_pdv_upit = mysql_query("SELECT SUM(((koli_profak*((cena_profak/100)*(100-rab_dos))/100)*(100+
								(SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
								AND tarifa_stope = porez
								AND porez_datum <= '$datumzaporez')
							))-(koli_profak*((cena_profak/100)*(100-rab_dos)))) 
							AS ukupporez FROM profakrob WHERE br_profak='$brojfak'");
						$ukupan_pdv_red = (mysql_fetch_array($ukupan_pdv_upit));
						echo number_format($ukupan_pdv_red['ukupporez'], 2,".",",");?>
					</td>
				</tr>
				<tr>
					<td style="border:none;">Ukupna vrednost sa PDV-om:</td>
					<td>
						<b><?php echo number_format((($zbir_red['ukupiznul'])-($provera_rabata_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",",");?></b>
					</td>
				</tr>
			<?php
			}
			?>
			</table> 
			<div class="cf"></div>
			<form action="profak3.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak; ?>"/>
				<button type='submit' class='dugme_zeleno print_hide' id='dodaj_robu'>Dodaj robu</button>
			</form>
				<form action="profak5.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak; ?>"/>
				<button type="submit" class="dugme_plavo print_hide">Pregled Predračun</button>
			</form>
			<form action="profak6.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak; ?>"/>
				<input type="hidden" name="izzad" value="<?php echo number_format((($zbir_red['ukupiznul'])-($provera_rabata_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",""); ?>"/>
				<input type="hidden" name="ispor" value="<?php echo number_format($ukupan_pdv_red['ukupporez'], 2,".",""); ?>"/>
				<input type="hidden" name="odo_rab" value="<?php echo number_format($provera_rabata_red['ukuprab'], 2,".",""); ?>"/>
				<button type='submit' class='dugme_plavo print_hide'>Zavrsi</button>
			</form>
			<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
			<div class="cf"></div>
			<p class="screen_hide" style="font-size:12px;">
				<?php include("../include/ConfigFirma.php");
				echo $inkfaktekst;?>
			</p>
			<div id="potpis0">
				<div class="potpis1">
					<p>Izdao</p>
				</div>
			</div>
		</div>
	</body>
</html>