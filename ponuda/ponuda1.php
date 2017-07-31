<?php
require("../include/DbConnectionPDO.php");
include("../include/ConfigFirma.php");

if (isset($_GET['ponuda_nova'])){
	$upit_unos_pon_blanko = 'INSERT INTO ponuda (datum) VALUES (NOW())';
	$baza_pdo->query($upit_unos_pon_blanko);
	$br_ponude = $baza_pdo->lastInsertId();
}

if (isset($_GET['ponuda_stara'])){
	$ponuda_stara = filter_input(INPUT_GET, 'ponuda_stara', FILTER_SANITIZE_STRING);
	$upit_ponuda = "SELECT * FROM ponuda WHERE id_ponude = $ponuda_stara";
	foreach ($baza_pdo->query($upit_ponuda) as $red_pon) {
		$br_ponude=$red_pon['id_ponude'];
		$sifra_fir=$red_pon['sifra_fir'];
		$rok=$red_pon['rok'];
		$izzad=$red_pon['izzad'];
		$ispor=$red_pon['ispor'];
		$odo_rab=$red_pon['odo_rab'];
		$napomena=$red_pon['napomena'];
		$ponuda_br_rucni=$red_pon['ponuda_br_rucni'];
		$datum=date("d-m-Y",(strtotime($red_pon['datum'])));
		$partner_tekst=$red_pon['partner_tekst'];
	}

	$upit_stavke = "SELECT * FROM ponuda_stavke WHERE br_ponude = $ponuda_stara";
}


//ponuda
//id_ponude 	datum 	sifra_fir 	rok 	izzad 	ispor 	odo_rab 	napomena 	ponuda_br_rucni 
//ponuda_stavke
//id_rob 	br_ponude 	naziv_robe 	sifra_robe 	kolicina 	jed_mere 	cena_profak 	rabat 	porez 	ruc_profak 

if(isset($_POST['id_ponude_u'])) {
	$sql = 'UPDATE ponuda SET datum=:datum, sifra_fir=:sifra_fir, rok=:rok, izzad=:izzad, ispor=:ispor, odo_rab=:odo_rab, napomena=:napomena, ponuda_br_rucni=:ponuda_br_rucni, partner_tekst=:partner_tekst
		  WHERE id_ponude = :id_ponude';
	$stmt = $baza_pdo->prepare($sql);

	$datum=date("Y-m-d",(strtotime($_POST['datum'])));
	$sifra_fir = filter_input(INPUT_POST, 'sifra_fir', FILTER_SANITIZE_STRING);
	$rok = filter_input(INPUT_POST, 'rok', FILTER_SANITIZE_STRING);
	$izzad = filter_input(INPUT_POST, 'ukupna_vrednost_sa_pdv', FILTER_SANITIZE_STRING);
	$ispor = filter_input(INPUT_POST, 'ukupan_pdv', FILTER_SANITIZE_STRING);
	$odo_rab = filter_input(INPUT_POST, 'zbir_rabat', FILTER_SANITIZE_STRING);
	$napomena = filter_input(INPUT_POST, 'napomena', FILTER_SANITIZE_STRING);
	$ponuda_br_rucni = filter_input(INPUT_POST, 'ponuda_br_rucni', FILTER_SANITIZE_STRING);
	$id_ponude_u = filter_input(INPUT_POST, 'id_ponude_u', FILTER_SANITIZE_STRING);
	$partner_tekst = filter_input(INPUT_POST, 'partner_tekst', FILTER_SANITIZE_STRING);

	$stmt->bindParam(':datum', $datum, PDO::PARAM_STR);
	$stmt->bindParam(':sifra_fir', $sifra_fir, PDO::PARAM_STR);
	$stmt->bindParam(':rok', $rok, PDO::PARAM_STR);
	$stmt->bindParam(':izzad', $izzad, PDO::PARAM_STR);
	$stmt->bindParam(':ispor', $ispor, PDO::PARAM_STR);
	$stmt->bindParam(':odo_rab', $odo_rab, PDO::PARAM_STR);
	$stmt->bindParam(':napomena', $napomena, PDO::PARAM_STR);
	$stmt->bindParam(':ponuda_br_rucni', $ponuda_br_rucni, PDO::PARAM_STR);
	$stmt->bindParam(':id_ponude', $id_ponude_u, PDO::PARAM_STR);
	$stmt->bindParam(':partner_tekst', $partner_tekst, PDO::PARAM_STR);
	$stmt->execute();
	$OK = $stmt->rowCount();
	if ($OK) {
		$info="Uredjeno.";
	}


	$upit_brisi_stavke = "DELETE FROM ponuda_stavke WHERE br_ponude = :id_ponude";
	$stmt_brisi_stavke = $baza_pdo->prepare($upit_brisi_stavke);
	$stmt_brisi_stavke->bindParam(':id_ponude', $id_ponude_u, PDO::PARAM_STR);
	$stmt_brisi_stavke->execute();
	$OKupit_brisi_stavke = $stmt_brisi_stavke->rowCount();
	if ($OKupit_brisi_stavke) {
		$info_brisanje="Uredjeno brisanje.";
	}
	
	$sql_stavke = 'INSERT INTO ponuda_stavke (br_ponude, naziv_robe, kolicina, jed_mere, cena_profak, rabat, porez) VALUES 
        (:br_ponude, :naziv_robe, :kolicina, :jed_mere, :cena_profak, :rabat, :porez)';
	$stmt_stavke_unos = $baza_pdo->prepare($sql_stavke);
	foreach(array_keys($_POST['ime_rob']) as $n){
		$naziv_robe=$_POST['ime_rob'];
		$kolicina=$_POST['kolicina'];
		$jed_mere=$_POST['jedinica_m'];
		$cena_profak=$_POST['cena'];
		$rabat=$_POST['rabat'];
		$porez=$_POST['pdv'];

		$stmt_stavke_unos->bindParam(':br_ponude', $id_ponude_u, PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':naziv_robe', $naziv_robe[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':kolicina', $kolicina[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':jed_mere', $jed_mere[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':cena_profak', $cena_profak[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':rabat', $rabat[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->bindParam(':porez', $porez[$n], PDO::PARAM_STR);
		$stmt_stavke_unos->execute();
	}


	if ($_POST['action'] == 'Snimi') {
		header("Location: ponuda_stara.php"); /* Redirect browser */
		exit();
	}
	else{
		//$id_ponude_u=$_POST['id_ponude_u'];
		$id_pon=$_POST['id_ponude_u'];
		header("Location: ponuda_pretraga1.php?br_ponude=$id_pon"); /* Redirect browser */
		exit();
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ponuda</title>
	<link rel="stylesheet" type="text/css" href="css/ponuda_stil.css">
	<script type="text/javascript" src="../include/jquery/jquery-1.6.2.min.js"></script>
	
	
	<script type='text/javascript' src='js/example.js'></script>

	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">
	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>
	<script type="text/javascript" src="js/jquery.AddIncSearch.js"></script>
	<script type="text/javascript" src="../include/form/jquery.validity.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/form/jquery.validity.css">

	<script type="text/javascript">
		$(document).ready(function(){
			$("#firma").AddIncSearch({
		        maxListSize: 4,
		        maxMultiMatch: 50,
		        selectBoxHeight: 400,
		        warnMultiMatch: 'top {0} matches ...',
		        warnNoMatch: 'nema poklapanja...'
	    	});
			$("#obaveznaf").validity(function() {
				$("#firma")
		        .require("Polje je neophodno...");
		        $("#rok_placanja")
		        .require("Polje je neophodno...")
				.match("number","Mora biti broj.");
		    });
		    $( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
		});
	</script>
</head>

<body>
	<pre id=whereToPrint></pre>
	<div class="nosac_sa_tabelom">
	<form method="post" action="ponuda1.php">
		
		<div class="memorandum screen_hide">
			<?php echo $inkfirma;?>
		</div>
		
		<div class="nosac_zaglavlja_fakture">
			<div class="zaglavlje_fakture_levi">
				<span style="font-size: 15px;">
					<!--<b>PONUDA BR. <?php if(isset($br_ponude)) {echo $br_ponude; }?> <?php if(isset($id_ponude)) {echo $id_ponude; }?> <?php echo "/".$trengodina;?></b>-->
					<b>PONUDA BR.  </b><input type="text" name="ponuda_br_rucni" class="broj_ponude" value="<?php 
							if (isset($ponuda_br_rucni)) {echo $ponuda_br_rucni; }
							else{
								if(isset($br_ponude)) {echo $br_ponude; }
								if(isset($id_ponude)) {echo $id_ponude; }
							}
						?>" /><?php echo "/".$trengodina;?>
				</span>
				<br><br>Mesto izdavanja predračuna: <b><?php echo $inkfirma_mir;?></b>
				<br>Datum izdavanja predračuna: <input id="biracdatuma" type="text" name="datum" value="<?php if(isset($datum)) {echo $datum; }?>" class="date" />
			</div>
			<div class="zaglavlje_fakture_desni">
				<span style="font-size: 12px;display: inline-block; margin-bottom:6px;">Kupac:</span><br>
				<div class="cf"></div>
				<a href="#" id="dugme_ubaci" class="print_hide">Ubaci</a>
				<div class="cf"></div>
				<textarea id="partner" rows="4" name="partner_tekst"><?php if(isset($partner_tekst)) {echo $partner_tekst; }?></textarea>
				<select id='firma' name='sifra_fir' size='1' class='polje_100 print_hide'>
				<option value=''>Odaberi</option>
				<?php
				$upit = "SELECT sif_kup, naziv_kup, postbr, mesto_kup, ulica_kup, pib FROM dob_kup";
				foreach ($baza_pdo->query($upit) as $red) {
					$naziv_kup=$red['naziv_kup'];
					$sif_kup=$red['sif_kup'];
					$postbr=$red['postbr'];
					$mesto_kup=$red['mesto_kup'];
					$ulica_kup=$red['ulica_kup'];
					$pib=$red['pib'];
					?>
					<option value='<?php echo $sif_kup;?>'
					label='<?php echo $naziv_kup;?>1&#013;<?php echo $ulica_kup;?>1&#013;<?php echo $postbr." ".$mesto_kup;?>1&#013;<?php echo $pib;?>' 
					<?php if(isset($sifra_fir) && $sifra_fir == $sif_kup) {echo "selected"; }?>
					>
					<?php echo $naziv_kup;?>
					</option>
					<?php
				}
				?>
			</select>
				<!--
				//id_rob 	br_ponude 	naziv_robe 	sifra_robe 	kolicina 	jed_mere 	cena_profak 	rabat 	porez 	ruc_profak 
				<b><span style="font-size: 16px;"><?php //echo $kupac;?></span></b>
				<br><?php //echo $ulica_kup;?>
				<br><?php //echo $post_br ." ". $mesto_kup;?>
				<br>
				PIB <?php //echo $pib;?>
				-->
			</div>
		</div>

		<div class="cf"></div>
		<table id="items">
			<tr>
				<th style="font-size:9px;">r.b.</th>
				<th>Naziv</th>
				<th style="font-size:9px;">Jed.mere</th>
				<th style="font-size:9px;">Količina</th>
				<th style="font-size:9px;">Cena</th>
				<th style="font-size:9px;">Iznos</th>
				<th style="font-size:9px;">Rabat %</th>
				<th style="font-size:9px;">Rabat Ukupan</th>
				<th style="font-size:9px;">PDV %</th>
				<th style="font-size:9px;">PDV Ukupan</th>
				<!--<th>Iznos+PDV</th>-->
			</tr>
		



			<?php if(isset($upit_stavke) && $baza_pdo->query($upit_stavke)->rowCount()) {

				//var_dump($baza_pdo->query($upit_stavke)->rowCount());
				foreach ($baza_pdo->query($upit_stavke) as $red_stavke) {
					$naziv_robe=$red_stavke['naziv_robe'];
					$jed_mere=$red_stavke['jed_mere'];
					$kolicina=$red_stavke['kolicina'];
					$cena_profak=$red_stavke['cena_profak'];
					$rabat=$red_stavke['rabat'];
					$porez=$red_stavke['porez'];
					?>

					<tr class="item-row">
			  	  <td class="redni_br"></td>
			      <td class="item-name"><div class="delete-wpr" name="naziv"><textarea name="ime_rob[]" class="ime_robe"><?php echo $naziv_robe;?></textarea><a class="delete print_hide" href="javascript:;" title="Ukloni red">X</a></div></td>
			      <td><textarea name="jedinica_m[]" class="j_m"><?php echo $jed_mere;?></textarea></td>
			      <td><textarea class="qty" name="kolicina[]"><?php echo $kolicina;?></textarea></td>
			      <td><textarea class="cost" name="cena[]"><?php echo $cena_profak;?></textarea></td>
			      <td><span class="iznos">0.00</span></td>
			      <td><textarea class="rabat" name="rabat[]"><?php echo $rabat;?></textarea></td>
			      <td><span class="rabat_iznos">0.00</span></td>
			      <td><textarea class="pdv" name="pdv[]"><?php echo $porez;?></textarea></td>
			      <td><span class="pdv_iznos">0.00</span></td>
			  	</tr>

				<?php
				}
			}
				else{
					?>
					<tr class="item-row">
			  	  <td class="redni_br"></td>
			      <td class="item-name"><div class="delete-wpr" name="naziv"><textarea name="ime_rob[]" class="ime_robe">Prajci tovljenici</textarea><a class="delete" href="javascript:;" title="Ukloni red">X</a></div></td>
			      <td class="j_m"><textarea name="jedinica_m[]" class="j_m">kom</textarea></td>
			      <td><textarea class="qty" name="kolicina[]">1</textarea></td>
			      <td><textarea class="cost" name="cena[]">0.00</textarea></td>
			      <td><span class="iznos">0.00</span></td>
			      <td><textarea class="rabat" name="rabat[]">0</textarea></td>
			      <td><span class="rabat_iznos">0.00</span></td>
			      <td><textarea class="pdv" name="pdv[]">20</textarea></td>
			      <td><span class="pdv_iznos">0.00</span></td>
		  		</tr>
					<?php
				}?>

			
			<tr>
		  	<td rowspan='7' colspan='2' style='border:none;'></td>
				<td colspan='3' style="border:none;">Zbir:</td>
				<td><div id="zbir">0.00</div></td>
			</tr>
			<tr>
				<td colspan='3' style="border:none;">Iznos odobrenog rabata:</td>
				<td>
					<div id="zbir_rabat">0.00</div>
					<input id="input_zbir_rabat" type="hidden" name="zbir_rabat" value=""/>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border:none;">Zbir-rabat:</td>
				<td><div id="zbir_minus_rabat">0.00</div></td>
			</tr>
			<tr>
				<td colspan='3' style="border:none;">Ukupan PDV:</td>
				<td>
					<div id="ukupan_pdv">0.00</div>
					<input id="input_ukupan_pdv" type="hidden" name="ukupan_pdv" value=""/>
				</td>
			</tr>
			<tr>
				<td colspan='3' style="border:none;">Ukupna vrednost sa PDV-om:</td>
				<td>
					<div id="ukupna_vrednost_sa_pdv">0.00</div>
					<input id="input_ukupna_vrednost_sa_pdv" type="hidden" name="ukupna_vrednost_sa_pdv" value=""/>
				</td>
			</tr>
			<tr class="avans">
				<td colspan='3' style="border:none;">Avansno uplaćeno:</td>
				<td><textarea id="paid">0.00</textarea></td>
			</tr>
			<tr class="avans">
				<td colspan='3' style="border:none;">Ostalo za uplatu:</td>
				<td><div class="due">0.00</div></td>
			</tr>
		</table>
		<br>
		<div class="cf"></div>
		<p class="" style="font-size:12px;">
			<textarea class="sirina100" name="napomena" rows="8">Napomena o poreskom oslobođenju: NEMA.
Plaćanje avansno.
Uplatu izvršiti na račun 330-33000385-44 sa pozivom na broj <?php if(isset($id_ponude_u)) {echo $id_ponude_u; }?> <?php if(isset($br_ponude)) {echo $br_ponude; }?> <?php echo "/".$trengodina;?> 
			</textarea>
		</p>
		<div id="potpis0">
			<div class="potpis1">
				<p>Izdao i Fakturisao</p>
			</div>
		</div>
		<a id="addrow" href="javascript:;" title="Dodaj red" class="dugme_plavo_pola print_hide"> Dodaj red</a>
		<input type="submit" name="action" value="Pretrazi" class="dugme_plavo_pola margin_left_2per print_hide"/>
		<div class="cf"></div>
		<input type="hidden" name="id_ponude_u" <?php if(isset($br_ponude)) {echo "value='".$br_ponude."'"; }?> <?php if(isset($id_ponude_u)) {echo "value='".$id_ponude_u."'"; }?>/>
		<input type="submit" name="action" value="Snimi" class="dugme_zeleno print_hide"/>
		</form>
		<div class="cf"></div>
		<button onClick='window.print()' type='button' class='dugme_plavo print_hide'>Stampaj</button>
	</div>
	<script type='text/javascript' src='js/jquery.autogrow.js'></script>
	<script>
		$(document).ready(function(){
	  	$('.ime_robe').autogrow();
	  });
	</script>
</body>
</html>