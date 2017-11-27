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
//dokument za stampu
if (isset($_GET['broj_fak_stampa'])) {
	$brojfak=$_GET['broj_fak_stampa'];

	$siffirme_upit = mysql_query("SELECT sifra_fir FROM dosta WHERE broj_dost='$brojfak'");
	while($siffirme_red = mysql_fetch_array($siffirme_upit)){
		$siffirme=$siffirme_red['sifra_fir'];
	}

	$datum_upit = mysql_query ("SELECT datum_d, date_format(datum_d, '%d. %m. %Y.') as formatted_date, rok, napomena, uplaceni_avans FROM dosta WHERE broj_dost=$brojfak ");
	$datum_red= mysql_fetch_array ($datum_upit);
	$datumdos=$datum_red['formatted_date'];
	$datumzaporez= $datum_red['datum_d'];
	$rokpl=$datum_red['rok'];
	$napomena=$datum_red['napomena'];

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
			<div class="zaglavlje_fakture_levi"><b>OTPREMNICA br. <?php echo $brojfak."/".$trengodina;?></b>
				<br><br>Mesto izdavanja otpremnice: <b><?php echo $inkfirma_mir;?></b>
				<br>Datum izdavanja otpremnice: <b><?php echo $datumdos;?></b>
				<br>Mesto prometa: <b><?php echo $inkfirma_mir;?></b>
				<br>Datum prometa: <b><?php echo $datumdos;?></b>
			</div>
			<div class="zaglavlje_fakture_desni">
				Kupac:<br>
				<b><?php echo $kupac;?></b>
				<br><?php echo $ulica_kup;?>
				<br><?php echo $post_br ." ". $mesto_kup;?>
				<br>
				PIB <?php echo $pib;?>
			</div>
		</div>
		<p class="print_hide">
			Broj fakture: <?php echo $brojfak;?><br>
			Kupac: <?php echo $kupac;?><br>
			Datum: <?php echo $datumdos;?>
		</p>
		<div class="cf"></div>
		<table>
			<tr>
				<th style="font-size:9px;">r.b.</th>
				<th style="font-size:9px;">Šifra</th>
				<th>Naziv</th>
				<th style="font-size:9px;">Jed.mere</th>
				<th style="font-size:9px;">Količina</th>
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
				<td><?php echo $prikaz_roba_red['koli_dos'];?></td>
				
				<td class="print_hide">
					<form method='post'>
						<input type='hidden' name='broj_fak_brisi' value='<?php echo $brojfak;?>'/>
						<input type='hidden' name='id_dos' value='<?php echo $prikaz_roba_red['id'];?>'/>
						<input type='image' id='btnPrint' src='../include/images/iks.png' title='Ispravi'/>
					</form>
				</td>
			</tr> 
			<?php } 
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
		<form method='post' id='obaveznaf_prtraga' class="print_hide" action="faktura.php">
			<label>Trazi proizvod:</label>
			<input type='hidden' name='broj_fak' value='<?php echo $brojfak;?>'/>
			<select name='metode' size='1' class='polje_100'>
				<option value='naziv_robe'>naziv robe</option>
				<option value='sifra'>sifra robe</option>
			</select>
			<input type='text' name='search' class='polje_100_92plus4' id='fokusiraj' style='margin-top:0.3em;'>
			<button type='submit' class='dugme_plavo'>Trazi</button>
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
		
		<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
		<a href="promeni_partnera.php?brojfak=<?php echo $brojfak;?>" class="dugme_plavo_92plus4 print_hide">Promeni partnera ili datum</a>
		<form  method="post" action="faktura.php">
			<input type="hidden" name="broj_fak_stampa" value="<?php echo $brojfak; ?>"/>
			<button type="submit" class="dugme_zeleno print_hide" id="dugme_novo_stanje_robe">Pregled Fakture</button>
		</form>
		<a href="../index.php" class="dugme_zeleno_92plus4 print_hide">Pocetna strana</a>
		<div class="cf"></div>
		<p class="screen_hide" style="font-size:12px;">
			<?php //include("../include/ConfigFirma.php");
			echo $napomena;?>
		</p>
		<div id="potpis0">
			<div class="potpis1">
				<p>Izdao i Fakturisao</p>
			</div>
			<div class="potpis2">
				<p>
					Robu primio<br>
					Br. L.K.
				</p>
			</div>
			
		</div>
	</div>
<?php
}
else {
	echo "Greska!";
}
?>
</body>
</html>