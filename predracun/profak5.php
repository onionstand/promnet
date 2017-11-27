<?php
require("../include/DbConnectionPDO.php");
function OsnovicaZaPdv($tarifa_osnovice,$datumzaporez,$brojfak){
	global $baza_pdo;

	$pdv_na_osnovicu_upit = "SELECT SUM(((koli_profak*((cena_profak/100)*(100-rab_dos))/100)*(100+
					(SELECT porez_procenat FROM poreske_stope
					WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
					AND tarifa_stope = porez
					AND porez_datum <= '$datumzaporez')
				))-(koli_profak*((cena_profak/100)*(100-rab_dos)))) AS ukupporez,
			SUM(koli_profak*((cena_profak/100)*(100-rab_dos))) AS osnovica_za_osnovicu,
			porez AS porez_za_osnovicu
			FROM profakrob
			WHERE br_profak='$brojfak'
			AND porez='$tarifa_osnovice'";

	$pdv_na_osnovicu_result = $baza_pdo->query($pdv_na_osnovicu_upit);
	$pdv_na_osnovicu_red = $pdv_na_osnovicu_result->fetch();

	IF ($pdv_na_osnovicu_red['ukupporez']>0){
		?>
		<tr>
			<td colspan='3' style="border:none;">
				<?php
				$upit_za_procenat_osnovice = "SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) 
							FROM poreske_stope WHERE tarifa_stope = ". $pdv_na_osnovicu_red['porez_za_osnovicu'].")
							AND tarifa_stope = ". $pdv_na_osnovicu_red['porez_za_osnovicu']."
							AND porez_datum <= '$datumzaporez'";
				$red_za_procenat_result = $baza_pdo->query($upit_za_procenat_osnovice);
				$red_za_procenat_osnovice = $red_za_procenat_result->fetch();
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
		<title>Predračun</title>
	</head> 
	<body>
		<div class="nosac_sa_tabelom">
			<?php
			$brojfak=$_POST['broj_profak'];

			$sifrafirme_sql = "SELECT sifra_fir FROM profak WHERE broj_prof='$brojfak'";
			$sifrafirme_result = $baza_pdo->query($sifrafirme_sql);
			$sifrafirme_red = $sifrafirme_result->fetch();
			$siffirme=$sifrafirme_red['sifra_fir'];


			$datkal_upit = "SELECT broj_prof, datum_prof, date_format(datum_prof, '%d. %m. %Y.') as formatted_date, rok, napomena FROM profak WHERE broj_prof=$brojfak ";
			$datkal_result = $baza_pdo->query($datkal_upit);
			$datkal_red = $datkal_result->fetch();
			$datumdos=$datkal_red['formatted_date'];
			$datumzaporez= $datkal_red['datum_prof'];
			$rokpl=$datkal_red['rok'];
			$napomena=$datkal_red['napomena'];

			$dob_kup_upit = "SELECT * FROM dob_kup WHERE sif_kup='$siffirme'";
			foreach ($baza_pdo->query($dob_kup_upit) as $dob_kup_red) {
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
				<div class="zaglavlje_fakture_levi">
					<span style="display: inline-block; margin-bottom:6px;">Kupac:</span><br>
					<b><span style="font-size: 11px;"><?php echo $kupac;?></span></b>
					<br><?php echo $ulica_kup;?>
					<br><?php echo $post_br ." ". $mesto_kup;?>
					<br>PIB <?php echo $pib;?>
					<br>MB <?php echo $mat_br;?>
				</div>
				<div class="zaglavlje_fakture_desni"><span style="font-size: 11px; display: inline-block; margin-bottom:6px;"><b>PREDRAČUN BR. <?php echo $brojfak."/".$trengodina;?></b></span>
					<br>Mesto izdavanja predračuna: <b><?php echo $inkfirma_mir;?></b>
					<br>Datum izdavanja predračuna: <b><?php echo $datumdos;?></b>
				</div>
			</div>
			
			<p class="print_hide">
				Broj profakture: <?php echo $brojfak;?><br>
				Kupac: <?php echo $kupac;?><br>
				Datum: <?php echo $datumdos;?>
			</p>
			<div class="cf"></div>
			<table>
			<tr>
				<th style="font-size:9px;">r.b.</th>
				<th>Naziv</th>
				<th style="font-size:9px;">Jed.mere</th>
				<th style="font-size:9px;">Količina</th>
				<th style="font-size:9px;">Cena</th>
				<th>Iznos</th>
				<?php 

				$provera_rabata_upit = "SELECT SUM(koli_profak*(cena_profak-(cena_profak/100)*(100-rab_dos))) AS ukuprab FROM profakrob WHERE br_profak='$brojfak'";
				$provera_rabata_result = $baza_pdo->query($provera_rabata_upit);
				$provera_rabata_red = $provera_rabata_result->fetch();
				IF ($provera_rabata_red['ukuprab'] != 0) {echo "
				<th>Rabat</th>";}
				?>
				<th>PDV</th>
				<!--<th>Iznos+PDV</th>-->
			</tr>
			<?php
			$i=1;


			$prikaz_roba_upit = "SELECT id_rob, br_profak, koli_profak, cena_profak, rab_dos,
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
									FROM profakrob WHERE br_profak='$brojfak'";
			foreach ($baza_pdo->query($prikaz_roba_upit) as $prikaz_roba_red) { ?>
				<tr>
					<td style="text-align:center;"><?php echo $i++;?></td>
					<td style="text-align:left;"><?php echo $prikaz_roba_red['naziv_robe'];?></td>
					<td style="text-align:center;"><?php echo $prikaz_roba_red['jed_mere'];?></td>
					<td><?php echo $prikaz_roba_red['koli_profak'];?></td>
					<td><?php echo number_format($prikaz_roba_red['cena_profak'], 2,".",",");?></td>
					<td><?php echo number_format($prikaz_roba_red['ukupdos'], 2,".",",");?></td>
					<?php IF ($provera_rabata_red['ukuprab'] != 0) {?>
						<td><?php echo number_format($prikaz_roba_red['rab_dos'], 0,".",",");?>%</td>
						<?php } ?>
					<td><?php echo $prikaz_roba_red['robaporez'];?></td>
					<!--<td>--><?php //echo number_format($prikaz_roba_red['ukupdospor'], 2,".","");?><!--</td>-->
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
				<tr>
					<td rowspan='7' colspan='2' style='border:none;'></td>
					<td colspan='3' style="border:none;">Zbir:</td>
					<td>
						<?php
						$zbir_upit = "SELECT SUM(cena_profak*koli_profak) AS ukupiznul FROM profakrob WHERE br_profak='$brojfak'";
						$zbir_result = $baza_pdo->query($zbir_upit);
						$zbir_red = $zbir_result->fetch();
						echo number_format ($zbir_red['ukupiznul'], 2,".",",");
						?>
					</td>
					
				</tr>
				<tr>
					<td colspan='3' style="border:none;">Iznos odobrenog rabata:</td>
					<td><?php echo number_format($provera_rabata_red['ukuprab'], 2,".",",");?></td>
				</tr>
				<tr>
					<td colspan="3" style="border:none;">Zbir-rabat:</td>
					<td><?php echo number_format((($zbir_red['ukupiznul'])-($provera_rabata_red['ukuprab'])), 2,".",",");?></td>
				</tr>
				<?php 
					//osnovica 10
					OsnovicaZaPdv(10,$datumzaporez,$brojfak);
					//osnovica 20
					OsnovicaZaPdv(20,$datumzaporez,$brojfak);
				?>
				<tr>
					<td colspan='3' style="border:none;">Ukupan PDV:</td>
					<td>
						<?php
						$ukupan_pdv_upit = "SELECT SUM(((koli_profak*((cena_profak/100)*(100-rab_dos))/100)*(100+
						(SELECT porez_procenat FROM poreske_stope
											WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
											AND tarifa_stope = porez
											AND porez_datum <= '$datumzaporez')
						))-(koli_profak*((cena_profak/100)*(100-rab_dos)))) 
						AS ukupporez FROM profakrob WHERE br_profak='$brojfak'";
						$ukupan_pdv_result = $baza_pdo->query($ukupan_pdv_upit);
						$ukupan_pdv_red = $ukupan_pdv_result->fetch();
						echo number_format($ukupan_pdv_red['ukupporez'], 2,".",",");?>
					</td>
				</tr>
				<tr>
					<td colspan='3' style="border:none;">Ukupna vrednost sa PDV-om:</td>
					<td><b><?php echo number_format((($zbir_red['ukupiznul'])-($provera_rabata_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",",");?></b></td>
				</tr>
				<?php
			}
			ELSE {
				?>
				<tr>
					<td rowspan='7' colspan='2' style="border:none;"></td>
					<td colspan='3' style="border:none;">Zbir:</td>
					<td>
						<?php
						$zbir_upit = "SELECT SUM(cena_profak*koli_profak) AS ukupiznul FROM profakrob WHERE br_profak='$brojfak'";
						$zbir_result = $baza_pdo->query($zbir_upit);
						$zbir_red = $zbir_result->fetch();
						echo number_format ($zbir_red['ukupiznul'], 2,".",",");
						?>
					</td>
					
				</tr>
				<?php 
					//osnovica 10
					OsnovicaZaPdv(10,$datumzaporez,$brojfak);
					//osnovica 20
					OsnovicaZaPdv(20,$datumzaporez,$brojfak);
				?>
				<tr>
					<td colspan='3' style="border:none;">Ukupan PDV:</td>
					<td><?php
						$ukupan_pdv_upit = "SELECT SUM(((koli_profak*((cena_profak/100)*(100-rab_dos))/100)*(100+
								(SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = porez)
								AND tarifa_stope = porez
								AND porez_datum <= '$datumzaporez')
							))-(koli_profak*((cena_profak/100)*(100-rab_dos)))) 
							AS ukupporez FROM profakrob WHERE br_profak='$brojfak'";
						$ukupan_pdv_result = $baza_pdo->query($ukupan_pdv_upit);
						$ukupan_pdv_red = $ukupan_pdv_result->fetch();
						echo number_format($ukupan_pdv_red['ukupporez'], 2,".",",");?>
					</td>
				</tr>
				<tr>
					<td colspan='3' style="border:none;">Ukupna vrednost sa PDV-om:</td>
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
				<input type="hidden" name="id" value="<?php echo $prikaz_roba_red['id_rob']; ?>"/>
				<button type='submit' class='dugme_zeleno print_hide' id='dodaj_robu'>Dodaj robu</button>
			</form>
			<a href="avans_racun.php?broj_profak=<?php echo $brojfak;?>" class="dugme_plavo_92plus4 print_hide">Pregled Avansni račun</a>
			<a href="promeni_partnera_prof.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_92plus4 print_hide">Promeni partnera ili datum</a>
			<a href="promeni_napomenu_prof.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_92plus4 print_hide">Izmeni napomenu</a>
			<a href="napravi_racun.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_92plus4 print_hide">Napravi racun</a>
			<form action="profak6.php" method="post">
				<input type="hidden" name="broj_profak" value="<?php echo $brojfak; ?>"/>
				<input type="hidden" name="izzad" value="<?php echo number_format((($zbir_red['ukupiznul'])-($provera_rabata_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",""); ?>"/>
				<input type="hidden" name="ispor" value="<?php echo number_format($ukupan_pdv_red['ukupporez'], 2,".",""); ?>"/>
				<input type="hidden" name="odo_rab" value="<?php echo number_format($provera_rabata_red['ukuprab'], 2,".",""); ?>"/>
				<button type='submit' class='dugme_plavo print_hide'>Zavrsi</button>
			</form>
			<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
			<div class="cf"></div>
			<p class="screen_hide" style="font-size:11px;">
				<?php 
				echo $napomena;
				?>
			</p>
			<div id="potpis0">
				<div class="potpis1">
					<p>Izdao i Fakturisao</p>
				</div>
			</div>
		</div>
	</body>
</html>