
<?php
require("../include/DbConnection.php");
function OsnovicaZaPdv($tarifa_osnovice,$datumzaporez,$brojfak){
	$pdv_na_osnovicu_10 = mysql_query("SELECT izlaz.br_dos, izlaz.srob_dos,
	SUM(((izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos))/100)*(100+
	(SELECT porez_procenat FROM poreske_stope
			WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
			AND tarifa_stope = roba.porez
			AND porez_datum <= '$datumzaporez')
		))-(izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos)))) AS ukupporez,
	SUM(izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos))) AS osnovica_za_osnovicu,
	roba.sifra,
	roba.porez AS porez_za_osnovicu
	FROM izlaz
	RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra
	WHERE br_dos='$brojfak'
	AND roba.porez='$tarifa_osnovice'");
	$row_pdv_na_osnovicu_10 = (mysql_fetch_array($pdv_na_osnovicu_10));
	IF ($row_pdv_na_osnovicu_10['ukupporez']>0){
		?>
		<tr>
			<td colspan='4' style="border:none;">
				<?php
				$upit_za_procenat_osnovice=mysql_query("SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum)
							FROM poreske_stope WHERE tarifa_stope = ". $row_pdv_na_osnovicu_10['porez_za_osnovicu'].")
							AND tarifa_stope = ". $row_pdv_na_osnovicu_10['porez_za_osnovicu']."
							AND porez_datum <= '$datumzaporez'");
				$red_za_procenat_osnovice = (mysql_fetch_array($upit_za_procenat_osnovice));
				echo $red_za_procenat_osnovice['porez_procenat'];?>% PDV na osnovicu <?php echo number_format($row_pdv_na_osnovicu_10['osnovica_za_osnovicu'], 2,".",",");?> :
			</td>
			<td>
				<?php echo number_format($row_pdv_na_osnovicu_10['ukupporez'], 2,".",",");?>
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
	<title>Faktura</title>
	<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../include/jquery/jquery.AddIncSearch.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">
</head>
<body>
<?php

//formiranje dost
if(isset($_POST['partnersif'])) { ?>
	<div class="nosac_glavni_400">
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#fokusiraj").focus();
			});
		</script>
		<?php
		/*zvanje sifre*/
		$upit = "SELECT * FROM dob_kup WHERE sif_kup=".$_POST['partnersif'];
		$result = mysql_query($upit) or die(mysql_error());
		$red = mysql_fetch_array($result) or die(mysql_error());
		$part=$red['sif_kup'];
		$rokpl=$_POST['rok_placanja'];
		
		mysql_query("INSERT INTO dosta (datum_d,sifra_fir, rok, uplaceni_avans, datum_prom)
		VALUES
		(CURDATE(),'$part','$rokpl',0,CURDATE())");
		$brojfak=mysql_insert_id();
		//echo $brojfak;
		$vrsta_dok="F";
		include("../include/ConfigFirma.php");
		$napomena=$inkfaktekst;
		mysql_query("UPDATE dosta SET napomena = '$napomena'
			WHERE broj_dost='$brojfak'");
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#obaveznaf_prtraga").validity(function() {
					$("#fokusiraj").require("Unesi tekst.");
				});
			});
		</script>
		<p>
			Sifra kupca: <?php echo $part;?><br>
			Partner: <?php echo $red['naziv_kup'];?><br>
			Rok placanja: <?php echo $rokpl;?><br>
			Broj fakture: <?php echo $brojfak;?>
		</p>
		<form method='post' id='obaveznaf_prtraga'>
			<label>Trazi proizvod:</label>
			<input type='hidden' name='broj_fak' value='<?php echo $brojfak;?>'/>
			<select name='metode' size='1' class='polje_100'>
				<option value='naziv_robe'>naziv robe</option>
				<option value='sifra'>sifra robe</option>
			</select>
			<input type='text' name='search' class='polje_100_92plus4' id='fokusiraj' style="margin-top:0.3em;">
			<button type='submit' class='dugme_zeleno'>Trazi</button>
		</form>
			<div class="cf"></div>
			
		</div>
<?php
} ?>

<?php
if (isset($_POST['metode'])&& ($_POST['search'])){
	$metode=$_POST['metode'];
	$search=$_POST['search'];
	$brojfak=$_POST['broj_fak'];
	?>
	<div class="nosac_sa_tabelom">
		<table>
			<tr>
				<th>Sifra</th>
				<th>Ime robe</th>
				<th>Cena</th>
				<th>Stanje</th>
				<th>Porez</th>
				<th>Kolicina</th>
				<th>Rabat</th>
			</tr>
			<?php
			$query = mysql_query("SELECT * FROM roba WHERE $metode LIKE '%$search%' ");
			while ($row = mysql_fetch_array($query))
			{
			$ime=$row["naziv_robe"];
			$sifra=$row["sifra"];
			$cena=$row["cena_robe"];
			$stanje=$row["stanje"];
			$porez=$row["porez"];
			?>
			<tr>
				<td><?php echo $sifra;?></td>
				<td><?php echo $ime;?></td>
				<td><?php echo $cena;?></td>
				<td><?php echo $stanje;?></td>
				<td><?php echo $porez;?></td>
				<td>
					<form method='post'>
					<input type='hidden' name='broj_fak_pretraga' value='<?php echo $brojfak;?>'/>
					<input type='hidden' name='sifra_r' value='<?php echo $sifra;?>'/>
					<input type='hidden' name='cena_r' value='<?php echo $cena;?>'/>
					<input type='text' name='fak_kol'  size='4'/>
				</td>
				<td>
					<input type='text' name='fak_rab' size='4'/>
				</td>
				<td>
					<input type='image' src='../include/images/plus.png' alt='Odaberi' />
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
		<div class="cf"></div>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#obaveznaf_prtraga").validity(function() {
					$("#fokusiraj").require("Unesi tekst.");
				});

			});
		</script>
		<form method='post' id='obaveznaf_prtraga'>
			<label>Trazi proizvod:</label>
			<input type='hidden' name='broj_fak' value='<?php echo $brojfak;?>'/>
			<select name='metode' size='1' class='polje_100'>
				<option value='naziv_robe'>naziv robe</option>
				<option value='sifra'>sifra robe</option>
			</select>
			<input type='text' name='search' size='25' class='polje_100_92plus4' id='fokusiraj' style="margin-top:0.3em;">
			<button type='submit' class='dugme_zeleno'>Trazi</button>
		</form>
		<div class="cf"></div>
		<form method="post">
			<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak;?>"/>
			<button type='submit' class='dugme_crveno'>Nazad</button>
		</form>
		<div class="cf"></div>
		<a href="javascript: openwindow()" class="dugme_plavo_92plus4">Nova Roba</a>
		<script>
			function openwindow(){
				window.open("../roba/nova_roba/nova_roba1.php", "_blank","location=1,status=1,scrollbars=1, width=500,height=700");
			} 
		</script>
		<div class="cf"></div>
	</div>
<?php
	} ?>

<?php
if (isset($_POST['broj_fak_pretraga'])){
	$brojfak=$_POST['broj_fak_pretraga'];
	$sifrarob=$_POST['sifra_r'];
	$fak_kol=$_POST['fak_kol'];
	$fak_rab=$_POST['fak_rab'];
	$cena_r=$_POST['cena_r'];
	if (empty($fak_kol)){
		$fak_kol=1;
	}
	if (empty($fak_rab)){
		$fak_rab=0;
	}
	mysql_query("INSERT INTO izlaz (br_dos, srob_dos, koli_dos, cena_d, rab_dos)
				VALUES
				('$brojfak' , '$sifrarob','$fak_kol','$cena_r','$fak_rab')");
	$dodrob=mysql_query("SELECT * FROM roba
						WHERE sifra='$sifrarob'");
	$row = mysql_fetch_array($dodrob);
	$pretsta=$row['stanje'];
	$robkon=$pretsta-$fak_kol;

	mysql_query("UPDATE roba SET stanje = '$robkon'
				WHERE sifra='$sifrarob'");
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			$("#dugme_novo_stanje_robe").focus();
		});
	</script>
	<div class="nosac_glavni_400">
		<p>Novo stanje robe: <?php echo $robkon;?></p>
		<form  method="post">
			<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak; ?>"/>
			<button type="submit" class="dugme_zeleno" id="dugme_novo_stanje_robe">Dalje</button>
		</form>
		<div class="cf"></div>
	</div>
<?php
	} ?>


<?php
//dokument za stampu
if (isset($_POST['broj_fak_stampa'])) {

	$brojfak=$_POST['broj_fak_stampa'];

	$siffirme_upit = mysql_query("SELECT sifra_fir FROM dosta WHERE broj_dost='$brojfak'");
	while($siffirme_red = mysql_fetch_array($siffirme_upit)){
		$siffirme=$siffirme_red['sifra_fir'];
	}

	$datum_upit = mysql_query ("SELECT datum_d, date_format(datum_d, '%d. %m. %Y.') as formatted_date, rok, napomena, uplaceni_avans, date_format(datum_prom, '%d. %m. %Y.') as form_datum_prom
		FROM dosta WHERE broj_dost=$brojfak ");
	$datum_red= mysql_fetch_array ($datum_upit);
	$datumdos=$datum_red['formatted_date'];
	$datum_promet=$datum_red['form_datum_prom'];
	$datumzaporez= $datum_red['datum_d'];
	$rokpl=$datum_red['rok'];
	$napomena=$datum_red['napomena'];
	$uplaceni_avans=$datum_red['uplaceni_avans'];
	//$racun_rucni=$datum_red['racun_rucni'];

	$partner_upit = mysql_query("SELECT * FROM dob_kup WHERE sif_kup='$siffirme'");
	while($partner_red = mysql_fetch_array($partner_upit))
		{
			$kupac=$partner_red['naziv_kup'];
			$ulica_kup=$partner_red['ulica_kup'];
			$post_br=$partner_red['postbr'];
			$mesto_kup=$partner_red['mesto_kup'];
			$pib=$partner_red['pib'];
			$mat_br=$partner_red['mat_br'];
		}

	/*Memorandum*/
	include("../include/ConfigFirma.php");
	?>
	<div class="nosac_sa_tabelom">
		<div class="memorandum screen_hide">
			<?php echo $inkfirma;?>
		</div>
		<div class="nosac_zaglavlja_fakture screen_hide">
			<div class="zaglavlje_fakture_levi">
				<span style="display: inline-block; margin-bottom:6px;">Kupac:</span><br>
				<span style="font-size: 11px;"><b><?php echo $kupac;?></b></span><br>
				<?php echo $ulica_kup;?><br>
				<?php echo $post_br ." ". $mesto_kup;?><br>
				<?php if($pib){echo 'PIB: '. $pib.'<br>';}?>
				<?php if($mat_br){echo 'MAT.BR: '. $mat_br.'<br>';}?>
			</div>
			<div class="zaglavlje_fakture_desni"><span style="font-size: 11px; display: inline-block; margin-bottom:6px;"><b>RAČUN-DOSTAVNICA BR. <?php echo $brojfak."/".$trengodina;?></b></span>
				<br>Mesto i datum izdavanja računa: <?php echo $inkfirma_mir . ", " . $datumdos;?>
				<br>Mesto i datum prometa robe: <?php echo $inkfirma_mir . ", " . $datumdos;?>
			</div>
		</div>
		<p class="print_hide">
			Broj fakture: <?php echo $brojfak."/".$trengodina;?><br>
			Kupac: <?php echo $kupac;?><br>
			Datum fakturisanja: <?php echo $datumdos;?> - 
			Datum prometa: <?php echo $datum_promet;?>
		</p>
		<div class="cf"></div>
		<table>
			<tr>
				<th style="font-size:9px;">r.b.</th>
				<th style="font-size:9px;">Šifra</th>
				<th>Naziv</th>
				<th style="font-size:9px;">Jed.mere</th>
				<th style="font-size:9px;">Količina</th>
				<th style="font-size:9px;">Cena</th>
				<th>Iznos</th>
				<?php
				$rabat_provera_postojanja_upit = mysql_query("SELECT SUM(koli_dos*(cena_d-(cena_d/100)*(100-rab_dos))) AS ukuprab FROM izlaz WHERE br_dos='$brojfak'");
				$rabat_provera_postojanja_red = (mysql_fetch_array($rabat_provera_postojanja_upit));
				if ($rabat_provera_postojanja_red['ukuprab'] != 0){
					echo "<th>Rabat</th>";
				}
				?>
				<th>PDV</th>
			</tr>
			<?php
			$i=1;
			$prikaz_roba_upit = mysql_query("SELECT izlaz.id, izlaz.br_dos, izlaz.srob_dos, izlaz.koli_dos, izlaz.cena_d, izlaz.rab_dos,
									izlaz.cena_d*izlaz.koli_dos AS ukupdos, roba.sifra, roba.naziv_robe, roba.cena_robe,roba.porez,

									(SELECT porez_procenat FROM poreske_stope
									WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
									AND tarifa_stope = roba.porez
									AND porez_datum <= '$datumzaporez') AS robaporez,

									(((((izlaz.cena_d/100)*(100-izlaz.rab_dos))/100)*(100+

									(SELECT porez_procenat FROM poreske_stope
									WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
									AND tarifa_stope = roba.porez
									AND porez_datum <= '$datumzaporez')

									))*izlaz.koli_dos) AS ukupdospor, roba.jed_mere
									FROM izlaz RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra WHERE br_dos='$brojfak'");
			while($prikaz_roba_red = mysql_fetch_array($prikaz_roba_upit)) {
			?>
			<tr>
				<td style="text-align:center;"><?php echo $i++;?></td>
				<td style="text-align:center;"><?php echo $prikaz_roba_red['srob_dos'];?></td>
				<td style="text-align:left;"><?php echo $prikaz_roba_red['naziv_robe'];?></td>
				<td style="text-align:center;"><?php echo $prikaz_roba_red['jed_mere'];?></td>
				<td><?php echo number_format($prikaz_roba_red['koli_dos'], 2,".",",")+0;?></td>
				<td><?php echo number_format($prikaz_roba_red['cena_d'], 2,".",",");?></td>
				<td><?php echo number_format($prikaz_roba_red['ukupdos'], 2,".",",");?></td>
				<?php
				if ($rabat_provera_postojanja_red['ukuprab'] != 0) { ?>
					<td><?php echo number_format($prikaz_roba_red['rab_dos'], 0,"","");?>%</td>
				<?php } ?>
				<td><?php echo $prikaz_roba_red['robaporez'];?>%</td>
				<td class="print_hide">
					<form method='post'>
						<input type='hidden' name='broj_fak_brisi' value='<?php echo $brojfak;?>'/>
						<input type='hidden' name='id_dos' value='<?php echo $prikaz_roba_red['id'];?>'/>
						<input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
					</form>
				</td>
			</tr>
			<?php }
			//sa rabatom racun
			if ($rabat_provera_postojanja_red['ukuprab'] != 0){ ?>
			<tr>
				<td rowspan='8' colspan='2' style="border:none;"></td>
				<td colspan='4' style="border:none;">Vrednost: </td>
				<td><?php $zbir_upit = mysql_query("SELECT SUM(cena_d*koli_dos) AS ukupiznul FROM izlaz WHERE br_dos='$brojfak'");
					$zbir_red=(mysql_fetch_array($zbir_upit));
					echo number_format($zbir_red['ukupiznul'], 2,".",",");?>
				</td>
			</tr>
			<tr>
				<td colspan='4' style="border:none;">Iznos odobrenog rabata:</td>
				<td><?php echo number_format($rabat_provera_postojanja_red['ukuprab'], 2,".",",");?></td>
			</tr>
			<tr>
				<td colspan="4" style="border:none;">Vrednost-rabata:</td>
				<td><?php echo number_format((($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab'])), 2,".",",");?></td>
			</tr>

			<?php
			//osnovica 10
			OsnovicaZaPdv(10,$datumzaporez,$brojfak);
			//osnovica 20
			OsnovicaZaPdv(20,$datumzaporez,$brojfak);
			?>

			<tr>
				<td colspan='4' style="border:none;">Ukupan PDV:</td>
				<td>
					<?php $ukupan_pdv_upit = mysql_query("SELECT izlaz.br_dos, izlaz.srob_dos, SUM(((izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos))/100)*(100+
					(SELECT porez_procenat FROM poreske_stope
									WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
									AND tarifa_stope = roba.porez
									AND porez_datum <= '$datumzaporez')
					))-(izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos)))) AS ukupporez, roba.sifra
					FROM izlaz RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra WHERE br_dos='$brojfak'");
					$ukupan_pdv_red = (mysql_fetch_array($ukupan_pdv_upit));
					echo number_format($ukupan_pdv_red['ukupporez'], 2,".",",");?>
				</td>
			</tr>
			<tr>
				<td colspan='4' style="border:none;">Ukupna vrednost sa PDV-om:</td>
				<td><b><?php echo number_format((($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",",");?></b></td>
			</tr>
			<?php
			}

			//Bez rabata racun
			else { ?>
			<tr>
				<td rowspan='7' colspan='2' style="border:none;"></td>
				<td colspan='4' style="border:none;">Vrednost:</td>
				<td><?php $zbir_upit = mysql_query("SELECT SUM(cena_d*koli_dos) AS ukupiznul FROM izlaz WHERE br_dos='$brojfak'");
					$zbir_red=(mysql_fetch_array($zbir_upit));
					echo number_format($zbir_red['ukupiznul'], 2,".",",");?>
				</td>
			</tr>

			<?php
			//osnovica 10
			OsnovicaZaPdv(10,$datumzaporez,$brojfak);
			//osnovica 20
			OsnovicaZaPdv(20,$datumzaporez,$brojfak);
			?>

			<tr>
				<td colspan='4' style="border:none;">Ukupan PDV:</td>
				<td>
					<?php $ukupan_pdv_upit = mysql_query("SELECT izlaz.br_dos, izlaz.srob_dos, SUM(((izlaz.koli_dos*
					((izlaz.cena_d/100)*(100-izlaz.rab_dos))
					/100)*(100+
					(SELECT porez_procenat FROM poreske_stope
									WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
									AND tarifa_stope = roba.porez
									AND porez_datum <= '$datumzaporez')
					))-(izlaz.koli_dos*((izlaz.cena_d/100)*(100-izlaz.rab_dos)))) AS ukupporez, roba.sifra
					FROM izlaz RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra WHERE br_dos='$brojfak'");
					$ukupan_pdv_red = (mysql_fetch_array($ukupan_pdv_upit));
					echo number_format($ukupan_pdv_red['ukupporez'], 2,".",",");?>
				</td>
			</tr>
			<tr>
				<td colspan='4' style="border:none;">Ukupna vrednost sa PDV-om:</td>
				<td><b><?php echo number_format((($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",",");?></b></td>
			</tr>
			<?php
			}
			if ($uplaceni_avans!= 0){ ?>

				<tr>
					<td colspan='4' style="border:none;">Avansno uplaćeno:</td>
					<td><?php echo number_format($uplaceni_avans, 2,".",",");?></td>
				</tr>
				<tr>
					<td colspan='4' style="border:none;">ZA UPLATU:</td>
					<td><b><?php echo number_format((
					((($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab']))+($ukupan_pdv_red['ukupporez']))-
					$uplaceni_avans), 2,".",",");?></b></td>
				</tr>
			<?php
			}
			?>
		</table>
		<div class="cf"></div>
		<script type="text/javascript">
				jQuery(document).ready(function() {
					$("#fokusiraj").focus();
					$("#obaveznaf_prtraga").validity(function() {
						$("#fokusiraj").require("Unesi tekst.")
					});
				});
		</script>
		<form method='post' id='obaveznaf_prtraga' class="print_hide">
			<label>Trazi proizvod:</label>
			<input type='hidden' name='broj_fak' value='<?php echo $brojfak;?>'/>
			<select name='metode' size='1' class='polje_100'>
				<option value='naziv_robe'>naziv robe</option>
				<option value='sifra'>sifra robe</option>
			</select>
			<input type='text' name='search' class='polje_100_92plus4' id='fokusiraj' style='margin-top:0.3em;'>
			<button type='submit' class='dugme_zeleno'>Trazi</button>
		</form>
		<div class="cf"></div>
		
		<?php
		//Obnova razlika u ceni
		$izzad=number_format((($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab']))+($ukupan_pdv_red['ukupporez']), 2,".",",");
		$izzad_bez_form_br=(($zbir_red['ukupiznul'])-($rabat_provera_postojanja_red['ukuprab']))+($ukupan_pdv_red['ukupporez']);
		$ispor=number_format($ukupan_pdv_red['ukupporez'], 2,".",",");
		$ispor_bez_form_br=$ukupan_pdv_red['ukupporez'];
		$odo_rab=number_format($rabat_provera_postojanja_red['ukuprab'], 2,".",",");
		$odo_rab_bez_form_br=$rabat_provera_postojanja_red['ukuprab'];
		$bruc_upit = mysql_query("SELECT izlaz.br_dos, izlaz.srob_dos,
									SUM(((izlaz.cena_d/100)*roba.ruc)*izlaz.koli_dos) AS bruc,
									roba.sifra FROM izlaz RIGHT JOIN roba ON izlaz.srob_dos=roba.sifra
									WHERE br_dos='$brojfak'");
		$bruc_red = (mysql_fetch_array($bruc_upit));
		$bruc=number_format($bruc_red['bruc'], 2,".",",");
		$bruc_bez_formatiranja_broja=$bruc_red['bruc'];
		mysql_query("UPDATE dosta
					SET izzad='$izzad_bez_form_br', ispor='$ispor_bez_form_br', odo_rab='$odo_rab_bez_form_br', bruc='$bruc_bez_formatiranja_broja'
					WHERE broj_dost=$brojfak");
		?>

		<a href="javascript: openwindow()" class="dugme_plavo_pola print_hide">Nova Roba</a>
		<button onClick='window.print()' type='button' class='dugme_plavo_pola margin_left_2per print_hide'>Stampaj</button>
		<a href="promeni_partnera.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_fak print_hide">Promeni partnera ili datum</a>
		<a href="promeni_napomenu.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_fak margin_left_right_2per print_hide">Izmeni napomenu</a>
		<a href="uplaceni_avans.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_fak print_hide">Uredi uplaceni avans</a>
		<div class="cf"></div>
		<a href="otpremnica.php?broj_fak_stampa=<?php echo $brojfak;?>" class="dugme_plavo_fak print_hide">Pregled Otpremnice</a>
		<a href="faktura_roba.php?broj_fak_stampa=<?php echo $brojfak;?>" class="dugme_plavo_fak margin_left_right_2per print_hide">Pregled Racun bez otpre.</a>
		<a href="faktura_usluga.php?broj_fak_stampa=<?php echo $brojfak;?>" class="dugme_plavo_fak print_hide">Pregled Racun usluga sa opisom</a>
		<div class="cf"></div>
		<a href="../index.php" class="dugme_crveno_pola print_hide">Pocetna strana</a>
		<form method="post" action="faktura_stare.php">
			<input type="hidden" type="text" name="sve_fakture" value="1"/>
			<button type="submit" class="dugme_crveno_pola margin_left_2per print_hide">Nazad na stare fakture</button>
		</form>
		<div class="cf"></div>
		<script>
			function openwindow(){
				window.open("../roba/nova_roba/nova_roba1.php", "_blank","location=1,status=1,scrollbars=1, width=500,height=700");
			} 
		</script>
		<p style="font-size:11px;">
			<?php echo $napomena;?>
		</p>
		<div id="potpis0">
			<div class="potpis1">
				<p>Izdao i Fakturisao</p>
			</div>
			<div class="potpis2">
				<p>
					Robu primio<br>
					Br. L.K.<br>
				</p>
			</div>

		</div>
	</div>
<?php
	} ?>

<?php
//Brisanje robe
if (isset($_POST['broj_fak_brisi'])) {
	$brojfak=$_POST['broj_fak_brisi'];
	$id_dos=$_POST['id_dos'];

	$prestanje_upit=mysql_query("SELECT * FROM izlaz WHERE id='$id_dos'");
	$prestanje_red=mysql_fetch_array($prestanje_upit);
	$prestanje=$prestanje_red['koli_dos'];
	$sifrob=$prestanje_red['srob_dos'];

	$robsta_upit=mysql_query("SELECT stanje FROM roba WHERE sifra='$sifrob'");
	$robsta_red=mysql_fetch_array($robsta_upit);
	$robsta=$robsta_red['stanje'];

	mysql_query("DELETE FROM izlaz WHERE id='$id_dos' AND br_dos='$brojfak'");

	$izmenastanja=$robsta+$prestanje;
	mysql_query("UPDATE roba SET stanje = '$izmenastanja' WHERE sifra='$sifrob'");
	?>
	<div class="nosac_glavni_400">
		<h2>Izbrisano.</h2>
		<p>Stanje robe: <?php echo $izmenastanja;?></p>
		<form method="post">
			<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak; ?>"/>
			<button type="submit" class="dugme_zeleno">Dalje</button>
		</form>
		<div class="cf"></div>
	</div>
<?php
} ?>

<?php
if(empty($_POST['broj_fak_brisi'])&&
	empty($_POST['partnersif'])&&
	empty($_POST['metode'])&&
	empty($_POST['search'])&&
	empty($_POST['broj_fak_pretraga'])&&
	empty($_POST['broj_fak_stampa'])) {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#firma").AddIncSearch({
			maxListSize: 4,
			maxMultiMatch: 50,
			selectBoxHeight: 400,
			warnMultiMatch: 'top {0} matches ...',
			warnNoMatch: 'nema poklapanja...'
		});
		$("#obaveznaf").validity(function() {
			$("#firma").require("Neophodno polje.");
			$("#rokplacanja_in").require("Neophodno polje.").match("number","Mora biti broj.")
		});

	});
	</script>
	<div class="nosac_glavni_400">
		<form id="obaveznaf" method="post">
			<label>Kupac:</label>
			<select id='firma' name='partnersif' size='1' class='polje_100'>
				<option value=''>Odaberi</option>
					<?php
					$upit = mysql_query("SELECT sif_kup,naziv_kup,ziro_rac FROM dob_kup");
					while($red = mysql_fetch_array($upit)){
						$naziv_kup=$red['naziv_kup'];
						$sif_kup=$red['sif_kup'];
						?>
						<option value="<?php echo $sif_kup;?>"><?php echo $naziv_kup;?></option>
					<?php } ?>
			</select>
			<label>Rok placanja:</label>
			<input type="text" name="rok_placanja" class="polje_100_92plus4" id="rokplacanja_in"/>
			
			<button type="submit" class="dugme_zeleno">Unesi</button>
		</form>
		<div class="cf"></div>
		<a href="javascript: windowNoviKupac()" class="dugme_plavo_92plus4 print_hide">Novi Partner</a>
		<script>
			function windowNoviKupac(){
				window.open("../partneri/partner_novi1.html", "_blank","location=1,status=1,scrollbars=1, width=500,height=700");
			} 
		</script>
		<div class="cf"></div>
		<form action="../index.php" method="post">
			<button type="submit" class="dugme_crveno">Ponisti</button>
		</form>
		<div class="cf"></div>
	</div>
<?php } ?>
</body>
</html>
