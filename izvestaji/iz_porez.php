<?php
require("../include/DbConnection.php");
require("../include/ConfigPDV.php");
function PdvPoDostavnicama($procenat_stope,$broj_dostavnice,$sifra_poreza){
	$pdv_dost_upit=mysql_query("SELECT 
							sum(
								(
									(
										(
											(
												(izlaz.cena_d/100)*(100-izlaz.rab_dos)
											)/100
										)*
										(100+" . $procenat_stope. "
										)
									)-
									(
										(izlaz.cena_d/100)*(100-izlaz.rab_dos)
									)
								)*izlaz.koli_dos
							) AS dospdvsuma,
						dosta.datum_d
						FROM izlaz 
						LEFT JOIN roba ON izlaz.srob_dos=roba.sifra
						LEFT JOIN dosta ON izlaz.br_dos=dosta.broj_dost
						WHERE izlaz.br_dos = " .$broj_dostavnice. " 
						AND roba.porez=".$sifra_poreza."
						GROUP BY izlaz.br_dos
						") or die ("greska u upitu pdv_dost_upit");
	$row_pdv_dost = mysql_fetch_array($pdv_dost_upit);
	if (isset($row_pdv_dost['dospdvsuma'])){
		$pdv_dost=number_format($row_pdv_dost['dospdvsuma'], 2,".","");
		return $pdv_dost;
	}
	else{
		$pdv_dost=0;
		return $pdv_dost;
	}

}


function PdvPoKalkulacijama($procenat_stope,$broj_kalkulacije,$sifra_poreza){
	$pdv_kalk_upit=mysql_query("SELECT 
									sum(
										(
											(
												(
													(
														(ulaz.cena_k/100)*(100-ulaz.rab_kalk)
													)/100
												)*
												(100+".$procenat_stope.")
											)-
											(
												(ulaz.cena_k/100)*(100-ulaz.rab_kalk)
											)
										)*ulaz.kol_kalk
									) AS kalkpdvsuma,
								kalk.datum
								FROM ulaz 
								LEFT JOIN roba ON ulaz.srob_kal=roba.sifra
								LEFT JOIN kalk ON ulaz.br_kal=kalk.broj_kalk
								WHERE ulaz.br_kal = ".$broj_kalkulacije." 
								AND roba.porez=".$sifra_poreza."
								GROUP BY ulaz.br_kal
								") or die ("greska u upitu pdv_kalk_upit");
	$row_pdv_kalk = mysql_fetch_array($pdv_kalk_upit);
	if (isset($row_pdv_kalk['kalkpdvsuma'])){
		$pdv_kalk=number_format($row_pdv_kalk['kalkpdvsuma'], 2,".","");
		return $pdv_kalk;
	}
	else{
		$pdv_kalk=0;
		return $pdv_kalk;
	}
}

function proracunOsnoviceIzPdva($porez_stopa,$iznos_poreza){
	$poreska_osnovica=($iznos_poreza/$porez_stopa)*100;
	echo $poreska_osnovica;
}

?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Izvestaj porez</title>
	<link rel="stylesheet" type="text/css" href="../include/css/stil2.css">
	<link rel="stylesheet" href="../include/jquery/css/jquery.ui.all.css">

	<script src="../include/jquery/jquery-1.6.2.min.js" type="text/javascript"></script>

	<script src="../include/jquery/jquery.ui.core.js"></script>
	<script src="../include/jquery/jquery.ui.widget.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker.min.js"></script>
	<script src="../include/jquery/jquery.ui.datepicker-sr-SR.js"></script>

	<script>
		$(function() {
			$( "#biracdatuma" ).datepicker($.datepicker.regional[ "sr-SR" ]);
			$( "#biracdatuma2" ).datepicker($.datepicker.regional[ "sr-SR" ]);
		});
	</script>
</head>
<body>
<?php
if (isset($_POST['datumod'])&& ($_POST['datumdo']))
{
	$od=$_POST['datumod'];
	$od2=strtotime( $od );
	$datumod=date("Y-m-d",$od2);
	$do=$_POST['datumdo'];
	$do2=strtotime( $do );
	$datumdo=date("Y-m-d",$do2);
	
	$datumod_prikaz=date("d.m.",$od2);
	$datumdo_prikaz=date("d.m.",$do2);
	$godina_prikaz=date("Y",$od2);
	
	//niza stopa
	$result_porez_niza_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 10)
								AND tarifa_stope = 10
								AND porez_datum <= '$datumdo'");
	$row_porez_niza_stopa = (mysql_fetch_array($result_porez_niza_stopa));
	$procenat_nize_stope=$row_porez_niza_stopa['porez_procenat'];
	//visa stopa
	$result_porez_visa_stopa = mysql_query("SELECT porez_procenat FROM poreske_stope
								WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = 20)
								AND tarifa_stope = 20
								AND porez_datum <= '$datumdo'");
	$row_porez_visa_stopa = (mysql_fetch_array($result_porez_visa_stopa));
	$procenat_vise_stope=$row_porez_visa_stopa['porez_procenat'];
	
	//pdv faktura
	
	$fresult18 = mysql_query("SELECT 
	dosta.broj_dost AS dosta_br_dos,
	dob_kup.naziv_kup AS dob_kup_naziv_kup,
	dosta.datum_d AS datum_dostavnice,
	dosta.izzad AS iznos_racuna
	FROM izlaz 
	LEFT JOIN roba ON izlaz.srob_dos=roba.sifra
	LEFT JOIN dosta ON izlaz.br_dos=dosta.broj_dost
	LEFT JOIN dob_kup ON dosta.sifra_fir=dob_kup.sif_kup
	WHERE dosta.datum_d >= '$datumod' AND dosta.datum_d <= '$datumdo'
	GROUP BY izlaz.br_dos
	") or die ("greska u upitu fresult18");
	?>
	<div class="nosac_sa_tabelom">
		<?php echo "<h2>PERIODICNI IZVESTAJ POREZA NA DODATU VREDNOST ZA PERIOD od " . date("d.m.Y",$od2) . " do " . date("d.m.Y",$do2) . "</h2>";?>
		<table>
			<tr>
				<th>Naziv<br />kupca</th>
				<th>Broj<br />racuna</th>
				<th>Datum</th>
				<th>Iznos<br />racuna</th>
				<th>20% pdv</th>
				<th>10% pdv</th>
			</tr>
		<?php
		$pdv_dostavnice_visastopa_zbir=0;
		$pdv_dostavnice_nizastopa_zbir=0;
		$iznos_racuna_bez_pdv_zbir=0;
		while($fniz18 = mysql_fetch_array($fresult18)) {
			$dosta_br_dos=$fniz18['dosta_br_dos'];

			$pdv_dostavnice_visastopa=PdvPoDostavnicama($procenat_vise_stope,$dosta_br_dos,20);
			$pdv_dostavnice_nizastopa=PdvPoDostavnicama($procenat_nize_stope,$dosta_br_dos,10);

			$pdv_dostavnice_visastopa_zbir += $pdv_dostavnice_visastopa;
			$pdv_dostavnice_nizastopa_zbir += $pdv_dostavnice_nizastopa;

			$iznos_racuna_bez_pdv=$fniz18['iznos_racuna']-$pdv_dostavnice_nizastopa-$pdv_dostavnice_visastopa;
			$iznos_racuna_bez_pdv_zbir += $iznos_racuna_bez_pdv;

			?>
			<tr>
				<td><?php echo $fniz18['dob_kup_naziv_kup'];?></td>
				<td><?php echo $fniz18['dosta_br_dos'];?></td>
				<td><?php echo $fniz18['datum_dostavnice'];?></td>
				<td><?php echo $iznos_racuna_bez_pdv;?></td>
				<td><?php echo $pdv_dostavnice_visastopa;?></td>
				<td><?php echo $pdv_dostavnice_nizastopa;?></td>
			</tr>

			<?php
		}
		?>
			<tr>
				<td colspan="3">Zbir:</td>
				<td><?php echo $iznos_racuna_bez_pdv_zbir;?></td>
				<td><?php echo $pdv_dostavnice_visastopa_zbir;?></td>
				<td><?php echo $pdv_dostavnice_nizastopa_zbir;?></td>
			</tr>
		</table>
		<h3>Placena akontacija poreza po Kalkulaciji</h3>
		<table>
			<tr>
				<td>Br.</td>
				<td>Ukupno</td>
				<td>20%</td>
				<td>10%</td>
			</tr>
			<?php
			$pdv_kalkulacije_visastopa_zbir=0;
			$pdv_kalkulacije_nizastopa_zbir=0;
			$pdv_kalkulacije_zbir_zbir=0;
			$kalkporez=mysql_query("SELECT broj_kalk,datum,ukal_porez 
									FROM kalk
									WHERE datum >= '$datumod' AND datum <= '$datumdo'") or die ("greska u upitu kalkporez");
			while($nizkalkporez = mysql_fetch_array($kalkporez)) {
				$broj_kal=$nizkalkporez['broj_kalk'];
				$pdv_kalkulacije_visastopa=PdvPoKalkulacijama($procenat_vise_stope,$broj_kal,20);
				$pdv_kalkulacije_nizastopa=PdvPoKalkulacijama($procenat_nize_stope,$broj_kal,10);
				$pdv_kalkulacije_zbir=$pdv_kalkulacije_visastopa+$pdv_kalkulacije_nizastopa;

				$pdv_kalkulacije_visastopa_zbir+=$pdv_kalkulacije_visastopa;
				$pdv_kalkulacije_nizastopa_zbir+=$pdv_kalkulacije_nizastopa;
				$pdv_kalkulacije_zbir_zbir+=$pdv_kalkulacije_zbir;
				?>
				<tr>
					<td><?php echo $broj_kal;?></td>
					<td><?php echo $pdv_kalkulacije_zbir;?></td>
					<td><?php echo $pdv_kalkulacije_visastopa; ?></td>
					<td><?php echo $pdv_kalkulacije_nizastopa; ?></td>
				</td>	
				<?php
			}
			?>
			<tr>
				<td><?php echo "Zbir:";?></td>
				<td><?php echo $pdv_kalkulacije_zbir_zbir;?></td>
				<td><?php echo $pdv_kalkulacije_visastopa_zbir; ?></td>
				<td><?php echo $pdv_kalkulacije_nizastopa_zbir; ?></td>
			</td>	
		</table>
		<h3>Placena akontacija poreza po Blagajni</h3>
		<table>
			<tr>
				<td>Broj blagajne</td>
				<td>Opis</td>
				<td>Datum</td>
				<td>Iznos blagajna</td>
				<td>PDV iznos</td>
			</tr>
			<?php
			$blagizn_zbir=0;
			$pdv_izn=0;
			$blagajna_upit=mysql_query("SELECT br_blag, opis_troska, blagizn, pdv_izn,datum
									FROM blagajna
									WHERE datum >= '$datumod' 
									AND datum <= '$datumdo'
									AND pdv_izn > 0") or die ("greska u upitu blagajna_upit");
			while($niz_blagajna = mysql_fetch_array($blagajna_upit)) {
				$blagizn_zbir+=$niz_blagajna['blagizn'];
				$pdv_izn+=$niz_blagajna['pdv_izn'];
				?>
				<tr>
					<td><?php echo $niz_blagajna['br_blag'];?></td>
					<td><?php echo $niz_blagajna['opis_troska'];?></td>
					<td><?php echo $niz_blagajna['datum'];?></td>
					<td><?php echo $niz_blagajna['blagizn'];?></td>
					<td><?php echo $niz_blagajna['pdv_izn'];?></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td></td>
				<td></td>
				<td>Ukupno:</td>
				<td><?php echo $blagizn_zbir;?></td>
				<td><?php echo $pdv_izn;?></td>
			</tr>
		</table>

		<h3>Placena akontacija poreza po Uslugama</h3>
		<table>
			<tr>
				<td>Broj usluge</td>
				<td>Opis</td>
				<td>Datum</td>
				<td>Iznos usluge</td>
				<td>PDV iznos</td>
			</tr>
			<?php
			$uslugeizn_zbir=0;
			$pdv_izn_usluge=0;
			$usluge_upit=mysql_query("SELECT br_usluge, opis, datum, iznosus,pdv
									FROM usluge
									WHERE datum >= '$datumod' 
									AND datum <= '$datumdo'
									AND pdv > 0") or die ("greska u upitu usluge_upit");
			while($niz_usluge = mysql_fetch_array($usluge_upit)) {
				$uslugeizn_zbir+=$niz_usluge['iznosus'];
				$pdv_izn_usluge+=$niz_usluge['pdv'];
				?>
				<tr>
					<td><?php echo $niz_usluge['br_usluge'];?></td>
					<td><?php echo $niz_usluge['opis'];?></td>
					<td><?php echo $niz_usluge['datum'];?></td>
					<td><?php echo $niz_usluge['iznosus'];?></td>
					<td><?php echo $niz_usluge['pdv'];?></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td></td>
				<td></td>
				<td>Ukupno:</td>
				<td><?php echo $uslugeizn_zbir;?></td>
				<td><?php echo $pdv_izn_usluge;?></td>
			</tr>
		</table>
		<div class="cf"></div>
		<form action="pdv_knizenje.php" method="post">
			<input type="hidden" name="datum_od" value="<?php echo $datumod; ?>"/>
			<input type="hidden" name="datum_do" value="<?php echo $datumdo; ?>"/>
			<input type="hidden" name="promet_dob_opst_stopa_pdv" value="<?php echo $pdv_dostavnice_visastopa_zbir; ?>"/>
			<input type="hidden" name="promet_dob_posebn_stopa_pdv" value="<?php echo $pdv_dostavnice_nizastopa_zbir; ?>"/>
			<input type="hidden" name="promet_dob_opst_stopa" value="<?php proracunOsnoviceIzPdva($procenat_vise_stope,$pdv_dostavnice_visastopa_zbir); ?>"/>
			<input type="hidden" name="promet_dob_posebn_stopa" value="<?php proracunOsnoviceIzPdva($procenat_nize_stope,$pdv_dostavnice_nizastopa_zbir); ?>"/>
			<input type="hidden" name="promet_dob_pdv_zbir" value="<?php echo $pdv_dostavnice_visastopa_zbir+$pdv_dostavnice_nizastopa_zbir; ?>"/>
			<input type="hidden" name="promet_dob_zbir" value="<?php echo $iznos_racuna_bez_pdv_zbir; ?>"/>
			<?php
				$prethodni_porez_pdv=$pdv_kalkulacije_zbir_zbir+$pdv_izn+$pdv_izn_usluge;

				//kalk
				$resultnab_vr = mysql_query("SELECT sum(nabav_vre) AS nabav_v FROM kalk WHERE datum >= '$datumod' AND datum <= '$datumdo'") or die ("resultnab_vr no query");
				$niznab_vr = mysql_fetch_array($resultnab_vr);
				$nabav_v=$niznab_vr['nabav_v'];
				$nab_vred_bez_pdva=$nabav_v-$pdv_kalkulacije_zbir_zbir;

				//blagajna
				$blagresult = mysql_query("SELECT sum(pdv_izn) AS pdv_blagajna, sum(blagizn) AS blagiznos FROM blagajna WHERE datum >= '$datumod' AND datum <= '$datumdo' AND pdv_izn > '0' AND blagizn > '0'") or die ("blagresult no query");
				$blagniz = mysql_fetch_array($blagresult);
				$pdv_blagajna=$blagniz['pdv_blagajna'];
				$blagiznos=$blagniz['blagiznos'];
				$blagiznos_bez_pdv = $blagiznos - $pdv_blagajna;

				//usluge
				$uslugeresult = mysql_query("SELECT sum(iznosus) AS iznosusluge, sum(pdv) AS pdv_usluge FROM usluge WHERE datum >= '$datumod' AND datum <= '$datumdo' AND pdv > '0'") or die ("uslugeresult no query");
				$uslugeniz = mysql_fetch_array($uslugeresult);
				$pdv_usluge=$uslugeniz['pdv_usluge'];
				$iznosusluge=$uslugeniz['iznosusluge'];
				$iznosusluge_bez_pdv=$iznosusluge-$pdv_usluge;


				$prethodni_porez_osnovica=$nab_vred_bez_pdva+$blagiznos_bez_pdv+$iznosusluge_bez_pdv;
			?>
			<input type="hidden" name="prethodni_porez_osnovica" value="<?php echo $prethodni_porez_osnovica; ?>"/>
			<input type="hidden" name="prethodni_porez_pdv" value="<?php echo $prethodni_porez_pdv; ?>"/>

			<input type="hidden" name="poreska_obaveza" value="<?php echo ($pdv_dostavnice_visastopa_zbir+$pdv_dostavnice_nizastopa_zbir)-$prethodni_porez_pdv; ?>"/>
			<button value="knjizenje" name="knjizenje" class="dugme_zeleno print_hide" type="submit">Proknjizi PDV</button>
		</form>
		<a href="../index.php" class="dugme_crveno_92plus4 print_hide">Pocetna strana</a>
	</div>
	<?php
}
else 
{
	?>
	<div class="nosac_glavni_400">
		<form method="post">
			<label>Datum od:</label>
			<input id="biracdatuma" type="text" name="datumod" value="" class="polje_100_92plus4" />
			<label>Datum do:</label>
			<input id="biracdatuma2" type="text" name="datumdo" value="" class="polje_100_92plus4" />
			<button type="submit" class="dugme_zeleno">Unesi</button>
			<a href="../index.php" class="dugme_crveno_92plus4">Pocetna strana</a>
		</form>
		<div class="cf"></div>
	</div>
	<?php
}
?>
</body>