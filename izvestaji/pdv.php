<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta charset="utf-8">
	<title>Porez na dodatu vrednost</title>
	<link rel="stylesheet" type="text/css" href="../include/css/pdv.css">
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
<?php require("../include/DbConnection.php");
require("../include/ConfigPDV.php");

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
	//	
	
	
	
	//pdv kalkulacija
	
	$result18 = mysql_query("SELECT sum(
		((((ulaz.cena_k/100)*(100-ulaz.rab_kalk))/100)*(100+
		
		(SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
							AND tarifa_stope = roba.porez
							AND porez_datum <= '$datumdo')		
		
		)-((ulaz.cena_k/100)*(100-ulaz.rab_kalk))
	)*ulaz.kol_kalk) AS pdv18suma, roba.sifra FROM ulaz 
	LEFT JOIN roba ON ulaz.srob_kal=roba.sifra
	LEFT JOIN kalk ON ulaz.br_kal=kalk.broj_kalk
	WHERE kalk.datum >= '$datumod' AND kalk.datum <= '$datumdo'
	AND roba.porez=20") or die ("greska u upitu result18");
	
	
	//$result18 = mysql_query("SELECT sum(pdv_18) AS pdv18suma FROM kalk WHERE datum >= '$datumod' AND datum <= '$datumdo'") or die ("no query");
	$niz18 = mysql_fetch_array($result18);
	$kpdv18suma=$niz18['pdv18suma'];
	
	//$result8 = mysql_query("SELECT sum(pdv_8) AS pdv8suma FROM kalk WHERE datum >= '$datumod' AND datum <= '$datumdo'") or die ("no query");
	$result8 = mysql_query("SELECT sum((
		(((ulaz.cena_k/100)*(100-ulaz.rab_kalk))/100)*(100+
		
		(SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
							AND tarifa_stope = roba.porez
							AND porez_datum <= '$datumdo')		
		
		)-((ulaz.cena_k/100)*(100-ulaz.rab_kalk))
	)*ulaz.kol_kalk) AS pdv8suma, roba.sifra FROM ulaz 
	LEFT JOIN roba ON ulaz.srob_kal=roba.sifra
	LEFT JOIN kalk ON ulaz.br_kal=kalk.broj_kalk
	WHERE kalk.datum >= '$datumod' AND kalk.datum <= '$datumdo'
	AND roba.porez=10") or die ("greska u upitu result8");
	$niz8 = mysql_fetch_array($result8);
	$kpdv8suma=$niz8['pdv8suma'];
	
	$resultnab_vr = mysql_query("SELECT sum(nabav_vre) AS nabav_v FROM kalk WHERE datum >= '$datumod' AND datum <= '$datumdo'") or die ("resultnab_vr no query");
	$niznab_vr = mysql_fetch_array($resultnab_vr);
	$nabav_v=$niznab_vr['nabav_v'];
	
	$kpdv8i18suma=$kpdv18suma+$kpdv8suma;
	
	//pdv faktura
	
	$fresult18 = mysql_query("SELECT 
	sum(
			(
				(
					(
						(
							(izlaz.cena_d/100)*(100-izlaz.rab_dos)
						)/100
					)*
					(100+
						(SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
							AND tarifa_stope = roba.porez
							AND porez_datum <= '$datumdo'
						)		
					)
				)-
				(
					(izlaz.cena_d/100)*(100-izlaz.rab_dos)
				)
			)*izlaz.koli_dos
		) AS fpdv18suma
	FROM izlaz 
	LEFT JOIN roba ON izlaz.srob_dos=roba.sifra
	LEFT JOIN dosta ON izlaz.br_dos=dosta.broj_dost
	WHERE dosta.datum_d >= '$datumod' AND dosta.datum_d <= '$datumdo'
	AND roba.porez=20") or die ("greska u upitu fresult18");
	
	
	//$fresult18 = mysql_query("SELECT sum(f_pdv18) AS fpdv18suma FROM dosta WHERE datum_d >= '$datumod' AND datum_d <= '$datumdo'") or die ("fresult18 no query");
	$fniz18 = mysql_fetch_array($fresult18);
	
	$fpdv18suma=$fniz18['fpdv18suma'];
	
	//
	$fresult8 = mysql_query("SELECT sum((
		(((izlaz.cena_d/100)*(100-izlaz.rab_dos))/100)*(100+
		
		(SELECT porez_procenat FROM poreske_stope
							WHERE porez_datum = (SELECT MAX(porez_datum) FROM poreske_stope WHERE tarifa_stope = roba.porez)
							AND tarifa_stope = roba.porez
							AND porez_datum <= '$datumdo')		
		
		)-((izlaz.cena_d/100)*(100-izlaz.rab_dos))
	)*izlaz.koli_dos) AS fpdv8suma, roba.sifra FROM izlaz 
	LEFT JOIN roba ON izlaz.srob_dos=roba.sifra
	LEFT JOIN dosta ON izlaz.br_dos=dosta.broj_dost
	WHERE dosta.datum_d >= '$datumod' AND dosta.datum_d <= '$datumdo'
	AND roba.porez=10") or die ("greska u upitu fresult8");
	
	//$fresult8 = mysql_query("SELECT sum(f_pdv8) AS fpdv8suma FROM dosta WHERE datum_d >= '$datumod' AND datum_d <= '$datumdo'") or die ("fresult8 no query");
	$fniz8 = mysql_fetch_array($fresult8);
	$fpdv8suma=$fniz8['fpdv8suma'];

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
	
	//fakture promet 18%
	$fakprom18=($fpdv18suma/$procenat_vise_stope)*100;
	
	//fakture promet 8%
	if ($fpdv8suma==0){
		$fakprom8=0;
		}
	else{
		$fakprom8=($fpdv8suma/$procenat_nize_stope)*100;
		}
	
	//kalk promet
	$nab_vred_bez_pdva=$nabav_v-$kpdv8i18suma;
	
	//dodate blagajne i usluge na kalkulacije
	$zbir_blag_usl_kalk_bezpdv=$nab_vred_bez_pdva+$blagiznos_bez_pdv+$iznosusluge_bez_pdv;
	$zbir_blag_usl_kalk_pdva=$pdv_blagajna+$pdv_usluge+$kpdv8i18suma;
	
	//zbir
	$zbirfakpdv=$fpdv18suma+$fpdv8suma;
	$zbirfak=$fakprom18+$fakprom8;
	
	$razlika_za_polje110=$zbirfakpdv-$zbir_blag_usl_kalk_pdva;
	
	?>
	<div>
	<div>
		<div class="a1"><p>РЕПУБЛИКА СРБИЈА<br />МИНИСТАРСТВО ФИНАНСИЈА - ПОРЕСКА УПРАВА<br />Организациона јединица <u><?php echo $org_jedinica; ?></u></p></div>
		<div class="a2"><p>Образац ПППДВ</p></div>
	</div>
	<div class="cf"></div>
	<div class="b1">
		<p>ПОРЕСКА ПРИЈАВА <br />ПОРЕЗА НА ДОДАТУ ВРЕДНОСТ</p>
	</div>
	<div class="b2">
		<div class="b2a">
			<p>ЗА ПЕРИОД ОД <u><?php echo $datumod_prikaz; ?></u> ДО <u><?php echo $datumdo_prikaz; ?></u> <u><?php echo $godina_prikaz; ?></u></p>
			<div class="cf"></div>
			<h6 class="b2godina">(година)</h6>
			<div class="cf"></div>
		</div>
	</div>
	<div class="cf"></div>
	<div class="c1">
		<div class="c1a">
			<p>Порески идентификациони број (ПИБ): <u><?php echo $pib; ?></u></p>
		</div>
		<div class="c1b">
			<div class="polje_adresa">
				<p><?php echo $naziv_adresa; ?></p>
			</div>
			<h6>(назив и адреса)</h6>
		</div>
		<div class="cf"></div>
	</div>
	<div class="cf"></div>
	<div>
		<div class="u_dinarima_bez"><h6>(у динарима, без децимала)</h6></div>
	</div>
	<div class="cf"></div>
	<div class="d1">
		<div class="centralni_elementi">
			<div class="g1">&nbsp;</div>
			<div class="g2"><b>I.  ПРОМЕТ ДОБАРА И УСЛУГА</b></div>
			<div class="g3">&nbsp;</div>
			<div class="g4"><center>Износ накнаде без ПДВ</center></div>
			<div class="g5">&nbsp;</div>
			<div class="g6"><center>ПДВ</center></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">1.</div>
			<div class="g2">Промет добара и услуга који је ослобођен ПДВ са правом на одбитак претходног пореза</div>
			<div class="g3">001</div>
			<div class="g4"><div class="polje_tekst"></div></div>
			<div class="g5">&nbsp;</div>
			<div class="g6">&nbsp;</div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">2.</div>
			<div class="g2">Промет добара и услуга који је ослобођен ПДВ без права на одбитак претходног пореза</div>
			<div class="g3">002</div>
			<div class="g4"><div class="polje_tekst"></div></div>
			<div class="g5">&nbsp;</div>
			<div class="g6">&nbsp;</div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">3.</div>
			<div class="g2">Промет добара и услуга по општој стопи</div>
			<div class="g3">003</div>
			<div class="g4"><div class="polje_tekst"><?php echo round($fakprom18); ?></div></div>
			<div class="g5">103</div>
			<div class="g6"><div class="polje_tekst"><?php echo round($fpdv18suma); ?></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">4.</div>
			<div class="g2">Промет добара и услуга по посебној стопи</div>
			<div class="g3">004</div>
			<div class="g4"><div class="polje_tekst"><?php echo round($fakprom8); ?></div></div>
			<div class="g5">104</div>
			<div class="g6"><div class="polje_tekst"><?php echo round($fpdv8suma); ?></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">5.</div>
			<div class="g2"><b>ЗБИР (1+2+3+4)</b></div>
			<div class="g3"><b>005</b></div>
			<div class="g4"><div class="polje_tekst"><b><?php echo round($zbirfak); ?></b></div></div>
			<div class="g5"><b>105</b></div>
			<div class="g6"><div class="polje_tekst"><b><?php echo round($zbirfakpdv); ?></b></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">&nbsp;</div>
			<div class="g2"><b>II. ПРЕТХОДНИ ПОРЕЗ</b></div>
			<div class="g3">&nbsp;</div>
			<div class="g4">&nbsp;</div>
			<div class="g5">&nbsp;</div>
			<div class="g6">&nbsp;</div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">6.</div>
			<div class="g2">Претходни порез плаћен приликом увоза</div>
			<div class="g3">006</div>
			<div class="g4"><div class="polje_tekst"></div></div>
			<div class="g5">106</div>
			<div class="g6"><div class="polje_tekst"></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">7.</div>
			<div class="g2">ПДВ надокнада плаћена пољопривреднику</div>
			<div class="g3">007</div>
			<div class="g4"><div class="polje_tekst"></div></div>
			<div class="g5">107</div>
			<div class="g6"><div class="polje_tekst"></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">8.</div>
			<div class="g2">Претходни порез, осим претходног пореза са ред. бр. 6. и 7.</div>
			<div class="g3">008</div>
			<div class="g4"><div class="polje_tekst"><?php echo round($zbir_blag_usl_kalk_bezpdv); ?></div></div>
			<div class="g5">108</div>
			<div class="g6"><div class="polje_tekst"><?php echo round($zbir_blag_usl_kalk_pdva); ?></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">9.</div>
			<div class="g2"><b>ЗБИР (6+7+8)</b></div>
			<div class="g3"><b>009</b></div>
			<div class="g4"><div class="polje_tekst"><b><?php echo round($zbir_blag_usl_kalk_bezpdv); ?></b></div></div>
			<div class="g5"><b>109</b></div>
			<div class="g6"><div class="polje_tekst"><b><?php echo round($zbir_blag_usl_kalk_pdva); ?></b></div></div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">&nbsp;</div>
			<div class="g2"><b>III. ПОРЕСКА ОБАВЕЗА</b></div>
			<div class="g3">&nbsp;</div>
			<div class="g4">&nbsp;</div>
			<div class="g5">&nbsp;</div>
			<div class="g6">&nbsp;</div>
		</div>
		<div class="cf"></div>
		<div class="centralni_elementi">
			<div class="g1">10.</div>
			<div class="g2">Износ ПДВ у пореском периоду (5 - 9) </div>
			<div class="g3">&nbsp;</div>
			<div class="g4">&nbsp;</div>
			<div class="g5">110</div>
			<div class="g6"><div class="polje_tekst"><b><?php echo round($razlika_za_polje110); ?></b></div></div>
		</div>
		<div class="cf"></div>
	</div>
	<div class="cf"></div>
	<div class="e1">
		<div class="centralni_elementi">
			<div class="g1">11.</div>
			<div class="g2">Повраћај:</div>
			<div class="g3">&nbsp;</div>
			<div class="g4">&nbsp;</div>
			<div class="g5">&nbsp;</div>
			<div class="g7"><big>НЕ</big></div>
			<div class="g8"><big>ДА</big></div>
			<div class="cf"></div>
			<div class="g9">&nbsp;</div>
			<div class="g10"><h6>(обавезно заокружити опцију)</h6></div>
		</div>
		<div class="cf"></div>
	</div>
	<div class="f1">
		<h5>попуњава подносилац пријаве:</h5>
		<p class="poreski_sav">Пријаву, односно њен део припремио порески саветник:</p>
		<div class="potpis_por_sav"><div class="potpis_por_sav2">&nbsp;</div></div>
		<div class="tabela_div">
			<table>
				<tr>
					<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				</tr>
			</table>
		</div>
		<div class="tabela_div">
			<table>
				<tr>
					<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				</tr>
			</table>
		</div>
		<div class="cf"></div>
		<div class="mesto"><h6>(Потпис пореског саветника)</h6></div>
		<div class="datum"><h6>(ПИБ пореског саветника)</h6></div>
		<div class="jmbg"><h6>(ЈМБГ пореског саветника)</h6></div>
		<div class="cf"></div>
		<p class="krivicnom">Под кривичном и материјалном одговорношћу изјављујем да су подаци у пријави потпуни и тачни:</p>
		<div class="potpis_mesto"><h6>(Место)</h6></div>
		<div class="potpis_datum"><h6>(Датум)</h6></div>
		<div class="potpis_lice"><h6>(Потпис одговорног лица)</h6></div>
		<div class="cf"></div>
		<div class="mp"><div class="mp2">M.P.</div></div>
		<div class="cf"></div>
		<h5>попуњава Пореска управа:</h5>
		<p class="poslednji">Потврда о пријему пореске пријаве:</p>
	</div>
	<script type="text/javascript">
        function PrintWindow()
        {                    
           window.print();            
        }
      
        
       PrintWindow();
	</script>
	
	<?php
}
else 
{
	?>
	<div id="formpoz3m">
		<div class="nosac">
		<form method="post">
		<label>Datum od: </label><input id="biracdatuma" type="text" name="datumod" value="" class="date" />
		<label>do: </label><input id="biracdatuma2" type="text" name="datumdo" value="" class="date" />
		<div class="cf"></div>
		<button type="submit" class="button">Unesi</button>
		</form>
		<div class="cf"></div>
	</div>
	<?php
}
?>
<a href="../index.php" class="button_kuci">Pocetna strana</a>
</div>
</body>